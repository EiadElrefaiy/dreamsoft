<?php

namespace App\Http\Controllers\User\Favourite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteFavouriteController extends Controller
{
    // Delete a favourite item
    public function delete(Request $request)
    {
        // Find the favourite item by ID
        $favourite = Favourite::find($request->id);

        // If favourite item not found, return error response
        if (!$favourite) {
            return response()->json(['message' => 'Favourite item not found'], 404);
        }

        // Delete the favourite item
        $favourite->delete();

        // Return success response
        return response()->json(['message' => 'Favourite item deleted successfully']);
    }
}
