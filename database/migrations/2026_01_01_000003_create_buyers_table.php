<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->increments('buyerID');
            $table->string('firstName', 100);
            $table->string('midName', 100)->nullable();
            $table->string('lastName', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('status', ['active', 'suspended'])->nullable()->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
