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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null'); 
            $table->foreignId('cart_id')->constrained()->onDelete('cascade'); 
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('delivery_address');
            $table->string('bill');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
