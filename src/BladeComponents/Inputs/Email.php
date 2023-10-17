<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Email extends BaseInput
{
    public function __construct( string $label, ?string $placeholder = null, string $type = 'email', bool $show1Password = false, )
    {
        parent::__construct($label, $placeholder, $type, 'envelope', null, $show1Password, null);
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.email');
    }

}
