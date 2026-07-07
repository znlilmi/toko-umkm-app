<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Membuat tabel daily_sales_summaries
        Schema::create('daily_sales_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('total_orders')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0.00);
            $table->decimal('total_commission', 15, 2)->default(0.00);
            $table->timestamps();

            // Indeks unik komposit agar data ringkasan harian per toko tidak duplikat
            $table->unique(['shop_id', 'date']);
        });
    }

    public function down(): void
    {
        // 1. Hapus tabel daily_sales_summaries
        Schema::dropIfExists('daily_sales_summaries');
    }
};
