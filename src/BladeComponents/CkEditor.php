<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class CkEditor extends Component
{
    public function __construct(
        public string $identifier,
        public string $model,
        public string $value,
        public string $componentId = '',
        public bool $useChatGpt = false,
        public int $debounceTime = 500,
    ) {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.ck-editor');
    }
}
