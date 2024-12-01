<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetSellerProductsController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();

        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the seller ID (subject) from the decoded JWT payload
        $seller_id = $decoded['sub'];

        // Retrieve the seller record from the database based on the provided seller ID
        $seller = Seller::findOrFail($seller_id);

        // If seller not found, return error response
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }

        // Retrieve the products along with their colors
        $products = $seller->products()->with('colors')->get();

        // Return JSON response with seller products and their colors
        return response()->json(['seller products' => $products]);
    }
}
