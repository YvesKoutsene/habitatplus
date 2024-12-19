<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #New by Jean-Yves
    //Pour les rôles
        Route::resource('roles', RoleController::class)->middleware('auth');

    //Pour les users
        Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
        Route::patch('users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');
        Route::resource('users', UserController::class);

        //Route::patch('/profile/password/update/{id}', [ProfileController::class, 'updatePassword'])->name('password.update');

});

require __DIR__.'/auth.php';
