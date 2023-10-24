<?php

    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class Accordion extends Component
    {
        public function __construct(public $selected = null)
        {
        }

        public function render(): View
        {
            return view('square-ui::blade-components.accordion');
        }
    }
