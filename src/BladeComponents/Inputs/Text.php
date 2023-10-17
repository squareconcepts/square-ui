<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Text extends BaseInput
{
    public function render(): View
    {
        return view('square-ui::blade-components.inputs.text');
    }

}
