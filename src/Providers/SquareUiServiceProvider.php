<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Squareconcepts\SquareUi\LivewireComponents\DataTable;
use Squareconcepts\SquareUi\LivewireComponents\IconPicker;
use Squareconcepts\SquareUi\LivewireComponents\LocalizedStringComponent;
use Squareconcepts\SquareUi\LivewireComponents\PasswordStrength;

class SquareUiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/square-ui.php', 'square-ui'
        );
    }

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'square-ui');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../lang');
        $this->loadViewsFrom(__DIR__ . '/../views', 'square-ui');

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
            __DIR__.'/../../public/js/ckeditor/ckeditor.js' => public_path('vendor/square-ui/ckeditor.js'),
        ], 'square-ui-assets');

        $this->publishes([
            __DIR__.'/../../public/images' => public_path('vendor/squareconcepts/square-ui'),
        ], 'square-ui-images');

        $this->loadLivewireComponents();
        $this->loadBladeComponents();
        $this->addDisks();
        $this->addRoutes();
        ComponentAttributeBag::macro('addClass', function (string $class) {
            $this->class($class);
        });
    }

    public function loadBladeComponents(): void
    {
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents', 'square-ui');
        Blade::anonymousComponentPath(__DIR__. '/../views/blade-components/svg', 'square-ui.svg');
        Blade::anonymousComponentPath(__DIR__. '/../views/blade-components/alerts', 'square-ui.alerts');
        Blade::anonymousComponentPath(__DIR__. '/../views/blade-components/session-message', 'square-ui.session');
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents\\Inputs', 'square-ui.inputs');
        Blade::component('square-ui::blade-components.tooltip', 'square-ui.tooltip');
        Blade::component('square-ui::blade-components.inputs.pin', 'square-ui.inputs.pin');
    }

    public function loadLivewireComponents(): void
    {
        Livewire::component('square-ui::data-table', DataTable::class);
        Livewire::component('square-ui::icon-picker', IconPicker::class);
        Livewire::component('square-ui::localized-string', LocalizedStringComponent::class);
    }

    public function addDisks(): void
    {
        $this->app['config']['filesystems.disks.square-ui'] = [
            'driver' => 'local',
            'root' => storage_path('app/public/square-ui-uploads'),
            'url' => config('app.url').'/storage/square-ui-uploads',
            'visibility' => 'public',
            'throw' => false,
        ];
    }

    public function addRoutes(): void
    {
        Route::group([
            'prefix' => 'square-ui',
            'as' => 'square-ui.'
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

    }
}
