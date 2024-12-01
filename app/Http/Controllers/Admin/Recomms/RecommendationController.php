<?php

namespace App\Http\Controllers\Admin\Recomms;

use Illuminate\Http\Request;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests\RecommendItemsToUser;

class RecommendationController extends Controller
{
    public function getRecommendations(Request $request)
    {
        $client = new Client(
            config('services.recombee.database_id'),
            config('services.recombee.private_token')
        );

        $userId = $request->input('user_id');
        $recommendationCount = 5; // Number of recommendations to fetch

        $response = $client->send(new RecommendItemsToUser($userId, $recommendationCount));

        return response()->json($response['recomms']);
    }
}
