<?php

namespace App\Http\Controllers\Seller\Piece;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetColorPiecesController extends Controller
{
    public function index(Request $request)
    {
        // Find the color by ID based on the request parameter
        $color = Color::find($request->id);
    
        // If color not found, return error response
        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }
    
        // Return JSON response with color pieces
        return response()->json(['color pieces' => $color->pieces]);
    }
    }
