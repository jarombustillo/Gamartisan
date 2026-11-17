<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('categoryID');
            $table->string('catName', 100);
        });

        Schema::create('charities', function (Blueprint $table) {
            $table->increments('charityID');
            $table->string('charName', 100);
            $table->text('charDesc')->nullable();
            $table->string('charConDetails', 200)->nullable();
            $table->enum('charStatus', ['available', 'unavailable'])->nullable()->default('available');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charities');
        Schema::dropIfExists('categories');
    }
};
