<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadOrderController extends Controller
{
    public function show(Request $request)
    {
        // Find the order by ID
        $order = Order::with("items")->find($request->id);

        // If order not found, return error response
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Return order details
        return response()->json(['order' => $order]);
    }
}
