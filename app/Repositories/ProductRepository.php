<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function find($id)
    {
        return Product::find($id);
    }

    public function decreaseStock($productId, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->decrement('stock', $quantity);
        }
    }
}
