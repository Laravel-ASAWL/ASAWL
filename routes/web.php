<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\SheetsController::class, 'index'])
    ->where('sheet', '(.*)');

//********************************************//
// Mitigación de autenticación y autorización //
//********************************************//

Auth::routes([
    'login' => true,
    'logout' => true,
    'register' => true,
    'reset' => true,
    'confirm' => true,
    'verify' => true,
]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

//********************************************//
