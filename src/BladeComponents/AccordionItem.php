<?php

    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class AccordionItem extends Component
    {
        public function __construct(public $title, public $index)
        {
        }

        public function render(): View
        {
            return view('square-ui::blade-components.accordion-item');
        }
    }
