<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class StatisticTile extends Component
{
    public function __construct(
        public string $label = '',
        public string $icon = '',
        public string $iconColor = '#000',
        public ?string $iconBackgroundColor = null,
        public string $valueColor = '#000',
        public int|string $value = 0,
        public bool $isCurrency = false,
    ) {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.statistic-tile');
    }
}
