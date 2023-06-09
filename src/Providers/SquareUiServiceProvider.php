<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

use Squareconcepts\SquareUi\Components\FontAwesomeComponent;
use Squareconcepts\SquareUi\Components\PasswordStrength;

class SquareUiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'square-ui');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');
        $this->loadViewsFrom(__DIR__ . '/../views', 'square-ui');

        $this->publishes([
            __DIR__ . '/../../config/square-ui.php' => config_path('square-ui.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/square-ui'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../lang' => resource_path('views/vendor/square-ui'),
        ], 'views');


        Livewire::component('square-ui::icon', FontAwesomeComponent::class);
        Livewire::component('square-ui::password-strength', PasswordStrength::class);
    }
}