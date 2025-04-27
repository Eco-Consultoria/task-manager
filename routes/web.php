<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::resource('tasks', TaskController::class);
   
});

Route::middleware(['auth', 'manager'])->group(function () {

    Route::patch('/tasks/{task}/review', [TaskController::class, 'review'])->name('tasks.review');
    Route::get('/tasks/{task}/review', [TaskController::class, 'showReview'])->name('tasks.showReview');

    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});

Route::post('/notifications/mark-as-read', function () {

    \App\Models\Notification::where('user_id', auth()->id())->delete();

    return response()->json(['success' => true]);
    
})->middleware('auth')->name('notifications.markAsRead');


