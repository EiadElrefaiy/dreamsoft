<?php

namespace App\Http\Controllers\User\OrderItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class DeleteOrderItemController extends Controller
{
    // Function to delete an order item by its ID
    public function delete(Request $request)
    {
        $orderItem = OrderItem::find($request->id);

        // Check if the order item exists
        if (!$orderItem) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        // Delete the order item
        $orderItem->delete();

        // Return success response
        return response()->json(['message' => 'Order item deleted successfully']);
    }
}
