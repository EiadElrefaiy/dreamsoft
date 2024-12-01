<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateSellerStatusController extends Controller
{
    public function update(Request $request)
    {
        // Find the seller with the specified ID
        $seller = Seller::find($request->id);

        // If the seller is not found, return a 404 error response
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }

        // Validation rules
        $rules = [
            'name' => ['string','max:255'],
            'phone' => ['string', 'unique:sellers,phone,'.$request->id],
            'address' => ['string','max:255'],
            'password' => ['string','min:6','confirmed'],
            'email_verified_at' => ['nullable', 'date'],
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update the seller with the validated data
        $seller->update($request->all());

        // Return a JSON response indicating success and the updated seller
        return response()->json(['message' => 'Seller updated successfully', 'seller' => $seller]);
    }

}
