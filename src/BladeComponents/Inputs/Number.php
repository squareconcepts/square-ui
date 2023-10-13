<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Number extends BaseInput
{

    public function __construct($label, $placeholder = null, $icon = null, $rightIcon = null, public bool $hasButtons = false)
    {
        $type = $this->hasButtons ? 'text' : 'number';
        parent::__construct(label: $label, placeholder: $placeholder, type: $type, icon: $icon, rightIcon: $rightIcon);
    }
    public function render(): View
    {
        return view('square-ui::blade-components.inputs.number');
    }

}
