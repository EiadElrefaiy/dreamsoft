<?php

namespace App\Http\Controllers\Seller\Color;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateColorController extends Controller
{
    // Create a new color
    public function create(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'], // Ensure product exists
            'color' => ['required', 'string'], // Color must be a string
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Image validation
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Initialize $fileName variable
        $fileName = null;

        // Check if the request contains a file named 'image'
        if ($request->hasFile('image')) {
            // Generate a unique filename based on current time and file extension
            $fileName = time() . '.' . $request->file('image')->extension();
            
            // Store the uploaded file in the 'public/images/colors' directory with the generated filename
            $request->file('image')->storeAs('public/images/colors', $fileName);
        }

        // Merge the filename with request data
        $requestData = array_merge($request->all(), ['image' => 'images/colors/'.$fileName]);

        // Create a new color instance and save it
        $color = Color::create($requestData);

        // Return success response
        return response()->json(['message' => 'Color created successfully', 'color' => $color]);
    }
}
