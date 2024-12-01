<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class CreateOrderController extends Controller
{
    public function create(Request $request)
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
        
        // Validate incoming request data for the order
        $validator = Validator::make($requestData, [
            'total' => ['required', 'numeric'], // Total must be numeric
            'status' => ['required', 'string', 'max:50'], // Status must be a string with max length 50
            'date' => ['required', 'date'], // Date must be a valid date
        ]);
    
        // If validation fails for the order, return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Begin a database transaction
        DB::beginTransaction();
    
            // Create a new order instance and save it
            $order = Order::create($requestData);
    
            // Validate incoming request data for the order items
            $orderItemsValidator = Validator::make($request->order_items, [
                '*.piece_id' => ['required', 'exists:pieces,id'],
                '*.quantity' => ['required', 'integer', 'min:1'],
                '*.price' => ['required', 'numeric', 'min:0'],
                '*.delivery_fees' => ['required', 'numeric'], // Color must be a string
            ]);
    
            // If validation fails for the order items, rollback the transaction and return validation errors
            if ($orderItemsValidator->fails()) {
                DB::rollback();
                return response()->json(['error' => $orderItemsValidator->errors()], 400);
            }
    
            // Create order items and associate them with the order
            foreach ($request->order_items as $itemData) {
                $orderItem = new OrderItem($itemData);
                $order->items()->save($orderItem);
            }
    
            // Commit the transaction
            DB::commit();
    
            //respones order
            $responseOrder = Order::with("items")->find($order->id);
            // Return success response with the created order and associated order items
            return response()->json(['message' => 'Order created successfully', 'order' => $responseOrder]);
        }
    }
