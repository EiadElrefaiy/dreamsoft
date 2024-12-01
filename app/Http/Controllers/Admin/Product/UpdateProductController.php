<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateProductController extends Controller
{
    // Update a product
    public function update(Request $request)
    {
        // Find the product by ID
        $product = Product::find($request->id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'section_id' => ['exists:sections,id'],
            'seller_id' => ['exists:sellers,id'],
            'description' => ['string'],
            'name' => ['string', 'max:255'], // Max length of 255 characters
            'image' => ['string'],
            'rate' => ['numeric'],
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update the product with the incoming request data
        $product->update($request->all());

        // Return success response
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }
}
