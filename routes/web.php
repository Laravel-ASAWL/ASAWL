<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//********************************************//
// Mitigación de autenticación y autorización //
//********************************************//

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function(){
    // ...
})->middleware('auth');

//********************************************//
