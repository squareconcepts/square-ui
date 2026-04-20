<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

class CodeBlock extends Component
{
    public function __construct(
    ) {}

    public function render()
    {
        return view('square-ui::blade-components.code-block');
    }
}
