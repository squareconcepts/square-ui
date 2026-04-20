<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

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
                ? __('Selecteer datum en tijd…')
                : __('Selecteer datum…');
        }
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.datetime-picker');
    }
}
