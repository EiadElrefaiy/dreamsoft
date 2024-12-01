<?php

namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
    // Validate incoming request data
    $validator = Validator::make($request->all(), [
        'name' => ['required','string','max:255'],
        'phone' =>['required','string','max:255','unique:sellers'],
        'address' => ['required','string','max:255'],
        'password' => ['required','string','min:6','confirmed'],
    ]);

    // Create a new user record in the database
    $seller = Seller::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'address' => $request->address,
        'password' => bcrypt($request->password),
    ]);

    // Authenticate the newly registered seller using the 'seller' guard
    Auth::guard('api-seller')->login($seller);

    // Generate a JWT token for the authenticated seller
    $token = Auth::guard('api-seller')->attempt(['phone' => $request->phone, 'password' => $request->password]);

    // Return a JSON response with the generated token, user details, and a success message
    return response()->json([
        'token' => $token, 
        'seller' => $seller, 
        'message' => 'User registered successfully'
    ], 201);

    }
}
