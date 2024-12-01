<?php

namespace App\Http\Controllers\Seller\Piece;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piece;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeletePieceController extends Controller
{
    public function delete(Request $request)
    {
        // Find the piece by ID
        $piece = Piece::find($request->id);

        // If piece not found, return error response
        if (!$piece) {
            return response()->json(['message' => 'Piece not found'], 404);
        }

        // Delete the piece
        $piece->delete();

        // Return success response
        return response()->json(['message' => 'Piece deleted successfully']);
    }
}
