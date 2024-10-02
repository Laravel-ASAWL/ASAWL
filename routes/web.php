<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//********************************************//
// Mitigación de autenticación y autorización //
//********************************************//

Auth::routes();
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth', 'signed']);

//********************************************//
