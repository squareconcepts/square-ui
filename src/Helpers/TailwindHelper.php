<?php

namespace Squareconcepts\SquareUi\Helpers;

/**
 * @deprecated 4.x Wordt verwijderd in 5.0. Tailwind 4 heeft geen tailwind.config.js meer (kleuren staan
 *             als CSS-variabelen in @theme), dus deze helper vindt niets. Gebruik de CSS-variabele direct,
 *             bijv. `var(--color-primary-500)`.
 */
class TailwindHelper
{
    /**
     * @deprecated 4.x Wordt verwijderd in 5.0. Zie de class-docblock.
     */
    public static function tailwindToHex(string $tailwindColor): string
    {
        trigger_error(
            'Squareconcepts\SquareUi\Helpers\TailwindHelper::tailwindToHex() is deprecated and will be removed in square-ui 5.0; '
            . 'Tailwind 4 has no tailwind.config.js. Use the CSS variable (e.g. var(--color-primary-500)) instead.',
            E_USER_DEPRECATED,
        );

        if (!file_exists(app_path('tailwind.config.js'))) {
            return 'Tailwind config file not found';
        }

        $configFile = file_get_contents(app_path('tailwind.config.js'));

        preg_match('/colors: {([^}]+)}/', $configFile, $matches);

        if (!isset($matches[1])) {
            return 'Color section not found in tailwind.config.js';
        }

        preg_match("/'$tailwindColor': '([^']+)'/", $matches[1], $colorMatches);

        return $colorMatches[1] ?? 'Color not found';
    }
}
