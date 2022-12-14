<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/requests', [\App\Http\Controllers\TicketController::class, 'index'])->name('requests.index')->middleware(['auth']);
Route::post('/requests', [\App\Http\Controllers\TicketController::class, 'store'])->name('requests.store');

Route::post('/req', function () {
    return \App\Models\Ticket::find(55);
})->middleware('auth:sanctum');

Route::put('/requests/{ticket}', [\App\Http\Controllers\TicketController::class, 'resolve'])
    ->middleware('auth:sanctum')
    ->name('requests.resolve');

require __DIR__.'/auth.php';
