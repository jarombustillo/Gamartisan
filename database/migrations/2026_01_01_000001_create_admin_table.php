<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('adminID');
            $table->string('adminUser', 100);
            $table->string('adminPass', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
