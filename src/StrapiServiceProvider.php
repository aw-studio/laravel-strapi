<?php

namespace AwStudio\LaravelStrapi;

use AwStudio\LaravelStrapi\Console\Commands\MakeStrapiComponent;
use AwStudio\LaravelStrapi\Console\Commands\MakeStrapiModel;
use AwStudio\LaravelStrapi\View\Components\Dynamiczone;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class StrapiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-strapi.php', 'laravel-strapi'
        );

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'strapi');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeStrapiModel::class,
                MakeStrapiComponent::class,
            ]);
        }
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-strapi.php' => config_path('laravel-strapi.php'),
        ], 'laravel-strapi-config');

        Blade::component('dynamiczone', Dynamiczone::class);
    }
}
