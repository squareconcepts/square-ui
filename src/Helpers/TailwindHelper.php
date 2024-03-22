<?php

    namespace Squareconcepts\SquareUi\Helpers;

    class TailwindHelper
    {

        public static function tailwindToHex( $tailwindColor  )
        {
            if(!file_exists(app_path('tailwind.config.js')))
            {
                return 'Tailwind config file not found';
            }

            // Lees de inhoud van het tailwind.config.js bestand
            $configFile = file_get_contents(app_path('tailwind.config.js'));

            // Zoek naar de sectie waar de kleuren zijn gedefinieerd
            preg_match('/colors: {([^}]+)}/', $configFile, $matches);

            // Als er een overeenkomst is gevonden
            if (isset($matches[1])) {
                // Extraheren van de kleurdefinities
                $colorDefinitions = $matches[1];

                // Zoek naar de gewenste kleurklasse
                preg_match("/'$tailwindColor': '([^']+)'/", $colorDefinitions, $colorMatches);

                // Als de kleurklasse is gevonden
                if (isset($colorMatches[1])) {
                    $hexColor = $colorMatches[1];
                    echo "De hexadecimale kleurwaarde van '$tailwindColor' is: $hexColor";
                } else {
                    echo "Kleur niet gevonden.";
                }
            } else {
                echo "Kleurensectie niet gevonden in tailwind.config.js.";
            }

        }

    }
