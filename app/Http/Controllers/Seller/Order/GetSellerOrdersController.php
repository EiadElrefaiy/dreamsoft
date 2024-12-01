<?php

namespace App\Http\Controllers\Seller\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Piece;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetSellerOrdersController extends Controller
{
    public function index(Request $request)
    {
                // Retrieve seller data
                $seller = Seller::find($request->id);

                if (!$seller) {
                    return response()->json(['message' => 'seller not found'], 404);
                }
                                
                // Retrieve all order items belonging to the user
                $orderItems = OrderItem::get();
    
                // Iterate through each order item
                foreach($orderItems as $item){
                    // Find the piece associated with the order item
                    $piece = Piece::find($item->piece_id);
            
                    // Update the order item's seller ID with the seller ID of the piece's product
                    $item->seller_id = $piece->color->product->seller->id;
                }
            
                    // Filter order items belonging to the current seller
                    $seller_orderItems = $orderItems->where("seller_id", $seller->id)->values();
            
                    // Assign the filtered order items to the seller's data
                    $seller->seller_orderItems = $seller_orderItems;

                    $orders = Order::with("items")->where("id", $seller_orderItems->pluck("order_id"))->get();

                    foreach ($orders as $order) {
                        foreach ($order->items as $key => $item) {
                            // Find the piece associated with the order item
                            $piece = Piece::find($item->piece_id);
                    
                            // Check if the seller ID of the piece's product matches the seller's ID
                            if ($piece->color->product->seller->id !== $seller->id) {
                                // If the seller ID doesn't match, remove the item from the items collection
                                unset($order->items[$key]);
                            } else {
                                // Update the order item's seller ID with the seller ID of the piece's product
                                $item->seller_id = $seller->id;
                            }
                        }
                    }
                 return response()->json(['orders' => $orders]);
    }
}
