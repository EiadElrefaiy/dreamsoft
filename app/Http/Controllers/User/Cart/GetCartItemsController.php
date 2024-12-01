<?php

namespace App\Http\Controllers\User\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Piece;
use App\Models\Color;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class GetCartItemsController extends Controller
{
    public function getCartItems(Request $request){
        
        // Retrieve the JWT token from the request's Authorization header
        $token = $request->bearerToken();
    
        // Decode the JWT token to extract its payload
        $decoded = JWTAuth::setToken($token)->getPayload();
            
        // Extract the user ID (subject) from the decoded JWT payload
        $user_id = $decoded['sub'];
            
        // Retrieve the user record from the database based on the provided user ID
        $user = User::findOrFail($user_id);
        
        // Retrieve all cart items belonging to the user
        $cartItems = Cart::where("user_id" , $user_id)->get();
    
        // Iterate through each cart item
        foreach($cartItems as $item){
            // Find the piece associated with the cart item
            $piece = Piece::find($item->piece_id);
    
            // Update the cart item's seller ID with the seller ID of the piece's product
            $item->seller_id = $piece->color->product->seller->id;
        }
    
        // Extract unique seller IDs from the cart items
        $sellers_id = $cartItems->unique("seller_id")->pluck("seller_id");
    
        // Retrieve seller information for each unique seller ID
        $sellers = Seller::whereIn("id" , $sellers_id)->get();
       
        // Iterate through each seller
        foreach($sellers as $seller){
            // Retrieve detailed seller information
            $seller_data = Seller::find($seller);
    
            // Filter cart items belonging to the current seller
            $seller_cartItems = $cartItems->where("seller_id", $seller->id)->values();
    
            // Assign the filtered cart items to the seller's data
            $seller->seller_cartItems = $seller_cartItems;
        }    
    
        // Return the list of sellers with their corresponding cart items
        return response()->json(['cartItems' => $cartItems , 'sellers' => $sellers]);
    }
}
