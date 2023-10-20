<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class SlideOverPanel extends Component
{
    public function __construct(
        public string $identifier,
        public string $title = '',
        public int $width = 400,
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.slide-over-panel');
    }
}
