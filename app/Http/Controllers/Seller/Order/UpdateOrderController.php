<?php

namespace App\Http\Controllers\Seller\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateOrderController extends Controller
{
    public function update(Request $request)
    {
        // Find the order by ID
        $order = Order::with("items")->find($request->id);

        // If order not found, return error response
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
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
        $order->update($request->all());

        // Return success response
        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }
}
