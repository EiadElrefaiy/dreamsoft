<?php

namespace App\Http\Controllers\Admin\Piece;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piece;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadPieceController extends Controller
{
    // Get a specific piece by ID
    public function show(Request $request)
    {
        // Find the piece by ID
        $piece = Piece::with("color")->find($request->id);

        // If piece not found, return error response
        if (!$piece) {
            return response()->json(['message' => 'Piece not found'], 404);
        }

        // Return piece details
        return response()->json(['piece' => $piece]);
    }
}
