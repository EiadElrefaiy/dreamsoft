<?php

namespace App\Http\Controllers\User\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use App\Models\Piece;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateItemController extends Controller
{
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

        $validator = Validator::make($requestData, [
            'piece_id' => ['required', 'exists:pieces,id'], // Ensure piece exists
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($requestData) {
                    // Retrieve the user
                    $user = User::find($requestData['user_id']);
        
                    // Check if the user exists
                    if (!$user) {
                        $fail("The user does not exist.");
                        return;
                    }
        
                    // Retrieve the piece
                    $piece = Piece::find($requestData['piece_id']);
        
                    // Check if the piece exists in the user's cart
                    if ($piece && $user->cart->contains($piece)) {
                        $fail("The piece already exists in the user's cart.");
                        return;
                    }
        
                    // Check if the requested quantity exceeds the available quantity for the piece
                    if ($piece && $value > $piece->quantity) {
                        $fail("The quantity requested ($value) exceeds the available quantity for this piece.");
                        return;
                    }
                    
                    // Optionally, you can check additional conditions here
                },
            ],
        ]);
                
        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new item in the cart
        $cartItem = Cart::create($requestData);

        // Return success response
        return response()->json(['message' => 'Item added to cart successfully', 'cart_item' => $cartItem]);
    }
}
