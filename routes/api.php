<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

Route::name('api.')->group(function() {
    Route::get('requests', [\App\Http\Controllers\Api\TicketController::class, 'index'])
        ->middleware('auth:sanctum')
        ->name('requests.index');
    Route::post('requests', [\App\Http\Controllers\Api\TicketController::class, 'store'])->name('requests.store');
    Route::put('requests/{ticket}', [\App\Http\Controllers\Api\TicketController::class, 'resolve'])
        ->middleware('auth:sanctum')
        ->name('requests.resolve');
});
