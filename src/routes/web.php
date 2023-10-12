<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::post('upload', function () {
    $file = request()->file('upload');
    $path = $file->storeAs('images', $file->getClientOriginalName(), config('square-ui.upload_disk'));
    return response()->json(['url' => Storage::disk(config('square-ui.upload_disk'))->url($path)]);
})->name('file-upload');
