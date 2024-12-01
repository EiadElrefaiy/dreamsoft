<?php

namespace App\Http\Controllers\Admin\Section;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;

class GetSectionController extends Controller
{
        public function index()
        {
            
            // File 
            // Define the cache key
            $cacheKey = 'all_sections';

            // Retrieve sections from the cache
            $sections = Cache::get($cacheKey);

            // Check if cached data exists and is valid
            if ($sections === null || $this->isCacheInvalid()) {
                // If cached data doesn't exist or is invalid, retrieve sections with their products
                $sections = Section::with("products")->get();

                // Format the data
                $data = $sections->map(function ($section) {
                    return [
                        'section' => $section,
                        'products' => $section->products->take(30)->map(function ($product) {
                            return $product;
                        }),
                    ];
                });

                // Store sections in the cache
                Cache::put($cacheKey, $data, $seconds = 60 * 10);

                return response()->json($data);
            }

            // Retrieve the timestamp when the cache was last updated
            $cacheLastUpdated = Cache::get('cache_last_updated', null);

            $lastSectionUpdate = Section::max('updated_at');
            $lastProductUpdate = Product::max('updated_at');

            // If cached data exists and is valid, return it
            return response()->json(["msg" => $sections]);
        }
        
        private function isCacheInvalid()
        {
            // Implement logic to determine if the cache is invalid
            // For example, you could compare the last modified timestamp of sections
            // stored in the cache with the timestamp of the last section update
        
            // Assuming you have a 'sections' table with a 'updated_at' column
            $lastSectionUpdate = Section::max('updated_at');
            $lastProductUpdate = Product::max('updated_at');
        
            // Retrieve the timestamp when the cache was last updated
            $cacheLastUpdated = Cache::get('cache_last_updated', null);
        
            // Compare timestamps and return true if cache is invalid
            if ($cacheLastUpdated === null) {
                // Update the timestamp of the last cache update
                return true;
            }
            if($lastSectionUpdate > $cacheLastUpdated){
                Cache::put('cache_last_updated', $lastSectionUpdate);
                return true;
            }
            if($lastProductUpdate > $cacheLastUpdated){
                Cache::put('cache_last_updated', $lastProductUpdate);
                return true;
            }        
            return false;
        }
        
            /* Redis
            // Define the cache tag
            $cacheTag = 'all_sections';

            // Retrieve sections from the cache by tag
            $sections = Cache::tags([$cacheTag])->get('sections');

            if ($sections != null) {
                return response()->json(["msg" => $sections]);
            } else {

                // Retrieve sections with their products
                $sections = Section::with("products")->get();

                // Format the data
                $data = $sections->map(function ($section) {
                    return [
                        'section' => $section->name,
                        'products' => $section->products->take(2)->map(function ($product) {
                            return $product;
                        }),
                    ];
                });

                // Store sections in the cache with the specified tag
                Cache::tags([$cacheTag])->put('sections', $data, $seconds = 60);

                return response()->json($data);
                */

        }


