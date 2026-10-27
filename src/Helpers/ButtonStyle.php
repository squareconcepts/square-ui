<?php

namespace Squareconcepts\SquareUi\Helpers;

/**
 * Flux 2 button style for a colour or (older) variant name passed by callers.
 *
 * Older code passed variant names like 'positive', 'warning' or 'info'; those don't exist
 * in Flux 2, which colours the primary variant instead: variant="primary" color="green".
 */
class ButtonStyle
{
    /**
     * @return array{variant: string, color: ?string}
     */
    public static function from(?string $value): array
    {
        return match ($value) {
            null, '', 'primary' => ['variant' => 'primary', 'color' => null],
            'danger', 'negative' => ['variant' => 'danger', 'color' => null],
            'outline', 'ghost', 'subtle', 'filled' => ['variant' => $value, 'color' => null],
            'positive', 'success' => ['variant' => 'primary', 'color' => 'green'],
            'warning' => ['variant' => 'primary', 'color' => 'amber'],
            'info', 'secondary' => ['variant' => 'primary', 'color' => 'blue'],
            default => ['variant' => 'primary', 'color' => $value],
        };
    }
}
