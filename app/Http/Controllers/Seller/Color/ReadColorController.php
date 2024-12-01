<?php

namespace App\Http\Controllers\Seller\Color;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadColorController extends Controller
{
    // Get a specific color by ID
    public function show(Request $request)
    {
        // Find the color by ID
        $color = Color::find($request->id);

        // If color not found, return error response
        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }

        // Return color details
        return response()->json(['color' => $color]);
    }
}
