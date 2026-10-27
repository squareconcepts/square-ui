<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 * <x-square-ui::select-dropdown>
 *
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik (Flux Pro) <flux:select variant="combobox" searchable>, <flux:autocomplete> of <flux:pillbox> (meerdere waarden).
 *             Zie UPGRADE.md, "Deprecated in 4.x".
 */
class SelectDropdown extends Component
{
    public function __construct(
        public string $optionLabel,
        public string $optionValue = 'id',
        public array|Collection $items = [],
        public ?string $asyncRoute = null,
        public bool $multiple = false,
        public string $optionDescription = '',
        public ?string $classes = '',
    ) {}

    public function render(): View
    {
        return view('square-ui::blade-components.select-dropdown');
    }
}
