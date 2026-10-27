<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\View\Component;

class Flag extends Component
{
    public array $options;

    private static ?array $available = null;

    public function __construct(
        public string $country,
        public bool $hasError = false
    ) {
        if ($this->country == 'en') {
            $this->country = 'gb';
        }

        $path = base_path('public/vendor/squareconcepts/square-ui/flags/' . $this->country . '.svg');
        // The flags directory doesn't change at runtime: glob it once per process, not per flag.
        $this->options = self::$available ??= collect(glob(base_path('public/vendor/squareconcepts/square-ui/flags/*.svg')))->map(function ($path) {
            return basename($path, '.svg');
        })->toArray();
        $this->hasError = !file_exists($path);

    }

    public function render()
    {
        return view('square-ui::blade-components.flag');
    }
}
