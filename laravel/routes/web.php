<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
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

// Public views
Route::view('/', 'welcome');
Route::get('/consent', [Controller::class, 'showConsent']);
Route::get('/enroll', [Controller::class, 'showForm']);
Route::post('/enroll', [Controller::class, 'submitForm']);
Route::get('/status/{token}', [Controller::class, 'statusPage']);
Route::post('/update_status/{token}', [Controller::class, 'submitUpdateStatus']);

// Public data submission
Route::post('/test_results/{token}', [Controller::class, 'submitResults']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::resource('/categories', \App\Http\Controllers\CategoryController::class);
});

require __DIR__.'/auth.php';
