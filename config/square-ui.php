<?php

return [
    'upload_disk' => env('SQUARE_UI_UPLOAD_DISK', 'square-ui'),
    'fontawesome_api_token' => env('SQUARE_UI_FONTAWESOME_API_TOKEN', ''),
    'password_strength_checker' => [
        'good' => 70,
        'strong' => 80,
        'very_strong' => 90,
    ],
    'languages' => [
        'nl' => 'nl',
        'en' => 'gb',
        'fr' => 'fr',
        'de' => 'de',
    ],
    'chat_gpt_api_token' => env('CHAT_GPT_API_TOKEN'),
    'chat_gpt_base_url' => env('CHAT_GPT_API_URL', 'https://api.openai.com/v1/'),
    'chat_gpt_model' => env('CHAT_GPT_MODEL', 'gpt-3.5-turbo'),
    'ck_editor_license' => env('CKEDITOR_LICENSE', 'GPL'),
];
