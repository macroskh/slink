<?php

namespace App\Providers;

use App\Http\Services\LinkGenerators\LinkGenerator;
use App\Http\Services\LinkService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

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
        $this->app->when(LinkService::class)
            ->needs(LinkGenerator::class)
            ->give(function (Application $app) {
                 return $app->make(config('links.generator.class'));
            });
    }
}
