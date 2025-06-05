<?php

use App\Http\Controllers\BookCategoryController;
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
Route::get('/admin', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');
Route::get('/student', [App\Http\Controllers\HomeController::class, 'student'])->name('student');


Route::get('/book-category/index', [BookCategoryController::class, 'index']);
Route::get('/book-category/list', [BookCategoryController::class, 'list']);
Route::post('/book-category', [BookCategoryController::class, 'store']);
Route::get('/book-category/{id}', [BookCategoryController::class, 'show']);
Route::put('/book-category/{id}', [BookCategoryController::class, 'update']);
Route::delete('/book-category/{id}', [BookCategoryController::class, 'destroy']);
