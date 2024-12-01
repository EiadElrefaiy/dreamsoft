<?php

namespace App\Http\Controllers\User\Auth;

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
        JWTAuth::invalidate(JWTAuth::getToken());
    
        // Optionally, you can also invalidate the token in the database
        $user->update(['token' => null]);
    
        return response()->json(['message' => 'User logged out successfully']);
    }
}
