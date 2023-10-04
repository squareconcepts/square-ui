<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class SignaturePad extends Component
{
    public function __construct(
        public string $label = 'Signature',
        public string $saveButtonText = 'Save',
        public string $clearText = 'Clear',
    ) {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.signature-pad');
    }
}
