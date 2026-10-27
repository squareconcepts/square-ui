<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * <x-square-ui::inputs.datetime-picker> / <x-square-ui.inputs::datetime-picker>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik (Flux Pro) <flux:date-picker> en <flux:time-picker>.
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class DatetimePicker extends Component
{
    public string $id;

    public function __construct(
        public string $label = '',
        public bool $withTime = true,
        public bool $withSeconds = false,
        public string $placeholder = '',
    ) {
        $this->id = 'dtp-' . Str::uuid();

        if ($this->placeholder === '') {
            $this->placeholder = $this->withTime
                ? __('square-ui::square-ui.select_date_time')
                : __('square-ui::square-ui.select_date');
        }
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.datetime-picker');
    }
}
