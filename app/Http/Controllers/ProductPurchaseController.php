<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductPurchaseController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function purchase(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $product = $this->productService->purchaseProduct($product, $request->quantity);

        if ($product instanceof \Illuminate\Http\JsonResponse) {
            return $product;  
        }

        return response()->json($product);
    }
}
