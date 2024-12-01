<?php

namespace App\Http\Controllers\User\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetSectionsController extends Controller
{
    public function index()
    {
        // Retrieve all sections from the database
        $sections = Section::with("products")->get();

        // Return JSON response with sections data
        return response()->json(['sections' => $sections]);
    }
}
