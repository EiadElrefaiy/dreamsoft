<?php

namespace App\Http\Controllers\User\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteAllItemsController extends Controller
{
    public function deleteAll(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();
    
        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
            
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
    
        // Find all cart items belonging to the user
        $cartItems = Cart::where('user_id', $user_id)->get();
    
        // If no cart items found, return success response
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No cart items found for the user']);
        }
    
        // Delete all cart items belonging to the user
        Cart::where('user_id', $user_id)->delete();
    
        // Return success response
        return response()->json(['message' => 'All cart items belonging to the user deleted successfully']);
    }
    }
