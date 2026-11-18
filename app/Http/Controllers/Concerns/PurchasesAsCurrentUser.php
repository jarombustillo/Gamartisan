<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Artist;
use App\Models\Product;

/**
 * Buyers and artists can both shop. Orders reference the buyers table, so an
 * artist's purchases are filed under their linked buyer profile.
 */
trait PurchasesAsCurrentUser
{
    protected function purchaserBuyerID(): int
    {
        if (session('user_type') === 'artist') {
            return Artist::findOrFail(session('user_id'))->purchasingBuyerID();
        }

        return (int) session('user_id');
    }

    /** Sellers may not buy their own listings. */
    protected function isOwnProduct(Product $product): bool
    {
        return session('user_type') === 'artist'
            && (int) $product->artistID === (int) session('user_id');
    }

    protected function purchasesRoute(): string
    {
        return session('user_type') === 'artist' ? 'artist.purchases' : 'buyer.orders';
    }
}
