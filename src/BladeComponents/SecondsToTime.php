<?php

    namespace Squareconcepts\SquareUi\BladeComponents;

    use Illuminate\Contracts\View\View;
    use Illuminate\View\Component;

    class SecondsToTime extends Component
    {
        public string $time;

        public function __construct(
            public int  $seconds,
            public ?int $compareToSeconds = null,
            public bool $showAsBadge = true
        )
        {
            $hours = intval($this->seconds / 3600);
            $seconds = $this->seconds % 3600;
            $minutes = intval($seconds / 60);
            $seconds = $seconds % 60;

            $this->time = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' .
                str_pad($minutes, 2, '0', STR_PAD_LEFT) . ":" .
                str_pad($seconds, 2, '0', STR_PAD_LEFT);
        }

        public function render(): View
        {
            return view('square-ui::blade-components.seconds-to-time');
        }
    }
