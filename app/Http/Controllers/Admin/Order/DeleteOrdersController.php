<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteOrdersController extends Controller
{
    public function delete(Request $request)
    {
        // Find the color by ID
        $order = Order::find($request->id);

        // Delete the order from the database
        $order->delete();
    
        // Return success response
        return response()->json(['message' => 'Order deleted successfully']);
    }
    }
