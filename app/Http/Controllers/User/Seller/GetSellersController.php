<?php

namespace App\Http\Controllers\User\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class GetSellersController extends Controller
{
    public function index()
    {
        // Retrieve all sellers from the database
        $sellers = Seller::with("products")->get();
        
        // Return the sellers as a JSON response
        return response()->json(['sellers' => $sellers]);
    }
}
