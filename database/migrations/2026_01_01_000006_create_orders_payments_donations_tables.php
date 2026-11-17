<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('orderID');
            $table->unsignedInteger('buyerID');
            $table->unsignedInteger('artistID');
            $table->unsignedInteger('productID');
            $table->integer('ordQuantity')->default(1);
            $table->decimal('ordTotalPrice', 10, 2);
            $table->decimal('sellerAmount', 10, 2)->default(0);
            $table->decimal('platformFee', 10, 2)->default(0);
            $table->dateTime('orderDate')->nullable()->useCurrent();
            $table->enum('ordStatus', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                ->nullable()->default('pending');

            $table->foreign('buyerID')->references('buyerID')->on('buyers')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('artistID')->references('artistID')->on('artists')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('productID')->references('productID')->on('products')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('paymentID');
            $table->unsignedInteger('orderID');
            $table->decimal('payAmount', 10, 2);
            $table->string('payMethod', 100);
            $table->string('payStatus', 100)->nullable()->default('pending');
            $table->dateTime('datePaid')->nullable();

            $table->foreign('orderID')->references('orderID')->on('orders')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->increments('donationID');
            $table->unsignedInteger('orderID');
            $table->unsignedInteger('charityID');
            $table->decimal('amountDonated', 10, 2);
            $table->dateTime('dateDonated')->nullable()->useCurrent();

            $table->foreign('orderID')->references('orderID')->on('orders')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('charityID')->references('charityID')->on('charities')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
    }
};
