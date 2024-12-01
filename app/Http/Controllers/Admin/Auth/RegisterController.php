<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
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
        'phone' =>['required','string','max:255','unique:admins'],
        'password' => ['required','string','min:6','confirmed'],
    ]);

    // Create a new user record in the database
    $admin = Admin::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
    ]);

    // Authenticate the newly registered admin using the 'admin' guard
    Auth::guard('api-admin')->login($admin);

    // Generate a JWT token for the authenticated admin
    $token = Auth::guard('api-admin')->attempt(['phone' => $request->phone, 'password' => $request->password]);

    // Return a JSON response with the generated token, user details, and a success message
    return response()->json([
        'token' => $token, 
        'admin' => $admin, 
        'message' => 'User registered successfully'
    ], 201);
 }
}
