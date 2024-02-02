<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

class Dropzone extends Component
{

    public function __construct(
        public string $identifier,
        public string $model,
        public string $dropzoneText = 'Sleep bestanden hier naartoe of klik om te bladeren',
        public bool $multiple = false,
        public bool $showPreview = true,
    )
    {
    }

    public function render()
    {
        return view('square-ui::blade-components.dropzone');
    }
}
