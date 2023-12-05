<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public function __construct(
        public $triggerSlot = null,
        public $slot = null,
        public $right = false,
        public string $slotClasses = '',
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.dropdown');
    }
}

