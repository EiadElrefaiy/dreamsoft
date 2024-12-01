<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadUserController extends Controller
{
    public function show(Request $request)
    {
        // Find the seller with the specified ID
        $user = User::with("orders")->find($request->id);

        // If the seller is not found, return a 404 error response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        // Return the seller as a JSON response
        return response()->json(['user' => $user]);
    }
}
