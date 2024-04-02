<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class SelectDropdown extends Component
{
    public function __construct(
        public string           $optionLabel,
        public string           $optionValue = 'id',
        public array|Collection $items = [],
        public ?string          $asyncRoute = null,
        public bool             $multiple = false,
        public string           $optionDescription = '',
        public ?string          $classes = '',
    ) {}

    public function render(): View
    {
        return view('square-ui::blade-components.select-dropdown');
    }
}
