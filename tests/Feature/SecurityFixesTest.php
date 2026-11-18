<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Artist;
use App\Models\Buyer;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Charity;
use App\Models\Donation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityFixesTest extends TestCase
{
    use RefreshDatabase;

    private Buyer $buyer;
    private Artist $artist;
    private Admin $admin;
    private Charity $charity;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->buyer = Buyer::create(['firstName' => 'Bea', 'lastName' => 'Buyer', 'email' => 'bea@example.test', 'password' => Hash::make('secret123'), 'status' => 'active']);
        $this->artist = Artist::create(['firstName' => 'Art', 'lastName' => 'Ist', 'email' => 'art@example.test', 'artPass' => Hash::make('secret123'), 'status' => 'active']);
        $this->admin = Admin::create(['adminUser' => 'admin', 'adminPass' => Hash::make('secret123')]);
        $this->charity = Charity::create(['charName' => 'Food Bank', 'charStatus' => 'available']);
        $this->category = Category::create(['catName' => 'Paintings']);
    }

    private function product(int $stock = 5, string $status = 'available', float $price = 100): Product
    {
        return Product::create([
            'artistID' => $this->artist->artistID, 'categoryID' => $this->category->categoryID,
            'prodName' => 'Vase', 'prodPrice' => $price, 'prodStock' => $stock, 'prodStatus' => $status,
        ]);
    }

    private function asBuyer(?Buyer $buyer = null): static
    {
        $buyer ??= $this->buyer;

        return $this->withSession(['user_type' => 'buyer', 'user_id' => $buyer->buyerID, 'user_name' => $buyer->firstName]);
    }

    private function asArtist(): static
    {
        return $this->withSession(['user_type' => 'artist', 'user_id' => $this->artist->artistID, 'user_name' => 'Art']);
    }

    private function checkout(Product $product, int $qty = 1, string $token = null)
    {
        return $this->post(route('checkout.store', $product), [
            'ordQuantity' => $qty,
            'payMethod' => 'Cash on Delivery',
            'charityID' => $this->charity->charityID,
            'checkout_token' => $token ?? str_repeat('a', 40),
        ]);
    }

    // ---- 1. Checkout: atomic, stock-safe, idempotent ------------------------

    public function test_buyer_checkout_writes_order_payment_donation_and_decrements_stock(): void
    {
        $product = $this->product(stock: 3, price: 200);

        $this->asBuyer()->checkout($product, 2)->assertRedirect(route('buyer.orders'));

        $order = Order::sole();
        $this->assertSame($this->buyer->buyerID, (int) $order->buyerID);
        $this->assertEquals(400, $order->ordTotalPrice);
        $this->assertEquals(340, $order->sellerAmount);
        $this->assertEquals(20, $order->platformFee);
        $this->assertEquals(400, Payment::sole()->payAmount);
        $this->assertEquals(40, Donation::sole()->amountDonated);
        $this->assertSame(1, $product->fresh()->prodStock);
        // artist + admin + buyer notifications
        $this->assertSame(3, Notification::count());
    }

    public function test_checkout_cannot_buy_more_than_stock(): void
    {
        $product = $this->product(stock: 1);

        $this->asBuyer()->checkout($product, 2)->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(1, $product->fresh()->prodStock);
    }

    public function test_last_item_marks_product_unavailable(): void
    {
        $product = $this->product(stock: 1);

        $this->asBuyer()->checkout($product, 1);

        $this->assertSame(0, $product->fresh()->prodStock);
        $this->assertSame('unavailable', $product->fresh()->prodStatus);
    }

    public function test_unavailable_product_cannot_be_bought_even_with_stock(): void
    {
        $product = $this->product(stock: 5, status: 'unavailable');

        $this->asBuyer()->checkout($product)->assertSessionHas('error');
        $this->assertSame(0, Order::count());
    }

    public function test_resubmitted_checkout_form_places_only_one_order(): void
    {
        $product = $this->product(stock: 5);
        $token = str_repeat('t', 40);

        $this->asBuyer()->checkout($product, 1, $token);
        $this->asBuyer()->checkout($product, 1, $token)->assertSessionHas('success', 'Your order was already placed.');

        $this->assertSame(1, Order::count());
        $this->assertSame(4, $product->fresh()->prodStock);
    }

    public function test_cart_checkout_twice_places_orders_once(): void
    {
        $product = $this->product(stock: 5);
        $item = CartItem::create(['userID' => $this->buyer->buyerID, 'userType' => 'buyer', 'productID' => $product->productID, 'quantity' => 2, 'dateAdded' => now()]);
        $payload = ['payMethod' => 'Cash on Delivery', 'charityID' => $this->charity->charityID, 'selected_items' => [$item->cartItemID]];

        $this->asBuyer()->post(route('cart.checkout'), $payload)->assertRedirect(route('buyer.orders'));
        $this->asBuyer()->post(route('cart.checkout'), $payload)->assertSessionHas('error');

        $this->assertSame(1, Order::count());
        $this->assertSame(3, $product->fresh()->prodStock);
        $this->assertSame(0, CartItem::count());
    }

    public function test_failed_cart_checkout_rolls_back_everything(): void
    {
        $ok = $this->product(stock: 5);
        $short = $this->product(stock: 1);
        $ids = [];
        foreach ([[$ok, 1], [$short, 3]] as [$p, $q]) {
            $ids[] = CartItem::create(['userID' => $this->buyer->buyerID, 'userType' => 'buyer', 'productID' => $p->productID, 'quantity' => $q, 'dateAdded' => now()])->cartItemID;
        }

        $this->asBuyer()->post(route('cart.checkout'), [
            'payMethod' => 'Cash on Delivery', 'charityID' => $this->charity->charityID, 'selected_items' => $ids,
        ])->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(0, Payment::count());
        $this->assertSame(5, $ok->fresh()->prodStock);
        $this->assertSame(2, CartItem::count());
    }

    // ---- 2. Who can buy: buyers and sellers (not their own items), not admins --

    private function otherArtistsProduct(int $stock = 5): Product
    {
        $other = Artist::create(['firstName' => 'Olga', 'lastName' => 'Other', 'email' => 'olga@example.test', 'artPass' => Hash::make('secret123'), 'status' => 'active']);

        return Product::create([
            'artistID' => $other->artistID, 'categoryID' => $this->category->categoryID,
            'prodName' => 'Bowl', 'prodPrice' => 100, 'prodStock' => $stock, 'prodStatus' => 'available',
        ]);
    }

    public function test_artist_can_buy_another_artists_product_under_a_linked_buyer_profile(): void
    {
        $product = $this->otherArtistsProduct();

        $this->asArtist()->checkout($product)->assertRedirect(route('artist.purchases'));
        $this->asArtist()->checkout($product, token: str_repeat('b', 40))->assertRedirect(route('artist.purchases'));

        $this->artist->refresh();
        $this->assertNotNull($this->artist->buyerID);
        // Both orders reuse the same profile, and it is not the unrelated buyer with the same ID.
        $this->assertSame(2, Order::where('buyerID', $this->artist->buyerID)->count());
        $this->assertSame(0, Order::where('buyerID', $this->buyer->buyerID)->count());
        $this->assertSame('art@example.test', $this->artist->buyerProfile->email);
        $this->assertSame(3, $product->fresh()->prodStock);

        $this->asArtist()->get(route('artist.purchases'))->assertOk()->assertSee('Bowl');
    }

    public function test_artist_buyer_profile_does_not_take_over_an_existing_buyer_with_same_email(): void
    {
        $this->artist->update(['email' => 'bea@example.test']);

        $this->asArtist()->checkout($this->otherArtistsProduct())->assertRedirect(route('artist.purchases'));

        $this->artist->refresh();
        $this->assertNotSame($this->buyer->buyerID, $this->artist->buyerID);
        $this->assertSame(0, Order::where('buyerID', $this->buyer->buyerID)->count());
    }

    public function test_artist_can_use_cart_and_check_out(): void
    {
        $product = $this->otherArtistsProduct();

        $this->asArtist()->post(route('cart.add', $product))->assertSessionHas('success');
        $item = CartItem::firstOrFail();
        $this->assertSame('artist', $item->userType);

        $this->asArtist()->post(route('cart.checkout'), [
            'payMethod' => 'Cash on Delivery',
            'charityID' => $this->charity->charityID,
            'selected_items' => [$item->cartItemID],
        ])->assertRedirect(route('artist.purchases'));

        $this->assertSame(1, Order::where('buyerID', $this->artist->fresh()->buyerID)->count());
        $this->assertSame(0, CartItem::count());
    }

    public function test_artist_cannot_buy_own_product(): void
    {
        $product = $this->product();

        $this->asArtist()->get(route('checkout', $product))->assertRedirect(route('products.show', $product));
        $this->asArtist()->checkout($product)->assertRedirect(route('products.show', $product));
        $this->asArtist()->post(route('cart.add', $product))->assertSessionHas('error');

        // Even if one is already in the cart (e.g. added before this rule), checkout refuses it.
        $item = CartItem::create(['userID' => $this->artist->artistID, 'userType' => 'artist', 'productID' => $product->productID, 'quantity' => 1]);
        $this->asArtist()->post(route('cart.checkout'), [
            'payMethod' => 'Cash on Delivery',
            'charityID' => $this->charity->charityID,
            'selected_items' => [$item->cartItemID],
        ])->assertSessionHas('error');

        $this->assertSame(0, Order::count());
        $this->assertSame(5, $product->fresh()->prodStock);
    }

    public function test_artist_sees_status_updates_for_own_purchases(): void
    {
        $this->asArtist()->checkout($this->otherArtistsProduct());
        $buyerID = $this->artist->fresh()->buyerID;

        Notification::send('order_status', 'Your order was shipped', ['buyerID' => $buyerID]);
        Notification::send('order_status', 'Someone else\'s order', ['buyerID' => $this->buyer->buyerID]);

        $this->asArtist()->get(route('artist.dashboard'))
            ->assertSee('Your order was shipped')
            ->assertDontSee('Someone else&#039;s order', false);

        $this->asArtist()->post(route('artist.notifications.readAll'));
        $this->assertSame(0, Notification::where('buyerID', $buyerID)->where('isRead', false)->count());
        $this->assertSame(1, Notification::where('buyerID', $this->buyer->buyerID)->where('isRead', false)->count());
    }

    public function test_artist_cannot_post_reviews(): void
    {
        $this->asArtist()->post(route('reviews.store', $this->otherArtistsProduct()), ['rating' => 5])
            ->assertRedirect(route('artist.dashboard'));
    }

    public function test_admin_cannot_checkout(): void
    {
        $product = $this->product();

        $this->withSession(['user_type' => 'admin', 'user_id' => $this->admin->adminID, 'admin_id' => $this->admin->adminID])
            ->checkout($product)->assertRedirect(route('admin.dashboard'));

        $this->assertSame(0, Order::count());
    }

    // ---- 3. Uploads ---------------------------------------------------------

    public function test_upload_is_saved_with_detected_extension_not_client_one(): void
    {
        Storage::fake('uploads');
        $png = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($png, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));
        $file = new UploadedFile($png, 'evil.html', 'text/html', null, true);

        $this->asArtist()->post(route('artist.products.store'), [
            'prodName' => 'Pic', 'prodPrice' => 10, 'prodStock' => 1,
            'categoryID' => $this->category->categoryID, 'prodStatus' => 'available', 'prodImage' => $file,
        ])->assertRedirect(route('artist.products'));

        $path = Product::sole()->prodImage;
        $this->assertStringEndsWith('.png', $path);
        $this->assertStringStartsWith('/uploads/products/', $path);
        Storage::disk('uploads')->assertExists(substr($path, strlen('/uploads/')));
    }

    public function test_image_url_handles_local_paths_and_full_urls(): void
    {
        $p = $this->product();
        $p->prodImage = '/uploads/products/a.png';
        $this->assertSame(asset('uploads/products/a.png'), $p->image_url);
        $p->prodImage = 'https://bucket.example/products/a.png';
        $this->assertSame('https://bucket.example/products/a.png', $p->image_url);
        $p->prodImage = null;
        $this->assertNull($p->image_url);
    }

    // ---- 4/5/6. Login, sessions, suspension ---------------------------------

    public function test_login_gets_a_new_session_id(): void
    {
        $this->get(route('login'));
        $before = session()->getId();

        $this->post(route('login.store'), ['email' => 'bea@example.test', 'password' => 'secret123'])
            ->assertRedirect(route('products'));

        $this->assertNotSame($before, session()->getId());
        $this->assertSame('buyer', session('user_type'));
    }

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.store'), ['email' => 'bea@example.test', 'password' => 'wrong'])->assertStatus(302);
        }

        $this->post(route('login.store'), ['email' => 'bea@example.test', 'password' => 'wrong'])->assertStatus(429);
    }

    public function test_logout_destroys_the_session(): void
    {
        $this->asBuyer()->get(route('buyer.dashboard'));
        $token = session()->token();

        $this->post(route('logout'))->assertRedirect(route('home'));

        $this->assertNull(session('user_id'));
        $this->assertNotSame($token, session()->token());
    }

    public function test_suspended_buyer_is_logged_out_on_next_request(): void
    {
        $this->buyer->update(['status' => 'suspended']);

        $this->asBuyer()->get(route('buyer.dashboard'))->assertRedirect(route('login'));
        $this->assertNull(session('user_id'));
    }

    public function test_non_admin_session_with_stray_admin_id_is_not_admin(): void
    {
        $this->withSession(['user_type' => 'buyer', 'user_id' => $this->buyer->buyerID, 'admin_id' => $this->admin->adminID])
            ->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    // ---- 7. Notifications ---------------------------------------------------

    public function test_cannot_mark_another_users_notification_read(): void
    {
        $other = Buyer::create(['firstName' => 'O', 'lastName' => 'T', 'email' => 'o@example.test', 'password' => 'x', 'status' => 'active']);
        Notification::send('info', 'theirs', ['buyerID' => $other->buyerID]);
        $theirs = Notification::sole();

        $this->asBuyer()->post(route('buyer.notifications.read', $theirs->notificationID))->assertNotFound();

        $this->assertFalse($theirs->fresh()->isRead);
    }

    public function test_mark_all_read_marks_only_own_notifications(): void
    {
        $other = Buyer::create(['firstName' => 'O', 'lastName' => 'T', 'email' => 'o@example.test', 'password' => 'x', 'status' => 'active']);
        Notification::send('info', 'mine', ['buyerID' => [$this->buyer->buyerID, $this->buyer->buyerID]]);
        Notification::send('info', 'theirs', ['buyerID' => $other->buyerID]);

        $this->asBuyer()->post(route('buyer.notifications.readAll'));

        $this->assertSame(2, Notification::where('buyerID', $this->buyer->buyerID)->where('isRead', true)->count());
        $this->assertSame(0, Notification::where('buyerID', $other->buyerID)->where('isRead', true)->count());
    }

    // ---- 9. Reviews / cart uniqueness ---------------------------------------

    public function test_second_review_for_same_order_is_rejected(): void
    {
        $product = $this->product();
        $order = Order::create(['buyerID' => $this->buyer->buyerID, 'artistID' => $this->artist->artistID, 'productID' => $product->productID, 'ordQuantity' => 1, 'ordTotalPrice' => 100, 'ordStatus' => 'delivered']);
        $review = ['revRating' => 5, 'revText' => 'Great', 'orderID' => $order->orderID];

        $this->asBuyer()->post(route('reviews.store', $product), $review)->assertSessionHas('success');
        $this->asBuyer()->post(route('reviews.store', $product), $review)->assertSessionHas('error');
        $this->assertSame(1, Review::count());

        $this->expectException(UniqueConstraintViolationException::class);
        Review::create(['productID' => $product->productID, 'buyerID' => $this->buyer->buyerID, 'orderID' => $order->orderID, 'revRating' => 1]);
    }

    public function test_adding_to_cart_twice_increments_one_row_and_caps_at_stock(): void
    {
        $product = $this->product(stock: 2);

        $this->asBuyer()->postJson(route('cart.add', $product))->assertOk();
        $this->asBuyer()->postJson(route('cart.add', $product))->assertOk();
        $this->asBuyer()->postJson(route('cart.add', $product))->assertStatus(422);

        $this->assertSame(2, (int) CartItem::sole()->quantity);
    }

    public function test_cart_quantity_update_is_capped_at_stock(): void
    {
        $product = $this->product(stock: 3);
        $item = CartItem::create(['userID' => $this->buyer->buyerID, 'userType' => 'buyer', 'productID' => $product->productID, 'quantity' => 1, 'dateAdded' => now()]);

        $this->asBuyer()->putJson(route('cart.update', $item), ['quantity' => 50])
            ->assertOk()->assertJson(['quantity' => 3, 'stock' => 3]);
    }
}
