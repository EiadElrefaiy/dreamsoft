<?php

namespace App\Http\Controllers\User\Favourite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favourite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserFavouriteController extends Controller
{
    public function index(Request $request){
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();
    
        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
            
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
        
        // Retrieve the user favourites from the database based on user ID
        $favourites = Favourite::where("user_id" , $user->id)->get();

        // Return the list of sellers with their corresponding cart items
        return response()->json(['favourites' => $favourites]);        
    }
}
