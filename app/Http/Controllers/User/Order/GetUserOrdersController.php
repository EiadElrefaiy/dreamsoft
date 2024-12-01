<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetUserOrdersController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();
    
        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
                        
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
                        
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
                
        // If user not found, return error response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Return JSON response with user's orders
        return response()->json(['user_orders' => $user->orders]);
    }
    }
