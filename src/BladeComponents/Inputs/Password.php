<?php

namespace Squareconcepts\SquareUi\BladeComponents\Inputs;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Squareconcepts\SquareUi\Helpers\BaseInput;

class Password extends BaseInput
{
    public function __construct( string $label, ?string $placeholder = null, string $type = 'password', bool $show1Password = false, )
    {
        parent::__construct($label, $placeholder, $type, 'key', 'eye', $show1Password, null);
    }

    public function render(): View
    {
        return view('square-ui::blade-components.inputs.password');
    }

}
