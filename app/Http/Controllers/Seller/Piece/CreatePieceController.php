<?php

namespace App\Http\Controllers\Seller\Piece;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piece;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreatePieceController extends Controller
{
    public function create(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'color_id' => ['required', 'exists:colors,id'], // Ensure color exists
            'size' => ['required', 'string'], // Size must be a string
            'price' => ['required', 'numeric'], // Price must be numeric
            'old_price' => ['required', 'numeric'], // Old price must be a string
            'delivery_fees' => ['required', 'numeric'], // 	Delivery_fees must be numeric
            'quantity' => ['required', 'integer'], // Quantity must be an integer
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new piece instance and save it
        $piece = Piece::create($request->all());

        // Return success response
        return response()->json(['message' => 'Piece created successfully', 'piece' => $piece]);
    }

}
