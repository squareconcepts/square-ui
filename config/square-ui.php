<?php

return [
    'upload_disk' => env('SQUARE_UI_UPLOAD_DISK','square-ui'),
    'fontawesome_api_token' => env('SQUARE_UI_FONTAWESOME_API_TOKEN', ''),
    'password_strength_checker' => [
        //what score between 0 and 100
        'good' => 70, //and above is
        'strong' => 80,//and above is
        'very_strong' => 90,//and above is
    ],
    'livewire_version' => 3,
    'languages' => [
        'nl' => 'nl',
        'en' => 'gb',
        'fr' => 'fr',
        'de' => 'de'
    ],
    'chat_gpt_api_token' =>env('CHAT_GPT_API_TOKEN'),
    'chat_gpt_base_url' =>env('CHAT_GPT_API_URL', 'https://api.openai.com/v1/'),
];
