<?php

namespace App\Http\Controllers\User\Favourite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateFavouriteController extends Controller
{
    // Create a new favourite item
    public function create(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();

        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
                        
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
                        
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
            
        // Merge the extracted user ID with the request data
        $requestData = array_merge($request->all(), ['user_id' => $user_id]);
        
        // Validate incoming request data
        $validator = Validator::make($requestData, [
            'piece_id' => ['required', 'exists:pieces,id'], // Ensure piece exists
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new favourite item
        $favourite = Favourite::create($requestData);

        // Return success response
        return response()->json(['message' => 'Favourite item created successfully', 'favourite' => $favourite]);
    }

}
