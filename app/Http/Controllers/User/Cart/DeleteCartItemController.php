<?php

namespace App\Http\Controllers\User\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteCartItemController extends Controller
{
    public function delete(Request $request)
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
    
        // Delete the cart item
        $cartItem->delete();
    
        // Return success response
        return response()->json(['message' => 'Cart item deleted successfully']);
    }
    }
