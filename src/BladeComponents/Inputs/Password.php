<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{
    public function __construct(public ?string $label = null, public bool $showPasswordStrength = true)
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.password');
    }

}
