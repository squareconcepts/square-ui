<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Datepicker extends Component
{
    public string $id;

    public function __construct(
        public bool $disableWeekends = false,
        public bool $startAtMonday = true,
        public bool $enableTime = true,
        public bool $timeFormat24 = true,
        public ?string $minDate = null,
        public ?string $maxDate = null,
        public bool $timeOnly = false,
        public ?string $minTime = null,
        public ?string $maxTime = null,
        public ?string $label = '',
        public ?string $placeholder = '',
        public string $model = '',
    ) {
        $this->id = Str::uuid();

        if ($this->timeOnly) {
            $this->enableTime = true;
        }
    }

    public function render(): View
    {
        return view('square-ui::blade-components.datepicker');
    }
}

