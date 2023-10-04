<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
class ColorPicker extends Component
{
    public function __construct(
        public string $label = ''
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.color-picker');
    }
}
