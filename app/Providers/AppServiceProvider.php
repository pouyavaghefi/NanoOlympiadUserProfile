<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use View;

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
        View::composer('*', function ($view) {
            $bases = DB::table('base_infos')
                ->whereIn('type', [
                    'siteName',
                    'siteDescription',
                    'siteOwner',
                    'siteUrl',
                    'subdomain1Url',
                    'subdomain2Url',
                    'siteLogo',
                    'siteFavicon',
                    'siteLangs'
                ])
                ->pluck('value', 'type');

            $view->with('bases', $bases);
        });
    }
}
