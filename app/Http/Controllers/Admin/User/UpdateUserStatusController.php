<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateUserStatusController extends Controller
{
    public function update(Request $request)
    {
        // Find the user with the specified ID
        $user = User::find($request->id);

        // If the user is not found, return a 404 error response
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validation rules
        $rules = [
            'name' => ['string','max:255'],
            'phone' => ['string', 'unique:users,phone,'.$request->id],
            'address' => ['string','max:255'],
            'password' => ['string','min:6','confirmed'],
            'email_verified_at' => ['nullable', 'date'],
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Update the user with the validated data
        $user->update($request->all());

        // Return a JSON response indicating success and the updated user
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
}
