<?php

return [
    'fontawesome_api_token' => env('SQUARE_UI_FONTAWESOME_API_TOKEN', ''),
    'password_strength_checker' => [
        'good' => 70,
        'strong' => 80,
        'very_strong' => 90,
    ],
    // Valuta voor 'currency'-kolommen in de DataTable (per kolom te overschrijven met 'currency' => 'USD').
    'currency' => env('SQUARE_UI_CURRENCY', 'EUR'),
    'languages' => [
        'nl' => 'nl',
        'en' => 'gb',
        'fr' => 'fr',
        'de' => 'de',
    ],
    'chat_gpt_api_token' => env('CHAT_GPT_API_TOKEN'),
    'chat_gpt_base_url' => env('CHAT_GPT_API_URL', 'https://api.openai.com/v1/'),
    'chat_gpt_model' => env('CHAT_GPT_MODEL', 'gpt-3.5-turbo'),
];
