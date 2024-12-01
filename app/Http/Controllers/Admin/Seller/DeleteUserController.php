<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteUserController extends Controller
{
    public function delete(Request $request)
    {
        // Find the seller with the specified ID
        $seller = Seller::find($request->id);

        // If the seller is not found, return a 404 error response
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }

        // Delete the seller
        $seller->delete();

        // Return a JSON response indicating success
        return response()->json(['message' => 'Seller deleted successfully']);
    }
}
