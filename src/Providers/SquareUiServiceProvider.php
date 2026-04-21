<?php

namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Squareconcepts\SquareUi\Commands\ConvertLivewireClassComponentsToMFC;
use Squareconcepts\SquareUi\Commands\UpgradeCheck;
use Squareconcepts\SquareUi\LivewireComponents\DataTable;
use Squareconcepts\SquareUi\LivewireComponents\Dialogs;
use Squareconcepts\SquareUi\LivewireComponents\IconPicker;
use Squareconcepts\SquareUi\LivewireComponents\LocalizedStringComponent;

class SquareUiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/square-ui.php', 'square-ui');
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'square-ui');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');
        $this->loadViewsFrom(__DIR__ . '/../views', 'square-ui');

        $this->loadArtisanCommands();

        $this->publishes([
            __DIR__ . '/../../config/square-ui.php' => config_path('square-ui.php'),
        ], 'square-ui-config');

        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/vendor/square-ui'),
        ], 'square-ui-views');

        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/square-ui'),
        ], 'square-ui-lang');

        $this->publishes([
            __DIR__ . '/../../public/images' => public_path('vendor/squareconcepts/square-ui'),
        ], 'square-ui-images');

        $this->loadLivewireComponents();
        $this->loadBladeComponents();

        ComponentAttributeBag::macro('addClass', function (string $class) {
            $this->class($class);
        });
    }

    private function loadBladeComponents(): void
    {
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents', 'square-ui');
        Blade::anonymousComponentPath(__DIR__ . '/../views/blade-components/svg', 'square-ui.svg');
        Blade::anonymousComponentPath(__DIR__ . '/../views/blade-components/session-message', 'square-ui.session');
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents\\Inputs', 'square-ui.inputs');
        Blade::component('square-ui::blade-components.tooltip', 'square-ui.tooltip');
        Blade::component('square-ui::blade-components.inputs.pin', 'square-ui.inputs.pin');
    }

    private function loadLivewireComponents(): void
    {

        Livewire::addNamespace(
            namespace: 'square-ui',
            classNamespace: 'Squareconcepts\SquareUi\LivewireComponents',
            classPath: __DIR__ . '/../LivewireComponents',
            classViewPath: __DIR__ . '/../views/livewire-components',
        );
        $this->loadViewsFrom(__DIR__ . '/../views/livewire-components', 'square-core');
//        Livewire::component('square-ui.data-table', DataTable::class);
//        Livewire::component('square-ui.icon-picker', IconPicker::class);
//        Livewire::component('square-ui.localized-string', LocalizedStringComponent::class);
//        Livewire::component('square-ui.dialogs', Dialogs::class);
    }

    private function loadArtisanCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConvertLivewireClassComponentsToMFC::class,
                UpgradeCheck::class,
            ]);
        }
    }
}
