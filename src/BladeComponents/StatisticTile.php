<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Ramsey\Collection\Collection;

class StatisticTile extends Component
{
    public function __construct(
        public string $title = '',
        public string $text = '',
        public string $icon = 'home',
        public string $iconColor = 'text-primary-500',
        public ?string $shoutOut = null

    ) {
    }

    public function render(): View
    {
        return view('square-ui::blade-components.statistic-tile');
    }
}
