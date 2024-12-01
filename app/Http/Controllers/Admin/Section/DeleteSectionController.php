<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteSectionController extends Controller
{
    public function delete($id)
    {
        // Find the section with the specified ID
        $section = Section::find($id);
    
        // Check if the section is not found
        if (!$section) {
            // If the section is not found, return a JSON response with a 404 status code and an error message
            return response()->json(['message' => 'Section not found'], 404);
        }
    
        // If the section is found, delete it from the database
        $section->delete();
    
        // Return a JSON response indicating success
        return response()->json(['message' => 'Section deleted successfully']);
    }
    }
