<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Number extends Component
{
    public function __construct(
    ) {}

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.number');
    }
}
