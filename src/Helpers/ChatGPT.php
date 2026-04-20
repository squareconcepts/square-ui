<?php

namespace Squareconcepts\SquareUi\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;
use Squareconcepts\SquareUi\SquareUi;

class ChatGPT
{
    public static function ask(string $question): mixed
    {
        if (empty(config('square-ui.chat_gpt_api_token')) || empty(config('square-ui.chat_gpt_base_url'))) {
            SquareUi::addKeysForChatGpt();
            throw new Exception('Please provide ChatGPT api token and base url. Set CHAT_GPT_API_TOKEN and CHAT_GPT_API_URL in your .env file');
        }

        $response = Http::withToken(config('square-ui.chat_gpt_api_token'))
            ->post(config('square-ui.chat_gpt_base_url') . 'chat/completions', [
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an SEO specialist'],
                    ['role' => 'user', 'content' => $question],
                ],
                'model' => 'gpt-3.5-turbo',
            ]);

        if ($response->failed()) {
            return $response->reason();
        }

        return $response->json()['choices'][0]['message']['content'];
    }
}
