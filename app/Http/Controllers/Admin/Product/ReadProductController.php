<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Algolia\AlgoliaSearch\SearchClient;

class ReadProductController extends Controller
{
    public function show(Request $request)
    {
        // Find the product with the specified ID
        $product = Product::with("colors")->find($request->id);
    
        // Check if the product exists
        if (!$product) {
            // If the product is not found, return a JSON response with a 404 status code and an error message
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // If the product is found, return a JSON response with the product data
        return response()->json(['product' => $product]);
    }

    public function showRecommendations(Request $request)
    {
        $productId = $request->id;

        // Fetch Algolia credentials from environment variables
        $algoliaAppId = "P7M3D1O31F";
        $algoliaApiKey = "cdbb2e66b74d5848db40c4e0194c967f";

        // Create Algolia client instance
        $client = SearchClient::create($algoliaAppId, $algoliaApiKey);
        $index = $client->initIndex('products');

        // Fetch the product details to use its attributes for recommendations
        $product = $index->getObject($productId);

        // Define filters based on the product attributes (e.g., category, brand)
        $filters = [
            'category:' . $product['category'],
            'NOT objectID:' . $productId // Exclude the current product from recommendations
        ];

        // Perform Algolia search to get recommendations
        $recommendations = $index->search('', [
            'filters' => implode(' AND ', $filters),
            'hitsPerPage' => 10
        ]);

        // Return the recommendations to the view
        return response()->json(['recommendations' => $recommendations]);
    }

}
