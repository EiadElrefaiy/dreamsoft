<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetUsersControllers extends Controller
{
    public function index()
    {
        // Retrieve all users from the database
        $users = User::with("orders")->get();
        
        // Return the users as a JSON response
        return response()->json(['users' => $users]);
    }
}
