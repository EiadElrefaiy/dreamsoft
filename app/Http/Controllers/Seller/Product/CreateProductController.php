<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateProductController extends Controller
{
    // Create a new product
    public function create(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();

        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the seller ID (subject) from the decoded JWT payload
        $seller_id = $decoded['sub'];

         // Retrieve the seller record from the database based on the provided seller ID
         $seller = Seller::findOrFail($seller_id);
       
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'section_id' => ['required', 'exists:sections,id'],
            'description' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'], 
            'image' => ['required', 'image', 'max:2048'], // Image must be an image file with max size of 2MB
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
            
            // Store the uploaded file in the 'public/images/deliveries' directory with the generated filename
            $request->file('image')->storeAs('public/images/products', $fileName);
        }

        // Merge the filename with request data
        $requestData = array_merge($request->all(), ['seller_id'=> $seller->id ,'image' => 'images/products/'.$fileName]);

        // Create a new product instance and save it
        $product = Product::create($requestData);

        // Return success response
        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }
}
