<?php

namespace App\Http\Controllers\Admin\CRUD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OneCodeMultiDbController extends Controller
{
    public function login(Request $request)
    {
        // Attempt to authenticate using the 'admin' guard
        if (Auth::guard('api-admin')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
            // Authentication successful
            $admin = Auth::guard('api-admin')->user();
            
            // Generate a JWT token for the authenticated admin
            $token = Auth::guard('api-admin')->attempt(['phone' => $request->phone, 'password' => $request->password]);
            
            // Return the JWT token and delivery details in the response
            return response()->json(['token' => $token, 'admin' => $admin], 200);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Invalid phone or password'], 401);
        } 
   }
}
