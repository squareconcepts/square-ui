<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatisticTile extends Component
{
    public function __construct(
        public string $title = '',
        public string $text = '',
        public string $icon = 'home',
        public string $iconColor = 'text-primary-500',
        public ?string $shoutOut = null,
        // Tekstkleur van titel, tekst en shout-out (stond al in de view, maar was geen prop en werkte dus niet).
        public string $textColor = 'text-primary-500',

    ) {}

    public function render(): View
    {
        return view('square-ui::blade-components.statistic-tile');
    }
}
