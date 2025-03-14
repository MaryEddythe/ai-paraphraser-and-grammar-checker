<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

Route::get('/', function () {
    return view('paraphrase', ['active' => 'paraphrase']);
});

Route::get('/grammar', function () {
    return view('grammar', ['active' => 'grammar']);
});

Route::post('/check-grammar', [ContentController::class, 'checkGrammar']);
Route::post('/paraphrase', [ContentController::class, 'paraphrase']);