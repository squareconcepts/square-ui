<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Squareconcepts\SquareUi\Helpers\ChatGPT;

Route::post('upload', function () {
    $file = request()->file('upload');
    $path = $file->storeAs('images', $file->getClientOriginalName(), config('square-ui.upload_disk'));
    return response()->json(['url' => Storage::disk(config('square-ui.upload_disk'))->url($path)]);
})->name('file-upload');

Route::prefix('/chat')->name('chat-gpt.')->group( callback: function () {
    Route::post('ask', function () {
        return ChatGPT::ask(request()->question);
    })->name('ask');
});
