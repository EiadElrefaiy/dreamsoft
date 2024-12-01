<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    // Retrieve the JWT token from the request's Authorization header
    $token = $request->bearerToken();

    // Set the token in the request headers with the name 'auth-token'
    $request->headers->set('auth-token', (string) $token, true);

    // Set the token in the request headers with the standard Authorization header format
    $request->headers->set('Authorization', 'Bearer '.$token, true);

    if($token){
        try
        {
            // Decode the JWT token to extract its payload
            $decoded = JWTAuth::setToken($token)->getPayload();
                
            // Extract the user ID (subject) from the decoded JWT payload
            $user_id = $decoded['sub'];
                
            // Retrieve the user record from the database based on the provided user ID
            $user = User::findOrFail($user_id);
        
            // Check if the user is blocked
            if ($user->status == "blocked") {
                // If the user is blocked, return an error response
                return response()->json(["status" => false , "msg" => "this user has been blocked"]);
            }
            // If the user is not blocked, return the JWT token and user details
            return response()->json(["status" => true , "token" => $token , "user" => $user]);
        }
        catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            // If the token is expired, return an error response
            return response()->json(["status" => false , "msg" => "token expired"]);
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            // If the token is invalid, return an error response
            return response()->json(["status" => false , "msg" => "Invalid token"]);
        }
        catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            // If the token is not found, return an error response
            return response()->json(["status" => false , "msg" => "token not found"]);
        }
        
   }

    // Extract email and password from the request
    $credentials = $request->only('phone', 'password');

    // Attempt to generate a JWT token using the provided credentials
    if (!$token = JWTAuth::attempt($credentials)) {
        // If authentication fails, return an error response
        return response()->json(['error' => 'Invalid phone or password'], 401);
    }

    // Retrieve the authenticated user
    $user = Auth::user();

    // If authentication is successful, return the JWT token and user details
    return response()->json(["data"=>['token' => $token, 'user' => $user]], 200);
    }
}
