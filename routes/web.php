<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParaphraseController;

Route::get('/', [ParaphraseController::class, 'showForm']);
Route::match(['get', 'post'], '/paraphrase', [ParaphraseController::class, 'paraphrase']);
