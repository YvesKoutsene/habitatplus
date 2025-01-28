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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Abonné\AnnouncementController;

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('habitatplus-email/v1.0/2025/verification-success', function () {
    return view('auth.verification-success');
})->name('verification.success');

#Pour super admin et admin
Route::middleware(['auth', 'checkUserType:0,1','check.email.verified'])->group(function () {
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

#Pour super admin et abonné
Route::middleware(['auth', 'checkUserType:0,2','check.email.verified'])->group(function () {
    //Pour les annonces
    Route::resource('announcement', AnnouncementController::class);
    Route::put('announcement/{bien}/terminate', [AnnouncementController::class, 'terminate'])->name('announcement.terminate');
    Route::put('announcement/{bien}/relaunch', [AnnouncementController::class, 'relaunch'])->name('announcement.relaunch');

});

#Pour super admin, admin et adonné
Route::middleware(['auth','checkUserType:0,1,2','check.email.verified'])->group(function () {
    //Pour profil utilisateur
    Route::put('/profile/profile/update/{id}', [ProfileController::class, 'update'])->name('update.profile');
    Route::put('/profile/password/update/{id}', [ProfileController::class, 'updatePassword'])->name('update.password');

});

#Pour les visiteurs
Route::middleware(['check.email.verified'])->group(function () {
    Route::get('/', [HomeController::class, 'indexHome'])->name('acceuil');
    Route::get('announcement/{id}/details', [HomeController::class, 'show'])->name('announcement.show.costumer');


});

require __DIR__.'/auth.php';
