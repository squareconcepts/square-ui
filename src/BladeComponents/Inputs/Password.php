<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{
    // withConfirmation/confirmationField worden (nog) niet gebruikt; ze blijven optioneel voor BC.
    public function __construct(public ?string $label = null, public bool $showPasswordStrength = true, public bool $withConfirmation = false, public ?string $confirmationField = null)
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.password');
    }
}
