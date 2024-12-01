<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    public function logout()
    {
        // Get the currently authenticated admin
        $admin = auth()->user();
    
        // Invalidate the token for the admin
        JWTAuth::invalidate(JWTAuth::getToken(), true, 'api-admin');
    
        // Optionally, you can also invalidate the token in the database
        $admin->update(['token' => null]);
    
        return response()->json(['message' => 'Admin logged out successfully']);
    }
}
