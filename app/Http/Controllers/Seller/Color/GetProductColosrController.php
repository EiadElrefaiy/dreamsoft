<?php

namespace App\Http\Controllers\Seller\Color;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetProductColosrController extends Controller
{
    public function index(Request $request)
    {
        // Find the product by ID based on the request parameter
        $product = Product::find($request->id);
    
        // If product not found, return error response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Return JSON response with product colors
        return response()->json(['product colors' => $product->colors]);
    }
    }
