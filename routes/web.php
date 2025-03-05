<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('auth/redirect', [LoginController::class, 'redirectToProvider']);
Route::get('auth/callback', [LoginController::class, 'handleProviderCallback']);

// route::post('staff/logout',Filament\Http\Controllers\Auth\LogoutController::class)
// Route::post('staff/logout', function () {
//     // Redirect to home page
//     return redirect('/');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/about', function () {
        return view('dashboard');
    })->name('about');
});
