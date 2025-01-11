<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            
            // Change the column to nullable and then add the foreign key constraint back
            $table->unsignedBigInteger('cart_id')->nullable()->change();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            
            // Change the column back to non-nullable (if necessary)
            $table->unsignedBigInteger('cart_id')->nullable(false)->change();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }
};
