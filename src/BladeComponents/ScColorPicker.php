<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
class ScColorPicker extends Component
{
    public function __construct(
        public string $label = ''
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.sc-color-picker');
    }
}
