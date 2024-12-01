<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Attempt to authenticate using the 'seller' guard
        if (Auth::guard('api-seller')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            // Authentication successful
            $seller = Auth::guard('api-seller')->user();
            
            // Generate a JWT token for the authenticated seller
            $token = Auth::guard('api-seller')->attempt(['phone' => $request->phone, 'password' => $request->password]);
            
            // Return the JWT token and delivery details in the response
            return response()->json(['token' => $token, 'seller' => $seller], 200);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Invalid phone or password'], 401);
        } 
   }
}
