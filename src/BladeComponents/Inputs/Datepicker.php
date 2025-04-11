<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Datepicker extends Component
{
    public string $id;

    public function __construct(
        public bool $enableTime = true,
        public ?string $label = '',
    ) {
        $this->id = Str::uuid();

    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.datepicker');
    }
}

