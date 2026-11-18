<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('reviewID');
            $table->unsignedInteger('buyerID');
            $table->unsignedInteger('productID');
            // Not in the old SQL dump, but ReviewController stores and checks it.
            $table->unsignedInteger('orderID')->nullable();
            $table->integer('revRating');
            $table->text('revText')->nullable();
            $table->dateTime('datePosted')->nullable()->useCurrent();

            $table->foreign('buyerID')->references('buyerID')->on('buyers')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('productID')->references('productID')->on('products')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('orderID')->references('orderID')->on('orders')
                ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('notificationID');
            $table->unsignedInteger('buyerID')->nullable();
            $table->unsignedInteger('artistID')->nullable()->index();
            $table->unsignedInteger('adminID')->nullable()->index();
            $table->string('notifType', 100);
            $table->string('notifMess', 200)->nullable();
            $table->dateTime('dateSent')->nullable()->useCurrent();
            $table->boolean('isRead')->default(false);

            $table->foreign('buyerID')->references('buyerID')->on('buyers')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->increments('reportsID');
            $table->unsignedInteger('adminID');
            $table->string('reportType', 100);
            $table->text('reportsDesc')->nullable();
            $table->dateTime('dateGenerated')->nullable()->useCurrent();

            $table->foreign('adminID')->references('adminID')->on('admin')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('reviews');
    }
};
