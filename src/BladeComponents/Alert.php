<?php

namespace Squareconcepts\SquareUi\BladeComponents;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $icon;
    public string $color;
    public function __construct(public ?string $message = null, public string $type = 'info')
    {
        $this->icon = $this->getIcon();
        $this->color = $this->getTextColor();
    }

    public function render(): View
    {
        return view('square-ui::blade-components.alert');
    }


    public function getIcon(): string
    {
        return match ($this->type) {
            'dark', 'secondary' , 'info' => 'circle-info',
            'danger' => 'hexagon-exclamation',
            'warning' => 'triangle-exclamation',
            default => 'circle-check',
        };
    }

    public function getTextColor(): string
    {
        return match ($this->type) {
            'danger' => 'red',
            'warning' => 'orange',
            'secondary', 'info' => 'blue',
            'dark' => 'black',
            'success' => 'green',
            default => 'green',
        };
    }
}
