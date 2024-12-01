<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
    // Validate incoming request data
    $validator = Validator::make($request->all(), [
        'name' => ['required','string','max:255'],
        'phone' =>['required','string','max:255','unique:users'],
        'address' => ['required','string','max:255'],
        'password' => ['required','string','min:6','confirmed'],
    ]);
    
        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

    // Create a new user record in the database
    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'password' => bcrypt($request->password),
    ]);

    // Generate a JWT token for the newly registered user
    $token = JWTAuth::fromUser($user);

    // Return a JSON response with the generated token, user details, and a success message
    return response()->json([
        'message' => 'User registered successfully',
        'token' => $token, 
        'user' => $user, 
    ], 201);

    }
}
