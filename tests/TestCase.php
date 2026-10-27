<?php

namespace Squareconcepts\SquareUi\Tests;

use Flux\FluxServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Squareconcepts\SquareUi\Providers\SquareUiServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        // Alleen de gratis livewire/flux: de suite moet zonder Flux Pro-licentie draaien.
        return [
            LivewireServiceProvider::class,
            FluxServiceProvider::class,
            SquareUiServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
        $app['config']->set('app.locale', 'en');
        $app['config']->set('database.default', 'testing');
    }
}
