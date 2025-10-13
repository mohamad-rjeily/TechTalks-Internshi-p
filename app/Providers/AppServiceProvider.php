<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'user' => 'App\Models\User',
            'medicine' => 'App\Models\Medicine',
            'category' => 'App\Models\Category',
            'report' => 'App\Models\Report',
            'request' => 'App\Models\Request',
            'donation' => 'App\Models\Donation',
        ]);
    }
}
