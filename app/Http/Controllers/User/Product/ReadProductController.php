<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadProductController extends Controller
{
    public function show(Request $request)
    {
        // Retrieve the product with its related colors
        $product = Product::with("colors")->find($request->id);
        
        // Check if the product exists
        if (!$product) {
            // If the product is not found, return a JSON response with a 404 status code and an error message
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // If the product is found, return a JSON response with the product data
        return response()->json(['product' => $product]);
    }
}
