<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('qty'); // amount of stock changed
            $table->string('type', 10); // IN, OUT
            $table->string('description')->nullable(); // e.g., Stock Awal, Penyesuaian, Penjualan, Pembatalan
            $table->timestamps();

            // Composite index for stock ledger report
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
