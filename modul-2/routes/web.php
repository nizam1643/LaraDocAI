<?php

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\PelajarController;

Route::get('/pelajar/index', [PelajarController::class, 'index']);
Route::get('/pelajar/list', [PelajarController::class, 'list']);
Route::post('/pelajar', [PelajarController::class, 'store']);
Route::get('/pelajar/{id}', [PelajarController::class, 'show']);
Route::put('/pelajar/{id}', [PelajarController::class, 'update']);
Route::delete('/pelajar/{id}', [PelajarController::class, 'destroy']);
