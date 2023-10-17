<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Number extends BaseInput
{
    public function __construct( string $label, ?string $placeholder = null, string $type = 'number', ?string $icon = null, ?string $rightIcon = null, bool $show1Password = false , public bool $hasButtons = false)
    {
        parent::__construct($label, $placeholder, $type, $icon, $rightIcon, $show1Password);
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.number');
    }

}
