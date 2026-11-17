<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Used by App\Models\CartItem; missing from the old SQL dump.
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('cartItemID');
            // userID points at buyers or artists depending on userType, so no FK.
            $table->unsignedInteger('userID');
            $table->string('userType', 20);
            $table->unsignedInteger('productID');
            $table->integer('quantity')->default(1);
            $table->dateTime('dateAdded')->nullable()->useCurrent();

            $table->index(['userID', 'userType']);
            $table->foreign('productID')->references('productID')->on('products')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
