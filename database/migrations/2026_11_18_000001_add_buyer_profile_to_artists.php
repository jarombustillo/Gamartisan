<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sellers can buy too. Orders must point at a buyers row, so each artist
     * gets a linked buyer profile (created on their first purchase).
     */
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->unsignedInteger('buyerID')->nullable()->unique();
            $table->foreign('buyerID')->references('buyerID')->on('buyers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropForeign(['buyerID']);
            $table->dropUnique(['buyerID']);
            $table->dropColumn('buyerID');
        });
    }
};
