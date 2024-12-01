<?php

namespace App\Http\Controllers\Seller\Piece;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piece;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class UpdatePieceController extends Controller
{
    // Update a piece
    public function update(Request $request)
    {
        // Find the piece by ID
        $piece = Piece::find($request->id);

        // If piece not found, return error response
        if (!$piece) {
            return response()->json(['message' => 'Piece not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'color_id' => ['exists:colors,id'], // Ensure color exists
            'size' => ['string'], // Size must be a string
            'price' => ['numeric'], // Price must be numeric
            'old_price' => ['string'], // Old price must be a string
            'quantity' => ['integer'], // Quantity must be an integer
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update Pieces details
        $piece->color_id = $request->color_id;
        $piece->size = $request->size;
        $piece->price = $request->price;
        $piece->old_price = $request->old_price;
        $piece->quantity = $request->quantity;
        
        // If image is provided, update it
        if ($request->hasFile('image')) {
            // Generate a unique filename based on current time and file extension
            $fileName = time() . '.' . $request->file('image')->extension();

            // Store the uploaded file in the 'public/images/users' directory with the generated filename
            $request->file('image')->storeAs('public/images/pieces', $fileName);

            // Delete previous image if exists
            if ($piece->image) {
                Storage::delete('public/'.$piece->image);
            }

            // Update user's image path
            $color->image = 'images/pieces/'.$fileName;
        }
            
        // Update the product with the incoming request data
        $piece->save();
        
        // Return success response
        return response()->json(['message' => 'Piece updated successfully', 'piece' => $piece]);
    }

}
