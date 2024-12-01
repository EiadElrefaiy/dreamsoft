<?php

namespace App\Http\Controllers\User\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadSellerController extends Controller
{
    public function show(Request $request)
    {
        // Find the seller with the specified ID
        $seller = Seller::with("products")->find($request->id);

        // If the seller is not found, return a 404 error response
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }

        // Return the seller as a JSON response
        return response()->json(['seller' => $seller]);
    }
}
