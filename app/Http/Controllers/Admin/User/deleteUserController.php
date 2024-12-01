<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class deleteUserController extends Controller
{
    public function delete(Request $request)
    {
        // Find the user with the specified ID
        $user = User::find($request->id);

        // If the user is not found, return a 404 error response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        // Return a JSON response indicating success
        return response()->json(['message' => 'User deleted successfully']);
    }
}
