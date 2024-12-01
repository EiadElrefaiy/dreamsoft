<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreatSectionController extends Controller
{
    // Validate the request data and create a new section
    public function create(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],  // Image validation
            'name' => ['required', 'string'], // Name must be a string
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Initialize $fileName variable
        $fileName = null;

        // Check if the request contains a file named 'image'
        if ($request->hasFile('image')) {
            // Generate a unique filename based on current time and file extension
            $fileName = time() . '.' . $request->file('image')->extension();
            
            // Store the uploaded file in the 'public/images/sections' directory with the generated filename
            $request->file('image')->storeAs('public/images/sections', $fileName);
        }

        // Merge the filename with request data
        $requestData = array_merge($request->all(), ['image' => 'images/sections/'.$fileName]);

        // Create a new section instance and save it
        $section = Section::create($requestData);

        // Return success response
        return response()->json(['message' => 'Section created successfully', 'section' => $section], 201);
    }
}
