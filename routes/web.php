<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/export/rating/{id}', [ExportController::class, 'rating'])
    ->middleware('auth')
    ->name('export.rating');

Route::get('/export/chrono/{id}', [ExportController::class, 'chrono'])
    ->middleware('auth')
    ->name('export.chrono');
