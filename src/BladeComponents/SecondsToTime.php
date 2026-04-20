<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SecondsToTime extends Component
{
    public string $time;

    public function __construct(
        public int $seconds,
        public ?int $compareToSeconds = null,
        public bool $showAsBadge = true,
        public ?string $color = null
    ) {
        $hours = intval($this->seconds / 3600);
        $remainder = $this->seconds % 3600;
        $minutes = intval($remainder / 60);
        $secs = $remainder % 60;

        $this->time = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' .
            str_pad($secs, 2, '0', STR_PAD_LEFT);
    }

    public function render(): View
    {
        return view('square-ui::blade-components.seconds-to-time');
    }
}
