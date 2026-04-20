<?php

namespace Squareconcepts\SquareUi\Helpers;

use Livewire\Wireable;

class FontAwesomeIcon implements Wireable
{
    private string $icon = '';
    private string $label = '';
    private array $styles = [];
    private array $stylesHtml = [];
    private array $styleValues = [];

    public function __construct() {}

    public static function make(string $id, string $label, array $styles): static
    {
        $instance = new static();
        $instance->icon = $id;
        $instance->label = $label;
        $instance->styles = $styles;
        $instance->setStylesData();

        return $instance;
    }

    public function toLivewire(): array
    {
        return [
            'icon' => $this->icon,
            'label' => $this->label,
            'styles' => $this->styles,
            'stylesHtml' => $this->stylesHtml,
            'styleValues' => $this->styleValues,
        ];
    }

    public static function fromLivewire($value): static
    {
        return self::make(
            $value['icon'] ?? '',
            $value['label'] ?? '',
            $value['styles'] ?? [],
        );
    }

    private function setStylesData(): void
    {
        $this->stylesHtml = [];
        $this->styleValues = [];

        foreach ($this->styles as $style) {
            $this->stylesHtml[$style] = '<i class="fa-' . $style . ' fa-' . $this->icon . '"></i>';
            $this->styleValues[$style] = 'fa-' . $style . ' fa-' . $this->icon;
        }
    }
}
