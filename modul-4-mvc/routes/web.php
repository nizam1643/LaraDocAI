<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/docs', function () {
    $docs = \App\Models\DocumentationEntry::latest()->get();
    return view('docs.index', compact('docs'));
});

// ADMIN ROUTES
Route::resource('rooms', RoomController::class)->middleware('role:admin');

// STUDENT ROUTES
Route::resource('groups', GroupController::class)->middleware('role:student');
Route::post('groups/{group}/add-member', [GroupController::class, 'addMember'])->middleware('role:student')->name('groups.addMember');
Route::delete('groups/{group}/remove-member/{user}', [GroupController::class, 'removeMember'])->middleware('role:student')->name('groups.removeMember');

// ADMIN AND STUDENT ROUTES
Route::resource('bookings', BookingController::class)->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});
