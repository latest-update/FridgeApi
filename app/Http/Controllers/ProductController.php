<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Custom\ShortResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index (): JsonResponse
    {
        return ShortResponse::json(true, 'Products are retrieved...', Product::all());
    }

    public function productById(Product $product): JsonResponse
    {
        return ShortResponse::json(true, 'Product are retrieved...', $product);
    }

    public function create (Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:4|max:255',
            'code' => 'required|string|min:1|max:255',
            'production_date' => 'nullable',
            'expired_at' => 'nullable',
            'cost' => 'required|integer',
            'image' => 'nullable|string'
        ]);

        return ShortResponse::json(true, 'Product created!', Product::create($data), 201);
    }

    public function update (Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name' => 'nullable|string|min:4|max:255',
            'code' => 'nullable|string|min:1|max:255',
            'production_date' => 'nullable',
            'expired_at' => 'nullable',
            'cost' => 'nullable|integer',
            'image' => 'nullable|string'
        ]);

        $product->update($data);
        return ShortResponse::json(true, 'Product updated', $product);
    }

    public function delete (Request $request, Product $product): JsonResponse
    {
        $product->delete();
        return ShortResponse::json(true, 'Product was deleted', $product);
    }
}
