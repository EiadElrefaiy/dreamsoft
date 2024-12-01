<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteProductController extends Controller
{
    // Delete a product
    public function delete(Request $request)
    {
        // Find the product by ID based on the request parameter
        $product = Product::find($request->id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the product
        $product->delete();

        // Return success response
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
