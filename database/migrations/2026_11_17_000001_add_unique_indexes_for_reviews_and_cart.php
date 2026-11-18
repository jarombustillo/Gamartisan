<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Let the database reject double submits that slip past the app's checks:
     * one review per order, one cart row per product per user.
     */
    public function up(): void
    {
        // Drop duplicates first (keep the oldest row) so the indexes can be created.
        DB::statement('DELETE r1 FROM reviews r1 JOIN reviews r2
            ON r1.orderID = r2.orderID AND r1.reviewID > r2.reviewID');
        DB::statement('DELETE c1 FROM cart_items c1 JOIN cart_items c2
            ON c1.userID = c2.userID AND c1.userType = c2.userType
            AND c1.productID = c2.productID AND c1.cartItemID > c2.cartItemID');

        Schema::table('reviews', function (Blueprint $table) {
            $table->unique('orderID', 'reviews_order_unique');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['userID', 'userType', 'productID'], 'cart_items_user_product_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_product_unique');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_order_unique');
        });
    }
};
