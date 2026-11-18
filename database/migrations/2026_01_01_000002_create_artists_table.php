<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->increments('artistID');
            $table->string('firstName', 100);
            $table->string('midName', 100)->nullable();
            $table->string('lastName', 100);
            $table->string('email', 100)->unique();
            $table->string('artPass', 255);
            $table->text('artBio')->nullable();
            $table->enum('status', ['active', 'suspended'])->nullable()->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
