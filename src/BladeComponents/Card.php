<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

/**
 * @deprecated 4.x Wordt verwijderd in 5.0. Gebruik <flux:card> met <flux:heading>. Zie UPGRADE.md, "Deprecated in 4.x".
 */
class Card extends Component
{
    public function __construct(
        // Optioneel: met een <x-slot:header_slot> is een titel niet nodig.
        public string $title = '',
        public $header_slot = null,
    ) {}

    public function render()
    {
        return view('square-ui::blade-components.card');
    }
}
