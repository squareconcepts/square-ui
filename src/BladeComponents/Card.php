<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

class Card extends Component
{

    public function __construct(
        public string $title,
        public $header_slot = null,
    )
    {
    }

    public function render()
    {
        return view('square-ui::blade-components.card');
    }
}
