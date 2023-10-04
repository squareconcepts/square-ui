<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class SelectDropdown extends Component
{
    public function __construct(
        public string $optionLabel,
        public string $optionValue = 'id',
        public array|Collection|\Illuminate\Database\Eloquent\Collection $items = [],
        public ?string $asyncRoute = null,
        public bool $multiple = false
    )
    {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.select-dropdown');
    }
}
