<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Currency extends BaseInput
{
    public function __construct( string $label, ?string $placeholder = null, string $type = 'text', bool $show1Password = false, public $dollar = false)
    {
        parent::__construct($label, $placeholder, $type, 'euro', null, $show1Password, null);
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.currency');
    }

}
