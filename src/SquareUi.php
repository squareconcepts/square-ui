<?php

namespace Squareconcepts\SquareUi;

class SquareUi
{
    public static function handleFontawesome(string $api_key): void
    {
        self::writeNewEnvironmentFileWith('SQUARE_UI_FONTAWESOME_API_TOKEN', $api_key);
    }

    public static function addKeysForChatGpt(): void
    {
        if (empty(env('CHAT_GPT_API_TOKEN')) && empty(env('CHAT_GPT_API_URL'))) {
            $input = str(file_get_contents(app()->environmentFilePath()))
                ->newLine(2)
                ->append('#CHAT GPT
CHAT_GPT_API_TOKEN=
CHAT_GPT_API_URL=');

            file_put_contents(app()->environmentFilePath(), $input);
        }
    }

    protected static function writeNewEnvironmentFileWith(string $key, string $value): void
    {
        $input = file_get_contents(app()->environmentFilePath());

        $replaced = preg_replace(
            "/^{$key}" . preg_quote('=' . env($key), '/') . "/m",
            $key . '=' . $value,
            $input
        );

        if ($replaced === $input || $replaced === null) {
            $replaced = str($input)->newLine(2)->append($key . '=' . $value)->toString();
        }

        file_put_contents(app()->environmentFilePath(), $replaced);
    }
}
