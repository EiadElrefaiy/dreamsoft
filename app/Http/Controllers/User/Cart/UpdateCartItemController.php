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

class UpdateCartItemController extends Controller
{
    public function update(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();
    
        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
            
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
    
        // Find the cart item by ID
        $cartItem = Cart::find($request->id);
    
        // If cart item not found, return error response
        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }
    
        // Ensure the authenticated user owns the cart item
        if ($cartItem->user_id !== $user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Merge the extracted user ID with the request data
        $requestData = array_merge($request->all(), ['user_id' => $user_id]);
    
        // Validate incoming request data
        // Validate incoming request data
        $validator = Validator::make($requestData, [
            'piece_id' => ['required', 'exists:pieces,id'], // Ensure piece exists
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($requestData) {
                $piece = Piece::find($requestData['piece_id']);
                if (!$piece || $value > $piece->quantity) {
                    $fail("The quantity requested ($value) exceeds the available quantity for this piece.");
                }
            }],
        ]);
        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Update the cart item with the incoming request data
        $cartItem->update($requestData);
    
        // Return success response
        return response()->json(['message' => 'Cart item updated successfully', 'cart_item' => $cartItem]);
    }
    }
