<?php

namespace App\Services;

use App\Models\Product;
use Algolia\AlgoliaSearch\SearchClient;

class AlgoliaImportService
{
    public function importProducts()
    {
        $client = SearchClient::create(
            env('ALGOLIA_APP_ID'),
            env('ALGOLIA_SECRET')
        );

        $index = $client->initIndex('products');

        $products = Product::all();

        $objects = $products->map(function ($product) {
            return array_merge(['objectID' => $product->id], $product->toArray());
        })->toArray();

        $index->saveObjects($objects, ['autoGenerateObjectIDIfNotExist' => true]);

        return count($objects);
    }
}
