<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class Tooltip extends Component
{
    public function __construct(
        public $message,
        public $placement = 'right',
        public $style = 'dark',
        public $classes = [])
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.tooltip');
    }
}
