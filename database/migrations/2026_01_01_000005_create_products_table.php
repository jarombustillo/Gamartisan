<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('productID');
            $table->unsignedInteger('artistID');
            $table->unsignedInteger('categoryID');
            $table->string('prodName', 100);
            $table->text('prodDesc')->nullable();
            $table->decimal('prodPrice', 10, 2);
            $table->string('prodImage', 255)->nullable();
            $table->integer('prodStock')->default(0);
            $table->enum('prodStatus', ['available', 'unavailable'])->nullable()->default('available');

            $table->foreign('artistID')->references('artistID')->on('artists')
                ->cascadeOnDelete()->cascadeOnUpdate();
            // No cascade on delete: a category in use cannot be removed.
            $table->foreign('categoryID')->references('categoryID')->on('categories')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
