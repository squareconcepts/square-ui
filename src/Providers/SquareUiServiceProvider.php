<?php
namespace Squareconcepts\SquareUi\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
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


        Livewire::component('square-ui::icon', FontAwesomeComponent::class);
        Livewire::component('square-ui::password-strength', PasswordStrength::class);

        Blade::component(Tabs::class, 'square-ui::tabs');

        Blade::component(Card::class, 'square-ui::card');
        Blade::component(ColorPicker::class, 'square-ui::color-picker');
        Blade::component(SelectDropdown::class, 'square-ui::select-dropdown');
        Blade::component(Tooltip::class, 'square-ui::tooltip');
        Blade::component(SignaturePad::class, 'square-ui::signature-pad');
        Blade::component(Button::class, 'square-ui::button');
    }
}
