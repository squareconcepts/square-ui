<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

class Card extends Component
{

    public function __construct(
        public string $title,
        public string $classes = '',
        public string $slotClasses = '',
        public bool $fullHeight = false,
        public bool $smallHeader = false,
        public bool $bordered = false,
        public bool $small = false,
        public $header_slot = null,
    )
    {
    }

    public function render()
    {
        return view('square-ui::blade-components.card');
    }
}