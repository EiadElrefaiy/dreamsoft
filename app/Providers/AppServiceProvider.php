<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use Laravel\Scout\Console\FlushCommand;
use Laravel\Scout\Console\ImportCommand;
use App\Observers\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);   
        Product::observe(ProductObserver::class);
    }
}
