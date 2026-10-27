<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * <x-square-ui::inputs.time-picker> / <x-square-ui.inputs::time-picker>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik (Flux Pro) <flux:time-picker>.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class TimePicker extends Component
{
    public string $id;

    public function __construct(
        public ?string $label = '',
        public bool $asDropdown = false,
    ) {
        $this->id = Str::uuid();

    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.timepicker');
    }
}
