<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Charity;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Places orders atomically.
 *
 * Every product row is locked (SELECT ... FOR UPDATE) before its stock is
 * checked and decremented, and the whole purchase runs in one transaction.
 * Two buyers racing for the last item cannot both succeed, and a failure
 * part-way leaves no half-written order/payment/donation rows behind.
 */
class OrderService
{
    // Revenue split: 85% seller, 10% charity, 5% platform
    private const SELLER_SHARE = 0.85;
    private const PLATFORM_SHARE = 0.05;
    private const CHARITY_SHARE = 0.10;

    /**
     * @param  array<int, int>  $quantities  productID => quantity
     * @return Collection<int, Order>
     *
     * @throws OrderException when a product is unavailable or short on stock
     */
    public function place(int $buyerID, string $buyerName, array $quantities, int $charityID, string $payMethod, ?callable $insideTransaction = null): Collection
    {
        ksort($quantities); // lock rows in a fixed order to avoid deadlocks between carts

        $result = DB::transaction(function () use ($buyerID, $quantities, $charityID, $payMethod, $insideTransaction) {
            $charity = Charity::where('charityID', $charityID)
                ->where('charStatus', 'available')
                ->first();
            if (!$charity) {
                throw new OrderException('Please choose an available charity.');
            }

            $products = Product::whereIn('productID', array_keys($quantities))
                ->orderBy('productID')
                ->lockForUpdate()
                ->get()
                ->keyBy('productID');

            foreach ($quantities as $productID => $qty) {
                $product = $products->get($productID);
                if (!$product || $product->prodStatus !== 'available') {
                    throw new OrderException(($product->prodName ?? 'A product') . ' is no longer available.');
                }
                if ($qty < 1 || $product->prodStock < $qty) {
                    throw new OrderException("{$product->prodName} only has {$product->prodStock} item(s) in stock.");
                }
            }

            $orders = collect();
            $now = now();

            foreach ($quantities as $productID => $qty) {
                $product = $products->get($productID);
                $total = round($product->prodPrice * $qty, 2);

                $order = Order::create([
                    'buyerID' => $buyerID,
                    'artistID' => $product->artistID,
                    'productID' => $product->productID,
                    'ordQuantity' => $qty,
                    'ordTotalPrice' => $total,
                    'sellerAmount' => round($total * self::SELLER_SHARE, 2),
                    'platformFee' => round($total * self::PLATFORM_SHARE, 2),
                    'orderDate' => $now,
                    'ordStatus' => 'pending',
                ]);

                $newStock = $product->prodStock - $qty;
                $product->update([
                    'prodStock' => $newStock,
                    'prodStatus' => $newStock <= 0 ? 'unavailable' : $product->prodStatus,
                ]);

                Payment::create([
                    'orderID' => $order->orderID,
                    'payAmount' => $total,
                    'payMethod' => $payMethod,
                    'payStatus' => 'pending',
                    'datePaid' => null,
                ]);

                Donation::create([
                    'orderID' => $order->orderID,
                    'charityID' => $charity->charityID,
                    'amountDonated' => round($total * self::CHARITY_SHARE, 2),
                    'dateDonated' => $now,
                ]);

                $order->setRelation('product', $product);
                $orders->push($order);
            }

            if ($insideTransaction) {
                $insideTransaction();
            }

            return ['orders' => $orders, 'charity' => $charity];
        });

        $this->notify($result['orders'], $result['charity'], $buyerID, $buyerName);

        return $result['orders'];
    }

    private function notify(Collection $orders, Charity $charity, int $buyerID, string $buyerName): void
    {
        $adminIds = Admin::pluck('adminID')->all(); // once, not once per order

        foreach ($orders as $order) {
            Notification::send('order', "New order #{$order->orderID} for {$order->product->prodName} (Qty: {$order->ordQuantity}) — ₱" . number_format($order->sellerAmount, 2) . ' earnings', [
                'artistID' => $order->artistID,
            ]);

            if ($adminIds) {
                Notification::send('order', "New order #{$order->orderID} — ₱" . number_format($order->ordTotalPrice, 2) . " from {$buyerName}", [
                    'adminID' => $adminIds,
                ]);
            }
        }

        $donated = $orders->sum(fn ($o) => round($o->ordTotalPrice * self::CHARITY_SHARE, 2));
        $ref = $orders->count() === 1 ? "your order #{$orders->first()->orderID}" : 'your orders';
        Notification::send('donation', '₱' . number_format($donated, 2) . " from {$ref} was donated to {$charity->charName}", [
            'buyerID' => $buyerID,
        ]);
    }
}
