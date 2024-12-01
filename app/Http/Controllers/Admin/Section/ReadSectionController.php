<?php

namespace App\Http\Controllers\Admin\Section;

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
        // Find the section with the specified ID
        $section = Section::find($request->id);
    
        // Check if the section is not found
        if (!$section) {
            // If the section is not found, return a JSON response with a 404 status code and an error message
            return response()->json(['message' => 'Section not found'], 404);
        }
    
        // If the section is found, return a JSON response with the section data
        return response()->json(['section' => $section]);
    }
    }
