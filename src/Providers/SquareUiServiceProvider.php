<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Squareconcepts\SquareUi\LivewireComponents\DataTable;
use Squareconcepts\SquareUi\LivewireComponents\Editor;
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
            __DIR__.'/../../public/js/main.js' => public_path('vendor/square-ui/square-ui.js'),
            __DIR__.'/../../public/js/ckeditor/ckeditor.js' => public_path('vendor/square-ui/ckeditor.js'),
        ], 'square-ui-assets');
        $this->publishes([
            __DIR__.'/../../public/css/custom.css' => public_path('vendor/square-ui/square-ui.css'),
        ], 'square-ui-assets');

        $this->loadLivewireComponents();
        $this->loadBladeComponents();
        $this->addDirectives();
        $this->addDisks();
        $this->addRoutes();
    }

    public function addDirectives(  )
    {
        Blade::directive('squareUiScripts', function () {
            $path = '/vendor/square-ui/square-ui.js';
            return <<<HTML
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <script src="https://kit.fontawesome.com/267cc97312.js" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/marked@2.1.3/marked.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
                <script src="$path"></script>
                <script src="/vendor/square-ui/ckeditor.js"></script>
            HTML;
        });

        Blade::directive('squareUiStyles', function () {
            $path = '/vendor/square-ui/square-ui.css';
            return <<<HTML
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
                <link rel="stylesheet" href="$path">
            HTML;
        });
    }

    public function loadBladeComponents(): void
    {
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents', 'square-ui');
        Blade::anonymousComponentPath(__DIR__. '/../views/blade-components/svg', 'square-ui.svg');
    }

    public function loadLivewireComponents(): void
    {
        Livewire::component('square-ui::data-table', DataTable::class);
        Livewire::component('square-ui::editor', Editor::class);
        Livewire::component('square-ui::icon-picker', IconPicker::class);
        Livewire::component('square-ui::localized-string', LocalizedStringComponent::class);
        Livewire::component('square-ui::password-strength', PasswordStrength::class);
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
