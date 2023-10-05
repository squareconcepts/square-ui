<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Squareconcepts\SquareUi\BladeComponents\Datepicker;
use Squareconcepts\SquareUi\BladeComponents\Dropdown;
use Squareconcepts\SquareUi\BladeComponents\Tabs;
use Squareconcepts\SquareUi\BladeComponents\Button;
use Squareconcepts\SquareUi\BladeComponents\Card;
use Squareconcepts\SquareUi\BladeComponents\ColorPicker;
use Squareconcepts\SquareUi\BladeComponents\SelectDropdown;
use Squareconcepts\SquareUi\BladeComponents\SignaturePad;
use Squareconcepts\SquareUi\BladeComponents\Tooltip;
use Squareconcepts\SquareUi\LivewireComponents\IconPicker;
use Squareconcepts\SquareUi\LivewireComponents\PasswordStrength;

class SquareUiServiceProvider extends ServiceProvider
{
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
        ], 'square-ui-assets');
        $this->publishes([
            __DIR__.'/../../public/css/custom.css' => public_path('vendor/square-ui/square-ui.css'),
        ], 'square-ui-assets');

        $this->loadLivewireComponents();
        $this->loadBladeComponents();
        $this->addDirectives();
    }

    public function addDirectives(  )
    {
        Blade::directive('squareUiScripts', function () {
            $path = public_path('vendor/square-ui/square-ui.js');
            return <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://kit.fontawesome.com/267cc97312.js" crossorigin="anonymous"></script>
        <script src="$path"></script>
    HTML;
        });

        Blade::directive('squareUiStyles', function () {
            $path = public_path('vendor/square-ui/square-ui.css');
            return <<<HTML
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="/vendor/square-ui/square-ui.css">
    HTML;
        });
    }

    public function loadBladeComponents(): void
    {
        Blade::component(Button::class, 'square-ui::button');
        Blade::component(Card::class, 'square-ui::card');
        Blade::component(ColorPicker::class, 'square-ui::color-picker');
        Blade::component(Datepicker::class, 'square-ui::datepicker');
        Blade::component(Dropdown::class, 'square-ui::dropdown');
        Blade::component(SelectDropdown::class, 'square-ui::select-dropdown');
        Blade::component(SignaturePad::class, 'square-ui::signature-pad');
        Blade::component(Tabs::class, 'square-ui::tabs');
        Blade::component(Tooltip::class, 'square-ui::tooltip');
    }

    public function loadLivewireComponents(): void
    {
        Livewire::component('square-ui::icon-picker', IconPicker::class);
        Livewire::component('square-ui::password-strength', PasswordStrength::class);
    }
}
