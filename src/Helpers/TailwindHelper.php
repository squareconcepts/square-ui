<?php

namespace Squareconcepts\SquareUi\Helpers;

class TailwindHelper
{
    public static function tailwindToHex(string $tailwindColor): string
    {
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
