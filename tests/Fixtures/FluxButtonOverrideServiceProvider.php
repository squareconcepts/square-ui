<?php

namespace Squareconcepts\SquareUi\Tests\Fixtures;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * square-ui gebruikt flux:button-varianten ('positive', 'warning') die alleen de override uit
 * squareconcepts/laravel-square-core kent. Deze provider moet vóór FluxServiceProvider booten,
 * zodat deze anonieme component-map voorrang krijgt (net als resource_path('views/flux') in een app).
 */
class FluxButtonOverrideServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::anonymousComponentPath(__DIR__ . '/views/flux', 'flux');
    }
}
