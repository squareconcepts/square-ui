<?php
namespace Squareconcepts\SquareUi\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;
use Squareconcepts\SquareUi\SquareUi;

class ChatGPT
{

    /**
     * @throws Exception
     */
    public static function ask( string $question  )
    {
        if (empty(config('square-ui.chat_gpt_api_token')) || empty(config('square-ui.chat_gpt_base_url'))) {
            SquareUi::addKeysForChatGpt();
            throw new Exception('Please provide ChatGPT api token adn base url. Set CHAT_GPT_API_TOKEN and CHAT_GPT_API_URL in your .env file');
        }
        $url = config('square-ui.chat_gpt_base_url') . 'chat/completions';
        $client = Http::withToken(config('square-ui.chat_gpt_api_token'));
        $data = [
            'messages' => [
                [ 'role' => 'system', 'content' => 'You are an SEO specialist' ],
                [ 'role' => 'user', 'content' => $question ],
            ],
            "model"=>"gpt-3.5-turbo",
        ];
        $response = $client->post($url, $data);
        if($response->failed()) {
            return $response->reason();
        }
        return $response->json()['choices'][0]['message']['content'];
    }
}
