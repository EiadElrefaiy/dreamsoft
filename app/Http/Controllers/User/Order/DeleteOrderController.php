<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteOrderController extends Controller
{
    // Delete a product
    public function delete(Request $request)
    {
        // Find the order by ID based on the request parameter
        $order = Order::find($request->id);
    
        // If order not found, return error response
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        // Delete the order
        $order->delete();
    
        // Return success response
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
