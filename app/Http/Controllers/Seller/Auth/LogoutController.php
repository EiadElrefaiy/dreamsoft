<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout()
    {
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Invalidate the token for the user
        JWTAuth::invalidate(JWTAuth::getToken(), true, 'api-seller');
    
        // Optionally, you can also invalidate the token in the database
        $user->update(['token' => null]);
    
        return response()->json(['message' => 'Seller logged out successfully']);
    }
}
