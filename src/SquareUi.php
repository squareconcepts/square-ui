<?php

namespace Squareconcepts\SquareUi;

class SquareUi {

    public static function handleFontawesome(string $api_key): void
    {
        $key= "SQUARE_UI_FONTAWESOME_API_TOKEN";
        self::writeNewEnvironmentFileWith($key, $api_key);
    }

    protected static function writeNewEnvironmentFileWith($key, $value): bool
    {

        $replaced = preg_replace(
            self::keyReplacementPattern($key,env($key)),
            $key . '='.$value,
            $input = file_get_contents(app()->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null) {
            $replaced =  str($replaced)->newLine(2)->append($key . '='.$value)->toString();
        }

        file_put_contents(app()->environmentFilePath(), $replaced);

        return true;
    }

    protected static function keyReplacementPattern($key, $value): string
    {
        $escaped = preg_quote('=' .$value, '/');

        return "/^$key{$escaped}/m";
    }
}