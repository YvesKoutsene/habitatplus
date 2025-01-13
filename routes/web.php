<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ParameterCategoryController;
use App\Http\Controllers\Admin\CategoryBienController;
use App\Http\Controllers\Admin\ParameterModelController;
use App\Http\Controllers\Admin\ModelSubscriptionController;
use App\Http\Controllers\Admin\CategoryTicketController;


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
    Route::patch('roles/{role}/suspend', [RoleController::class, 'suspend'])->name('roles.suspend');
    Route::patch('roles/{role}/reactivate', [RoleController::class, 'reactivate'])->name('roles.reactivate');

    //Pour les users
    Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::patch('users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');
    Route::resource('users', UserController::class);

    //Pour profil d'admin
    Route::put('/profile/password/update/{id}', [ProfileController::class, 'updatePassword'])->name('update.password');
    Route::put('/profile/profile/update/{id}', [ProfileController::class, 'update'])->name('update.profile');

    //Pour les paramètres categories de bien
    Route::resource('parameter_category', ParameterCategoryController::class);

    //Pour les categorie de bien
    Route::resource('category_bien', CategoryBienController::class);
    Route::patch('category_bien/{categorieBien}/suspend', [CategoryBienController::class, 'suspend'])->name('category_bien.suspend');
    Route::patch('category_bien/{categorieBien}/reactivate', [CategoryBienController::class, 'reactivate'])->name('category_bien.reactivate');

    //Pour les paramètres de modèles
    Route::resource('parameter_model', ParameterModelController::class);

    //Pour les modèles d'abonnement
    Route::resource('model_subscription', ModelSubscriptionController::class);

    //Pour les categories de ticket
    Route::resource('category_ticket', CategoryTicketController::class);




});

require __DIR__.'/auth.php';
