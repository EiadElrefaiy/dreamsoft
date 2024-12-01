<?php

namespace App\Http\Controllers\User\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReadSectionController extends Controller
{
    public function show(Request $request)
    {
        $section = Section::with("products")->find($request->id);

        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        return response()->json(['section' => $section]);
    }
}
