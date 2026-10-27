<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * <x-square-ui::inputs.datepicker> / <x-square-ui.inputs::datepicker>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik (Flux Pro) <flux:date-picker> (met with-presets voor de periode-knoppen);
 *             voor datum + tijd een <flux:date-picker> plus <flux:time-picker>.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class Datepicker extends Component
{
    public string $id;

    public function __construct(
        public bool $enableTime = true,
        public ?string $label = '',
        public bool $asDropdown = true,
        public bool $showPeriods = false,
        public array $periodOption = ['today', 'tomorrow', 'week', 'next_week', 'month', 'next_month']
    ) {
        $this->id = Str::uuid();
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.datepicker');
    }
}
