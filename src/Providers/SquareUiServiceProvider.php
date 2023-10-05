<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Squareconcepts\SquareUi\BladeComponents\Datepicker;
use Squareconcepts\SquareUi\BladeComponents\SecondsToTime;
use Squareconcepts\SquareUi\BladeComponents\Tabs;
use Squareconcepts\SquareUi\BladeComponents\Button;
use Squareconcepts\SquareUi\BladeComponents\Card;
use Squareconcepts\SquareUi\BladeComponents\ColorPicker;
use Squareconcepts\SquareUi\BladeComponents\SelectDropdown;
use Squareconcepts\SquareUi\BladeComponents\SignaturePad;
use Squareconcepts\SquareUi\BladeComponents\Tooltip;
use Squareconcepts\SquareUi\LivewireComponents\FontAwesomeComponent;
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
            return <<<HTML
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    HTML;
        });

        Blade::directive('squareUiStyles', function () {
            return <<<HTML
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    HTML;
        });
    }


    public function loadBladeComponents(): void
    {
//        Blade::component(Tabs::class, 'square-ui::tabs');
//        Blade::component(Card::class, 'square-ui::card');
//        Blade::component(ColorPicker::class, 'square-ui::color-picker');
//        Blade::component(SelectDropdown::class, 'square-ui::select-dropdown');
//        Blade::component(Tooltip::class, 'square-ui::tooltip');
//        Blade::component(SignaturePad::class, 'square-ui::signature-pad');
//        Blade::component(Button::class, 'square-ui::button');
//        Blade::component(Datepicker::class, 'square-ui::datepicker');
//        Blade::component(SecondsToTime::class, 'square-ui::seconds-to-time');
//        View::addNamespace('square-ui::svg', __DIR__.'../views/blade-components/svg');
        Blade::componentNamespace('Squareconcepts\\SquareUi\\BladeComponents', 'square-ui');
        Blade::anonymousComponentPath(__DIR__. '/../views/blade-components/svg', 'square-ui.svg');

    }

    public function loadLivewireComponents(): void
    {
        Livewire::component('square-ui::icon', FontAwesomeComponent::class);
        Livewire::component('square-ui::password-strength', PasswordStrength::class);
    }
}
