<?php

return [
    'fontawesome_api_token' => env('SQUARE_UI_FONTAWESOME_API_TOKEN'),
    'password_strength_checker' => [
        //what score between 0 and 100
        'good' => 70, //and above is
        'strong' => 80,//and above is
        'very_strong' => 90,//and above is
    ],
    'livewire_version' => 3
];
