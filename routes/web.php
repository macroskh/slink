<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;

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
Route::group(['middleware' => ['web']], function () {
    Route::get('/', [LinkController::class, 'index'])->name('home');
    Route::post('/', [LinkController::class, 'store'])->name('store');
    Route::get('/{shortcut}', [LinkController::class, 'show'])
        ->where(['shortcut' => '[A-Za-z0-9]{8}'])
        ->name('show');
});
