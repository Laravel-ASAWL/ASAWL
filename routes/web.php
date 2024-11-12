<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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


Route::get('/{page?}', function (string $page) {
    $page = 'docs.'.$page;
    if (View::exists($page)) {
        return view('docs', ['page' => $page]);
    }

    $fallback = preg_replace('/^(inertia|blade|livewire)\//', '', $page);

    abort_unless(View::exists($fallback), 404);

    return view('docs', ['page' => $fallback]);
})->where('page', '[a-z-\/]+');
