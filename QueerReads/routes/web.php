<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', function () {
    return view('index');
});
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class)->only([
        'show', 'edit', 'update'
    ]);
});