<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Song API CRUD Routes
| Base URL: /api/songs
| Methods: GET, POST, PUT, DELETE
|
*/

Route::prefix('songs')->group(function () {
    Route::get('/', [SongController::class, 'index'])->name('songs.index');
    Route::post('/', [SongController::class, 'store'])->name('songs.store');
    Route::get('{id}', [SongController::class, 'show'])->name('songs.show');
    Route::put('{id}', [SongController::class, 'update'])->name('songs.update');
    Route::delete('{id}', [SongController::class, 'destroy'])->name('songs.destroy');
});
