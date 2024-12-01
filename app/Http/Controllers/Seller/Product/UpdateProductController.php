<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class UpdateProductController extends Controller
{
    // Update a product
    public function update(Request $request)
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
