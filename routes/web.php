<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::controller(ProjectController::class)->group(function () {
        Route::get('projects', 'index')->name('projects');
        Route::post('projects', 'store')->name('projects.create');
        Route::get('projects/{project}', 'show')->name('projects.show');
        Route::patch('projects/{project}', 'update')->name('projects.update');
        Route::delete('projects/{project}', 'destroy')->name('projects.destroy');
    });

    Route::controller(ProfileController::class)->group(function() {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    
});

require __DIR__.'/auth.php';
