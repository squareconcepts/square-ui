<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class FileUpload extends Component
{
    public function __construct(
        public string $placeholder = 'Select a file',
        public bool $multiple = false,
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.file-upload');
    }
}

