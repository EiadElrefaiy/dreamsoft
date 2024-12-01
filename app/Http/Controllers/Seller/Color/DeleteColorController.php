<?php

namespace App\Http\Controllers\Seller\Color;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteColorController extends Controller
{
    // Delete a color
    public function delete(Request $request)
    {
        // Find the color by ID
        $color = Color::find($request->id);

        // If color not found, return error response
        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        // Delete the color
        $color->delete();

        // Return success response
        return response()->json(['message' => 'Color deleted successfully']);
    }
}
