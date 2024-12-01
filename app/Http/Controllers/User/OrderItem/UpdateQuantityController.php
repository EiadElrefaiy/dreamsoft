<?php

namespace App\Http\Controllers\User\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UpdateQuantityController extends Controller
{
    // Function to update the quantity of an order item by its ID
    public function updateQuantity(Request $request)
    {
        $orderItem = OrderItem::find($request->id);

        // Check if the order item exists
        if (!$orderItem) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'quantity' => ['integer', 'min:1'],
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Update the quantity of the order item
        $orderItem->quantity = $request->quantity;
        $orderItem->save();

        // Return success response
        return response()->json(['message' => 'Order item quantity updated successfully', 'order_item' => $orderItem]);
    }
}
