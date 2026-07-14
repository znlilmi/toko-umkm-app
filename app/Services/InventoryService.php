<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMutation;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust stock (IN/OUT) for a product and log the mutation.
     *
     * @param Product $product
     * @param int $qty
     * @param string $type
     * @param string|null $description
     * @return Product
     */
    public function adjustStock(Product $product, int $qty, string $type, ?string $description = null): Product
    {
        return DB::transaction(function () use ($product, $qty, $type, $description) {
            if ($type === 'OUT') {
                abort_if($product->stock < $qty, 422, 'Stok tidak mencukupi untuk pengurangan.');
                $product->decrement('stock', $qty);
            } else {
                $product->increment('stock', $qty);
            }

            StockMutation::create([
                'product_id'  => $product->id,
                'qty'         => $qty,
                'type'        => $type,
                'description' => $description ?? 'Penyesuaian Manual',
            ]);

            return $product->fresh();
        });
    }
}
