<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class Button extends Component
{
    public function __construct(
        public ?string $label = null,
        public ?string $icon = null,
        public string $type = 'flat'
    ) {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.button');
    }
}
