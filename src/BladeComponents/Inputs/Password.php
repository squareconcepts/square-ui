<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{
    public function __construct(public ?string $label = null, public bool $showPasswordStrength = true, public bool $withConfirmation = false, public ?string $confirmationField = null)
    {
        if($this->withConfirmation && empty($this->confirmationField)) {
            throw new \Exception("You must provide a confirmation field");
        }
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.password');
    }

}
