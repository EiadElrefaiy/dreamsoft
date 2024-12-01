<?php

namespace App\Http\Controllers\Seller\Color;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class UpdateColorController extends Controller
{
    // Update a color
    public function update(Request $request)
    {
        // Find the color by ID
        $color = Color::find($request->id);

        // If color not found, return error response
        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'product_id' => ['exists:products,id'], // Ensure product exists
            'color' => ['string'], // Color must be a string
            'image' => ['image', 'max:2048'], // Image must be an image file with max size of 2MB
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update Color details
        $color->product_id = $request->product_id;
        $color->color = $request->color;

                // If image is provided, update it
                if ($request->hasFile('image')) {
                    // Generate a unique filename based on current time and file extension
                    $fileName = time() . '.' . $request->file('image')->extension();
            
                    // Store the uploaded file in the 'public/images/users' directory with the generated filename
                    $request->file('image')->storeAs('public/images/colors', $fileName);
            
                    // Delete previous image if exists
                    if ($color->image) {
                        Storage::delete('public/'.$color->image);
                    }
            
                    // Update user's image path
                    $color->image = 'images/colors/'.$fileName;
                }
                    
        // Update the product with the incoming request data
        $color->save();

        // Return success response
        return response()->json(['message' => 'Color updated successfully', 'color' => $color]);
    }
}
