<?php

use App\Http\Controllers\QuranController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('app');
});


Route::post('/api/quran/extract-emotion', [QuranController::class, 'extractEmotion']);
Route::post('/api/quran/verses', [QuranController::class, 'getVerses']);