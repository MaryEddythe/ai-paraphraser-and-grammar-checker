<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentController;

Route::get('/', [ContentController::class, 'showForm']);
Route::match(['get', 'post'], '/paraphrase', [ContentController::class, 'paraphrase']);
