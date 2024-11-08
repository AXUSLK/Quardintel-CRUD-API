<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function purchaseProduct(Product $product, int $quantity)
    {
        if ($product->quantity < $quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        $product->quantity -= $quantity;
        $product->updated_by = auth()->user()->id;
        $product->save();

        return $product;
    }
}
