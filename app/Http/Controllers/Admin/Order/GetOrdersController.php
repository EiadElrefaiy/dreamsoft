<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetOrdersController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve all orders from the database
        $orders = Order::with("items")->get();
    
        // Return a JSON response containing the orders
        return response()->json(['orders' => $orders]);
    }
}
