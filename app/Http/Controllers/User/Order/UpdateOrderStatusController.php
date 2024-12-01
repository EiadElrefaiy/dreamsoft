<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateOrderStatusController extends Controller
{
    // Update an order
    public function update(Request $request)
    {
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();

        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
                        
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
                        
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
            
        // Merge the extracted user ID with the request data
        $requestData = array_merge($request->all(), ['user_id' => $user_id]);

        // Find the order by ID
        $order = Order::find($request->id);

        // If order not found, return error response
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($requestData, [
            'user_id' => ['exists:users,id'], // Ensure user exists
            'total' => ['numeric'], // Total must be numeric
            'status' => ['string', 'max:50'], // Status must be a string with max length 50
            'date' => ['date'], // Date must be a valid date
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update the order with the incoming request data
        $order->update($requestData);

        // Return success response
        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }
}
