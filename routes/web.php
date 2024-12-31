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

use App\Http\Middleware\CustomMiddleware;
use App\Http\Middleware\CheckPermission;

app('router')->aliasMiddleware('custom', CustomMiddleware::class);
app('router')->aliasMiddleware('permission', CheckPermission::class);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'permission:accéder à admin panel'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    #New by Jean-Yves

    //Pour profil 
    Route::put('/profile/password/update/{id}', [ProfileController::class, 'updatePassword'])->name('update.password');
    Route::put('/profile/profile/update/{id}', [ProfileController::class, 'update'])->name('update.profile');

    // Gestion des utilisateurs
    Route::middleware('permission:voir utilisateurs')->get('/users', [UserController::class, 'index'])->name('users.index');
    Route::middleware('permission:ajouter utilisateurs')->get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::middleware('permission:ajouter utilisateurs')->post('/users', [UserController::class, 'store'])->name('users.store');
    Route::middleware('permission:voir utilisateurs')->get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::middleware('permission:editer utilisateurs')->get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::middleware('permission:editer utilisateurs')->put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::middleware('permission:supprimer utilisateurs')->delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::middleware('permission:suspendre/réactiver utilisateurs')->patch('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::middleware('permission:suspendre/réactiver utilisateurs')->patch('/users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');

    // Gestion des rôles
    Route::middleware('permission:voir rôles')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::middleware('permission:créer rôles')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::middleware('permission:créer rôles')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::middleware('permission:voir rôles')->get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::middleware('permission:editer rôles')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::middleware('permission:editer rôles')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::middleware('permission:supprimer rôles')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Gestion des paramètres catégories
    Route::middleware('permission:voir paramètres catégories')->get('/parameter_category', [ParameterCategoryController::class, 'index'])->name('parameter_category.index');
    Route::middleware('permission:ajouter paramètres catégories')->get('/parameter_category/create', [ParameterCategoryController::class, 'create'])->name('parameter_category.create');
    Route::middleware('permission:ajouter paramètres catégories')->post('/parameter_category', [ParameterCategoryController::class, 'store'])->name('parameter_category.store');
    Route::middleware('permission:voir paramètres catégories')->get('/parameter_category/{parameterCategory}', [ParameterCategoryController::class, 'show'])->name('parameter_category.show');
    Route::middleware('permission:editer paramètres catégories')->get('/parameter_category/{parameterCategory}/edit', [ParameterCategoryController::class, 'edit'])->name('parameter_category.edit');
    Route::middleware('permission:editer paramètres catégories')->put('/parameter_category/{parameterCategory}', [ParameterCategoryController::class, 'update'])->name('parameter_category.update');
    Route::middleware('permission:supprimer paramètres catégories')->delete('/parameter_category/{parameterCategory}', [ParameterCategoryController::class, 'destroy'])->name('parameter_category.destroy');


    // Gestion des catégories de bien
    Route::middleware('permission:voir catégories')->get('/category_bien', [CategoryBienController::class, 'index'])->name('category_bien.index');
    Route::middleware('permission:créer catégories')->get('/category_bien/create', [CategoryBienController::class, 'create'])->name('category_bien.create');
    Route::middleware('permission:créer catégories')->post('/category_bien', [CategoryBienController::class, 'store'])->name('category_bien.store');
    Route::middleware('permission:voir catégories')->get('/category_bien/{categoryBien}', [CategoryBienController::class, 'show'])->name('category_bien.show');
    Route::middleware('permission:editer catégories')->get('/category_bien/{categoryBien}/edit', [CategoryBienController::class, 'edit'])->name('category_bien.edit');
    Route::middleware('permission:editer catégories')->put('/category_bien/{categoryBien}', [CategoryBienController::class, 'update'])->name('category_bien.update');
    Route::middleware('permission:supprimer catégories')->delete('/category_bien/{categoryBien}', [CategoryBienController::class, 'destroy'])->name('category_bien.destroy');
 
    // Gestion des paramètres modèles
    Route::middleware('permission:voir paramètres modèles d\'abonnements')->get('/parameter_model', [ParameterModelController::class, 'index'])->name('parameter_model.index');
    Route::middleware('permission:ajouter paramètres modèles d\'abonnements')->get('/parameter_model/create', [ParameterModelController::class, 'create'])->name('parameter_model.create');
    Route::middleware('permission:ajouter paramètres modèles d\'abonnements')->post('/parameter_model', [ParameterModelController::class, 'store'])->name('parameter_model.store');
    Route::middleware('permission:voir paramètres modèles d\'abonnements')->get('/parameter_model/{parameterModel}', [ParameterModelController::class, 'show'])->name('parameter_model.show');
    Route::middleware('permission:editer paramètres modèles d\'abonnements')->get('/parameter_model/{parameterModel}/edit', [ParameterModelController::class, 'edit'])->name('parameter_model.edit');
    Route::middleware('permission:editer paramètres modèles d\'abonnements')->put('/parameter_model/{parameterModel}', [ParameterModelController::class, 'update'])->name('parameter_model.update');
    Route::middleware('permission:supprimer paramètres modèles d\'abonnements')->delete('/parameter_model/{parameterModel}', [ParameterModelController::class, 'destroy'])->name('parameter_model.destroy');

    // Gestion des modèles d'abonnements
    Route::middleware('permission:voir modèles d\'abonnements')->get('/model_subscription', [ModelSubscriptionController::class, 'index'])->name('model_subscription.index');
    Route::middleware('permission:créer modèles d\'abonnements')->get('/model_subscription/create', [ModelSubscriptionController::class, 'create'])->name('model_subscription.create');
    Route::middleware('permission:créer modèles d\'abonnements')->post('/model_subscription', [ModelSubscriptionController::class, 'store'])->name('model_subscription.store');
    Route::middleware('permission:voir modèles d\'abonnements')->get('/model_subscription/{modelSubscription}', [ModelSubscriptionController::class, 'show'])->name('model_subscription.show');
    Route::middleware('permission:editer modèles d\'abonnements')->get('/model_subscription/{modelSubscription}/edit', [ModelSubscriptionController::class, 'edit'])->name('model_subscription.edit');
    Route::middleware('permission:editer modèles d\'abonnements')->put('/model_subscription/{modelSubscription}', [ModelSubscriptionController::class, 'update'])->name('model_subscription.update');
    Route::middleware('permission:supprimer modèles d\'abonnements')->delete('/model_subscription/{modelSubscription}', [ModelSubscriptionController::class, 'destroy'])->name('model_subscription.destroy');

     // Gestion des catégories de tickets
    Route::middleware('permission:voir catégories ticket')->get('/category_ticket', [CategoryTicketController::class, 'index'])->name('category_ticket.index');
    Route::middleware('permission:créer catégories ticket')->get('/category_ticket/create', [CategoryTicketController::class, 'create'])->name('category_ticket.create');
    Route::middleware('permission:créer catégories ticket')->post('/category_ticket', [CategoryTicketController::class, 'store'])->name('category_ticket.store');
    Route::middleware('permission:voir catégories ticket')->get('/category_ticket/{categoryTicket}', [CategoryTicketController::class, 'show'])->name('category_ticket.show');
    Route::middleware('permission:editer catégories ticket')->get('/category_ticket/{categoryTicket}/edit', [CategoryTicketController::class, 'edit'])->name('category_ticket.edit');
    Route::middleware('permission:editer catégories ticket')->put('/category_ticket/{categoryTicket}', [CategoryTicketController::class, 'update'])->name('category_ticket.update');
    Route::middleware('permission:supprimer catégories ticket')->delete('/category_ticket/{categoryTicket}', [CategoryTicketController::class, 'destroy'])->name('category_ticket.destroy');

});

require __DIR__.'/auth.php';

//'custom:Administrateur',