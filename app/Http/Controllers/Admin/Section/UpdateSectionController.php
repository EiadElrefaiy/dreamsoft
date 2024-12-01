<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use App\Events\SectionUpdated;
use Illuminate\Support\Facades\Cache;

class UpdateSectionController extends Controller
{
    public function update(Request $request)
    {
        // Find the section with the specified ID
        $section = Section::find($request->id);
    
        // Check if the section is not found
        if (!$section) {
            // If the section is not found, return a JSON response with a 404 status code and an error message
            return response()->json(['message' => 'Section not found'], 404);
        }
    
        // Validation rules
        $rules = [
            'image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],  // image validation
            'name' => ['string'], //name must be a string
        ];
    
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
    
        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with a 400 status code and the validation errors
            return response()->json(['errors' => $validator->errors()], 400);
        }
        // Update Color details
        $section->name = $request->name;

                // If image is provided, update it
                if ($request->hasFile('image')) {
                    // Generate a unique filename based on current time and file extension
                    $fileName = time() . '.' . $request->file('image')->extension();
            
                    // Store the uploaded file in the 'public/images/sections' directory with the generated filename
                    $request->file('image')->storeAs('public/images/sections', $fileName);
            
                    // Delete previous image if exists
                    if ($section->image) {
                        Storage::delete('public/'.$section->image);
                    }
            
                    // Update section's image path
                    $section->image = 'images/sections/'.$fileName;
                }
                    
        // Update the section with the incoming request data
        $section->save();
    
        // Dispatch the SectionUpdated event
        event(new SectionUpdated($section));

        // Invalidate the cache associated with the tag 'all_sections'
        //Redis
        /* Cache::tags(['all_sections'])->flush(); */
        
        // Remove the cached sections
        Cache::forget('all_sections');

    
        // Return a JSON response indicating success and the updated section
        return response()->json(['message' => 'Section updated successfully', 'section' => $section]);
    }


    // Update a product
    public function update_product(Request $request)
    {
        // Find the product by ID
        $product = Product::find($request->id);

        // If product not found, return error response
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'section_id' => ['exists:sections,id'],
            'seller_id' => ['exists:sellers,id'],
            'description' => ['string'],
            'name' => ['string', 'max:255'], // Max length of 255 characters
            'image' => ['image', 'max:2048'], // Image must be an image file with max size of 2MB
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

                // Update product details
                $product->name = $request->name;
                $product->description = $request->description;
                $product->section_id = $request->section_id;
        
                // If image is provided, update it
                if ($request->hasFile('image')) {
                    // Generate a unique filename based on current time and file extension
                    $fileName = time() . '.' . $request->file('image')->extension();
            
                    // Store the uploaded file in the 'public/images/users' directory with the generated filename
                    $request->file('image')->storeAs('public/images/products', $fileName);
            
                    // Delete previous image if exists
                    if ($product->image) {
                        Storage::delete('public/'.$product->image);
                    }
            
                    // Update user's image path
                    $product->image = 'images/products/'.$fileName;
                }
                    
        // Update the product with the incoming request data
        $product->save();

        // Return success response
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }
}
