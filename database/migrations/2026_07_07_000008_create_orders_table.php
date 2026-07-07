<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('shipping_cost', 15, 2);
            $table->decimal('grand_total', 15, 2);
            $table->string('status', 50)->default('pending_payment'); // pending_payment, pending_confirmation, processing, shipped, completed, cancelled
            $table->text('shipping_address');
            $table->string('courier', 100);
            $table->string('tracking_number')->nullable();
            $table->timestamps();

            // Composite indexes for sales reports
            $table->index(['shop_id', 'status', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
