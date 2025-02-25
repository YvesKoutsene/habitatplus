<?php

use App\Http\Controllers\Estate\AnnouncementController;
use App\Http\Controllers\Estate\CategoryBienController;
use App\Http\Controllers\Estate\CategoryTicketController;
use App\Http\Controllers\Estate\TicketController;
use App\Http\Controllers\Estate\ModelSubscriptionController;
use App\Http\Controllers\Estate\ParameterCategoryController;
use App\Http\Controllers\Estate\ParameterModelController;
use App\Http\Controllers\Estate\RoleController;
use App\Http\Controllers\Estate\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Estate\ReportingController;

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('habitat+/email/v1.0/2025/verification-success', function () {
    return view('emails.verification-success');
})->name('verification.success');

#Pour super admin et admin
Route::middleware(['auth', 'checkUserType:0,1','check.email.verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('roles', RoleController::class)->middleware('auth');
    Route::patch('roles/{role}/suspend', [RoleController::class, 'suspend'])->name('roles.suspend');
    Route::patch('roles/{role}/reactivate', [RoleController::class, 'reactivate'])->name('roles.reactivate');

    Route::patch('users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::patch('users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');
    Route::resource('users', UserController::class);

    Route::resource('parameter_category', ParameterCategoryController::class);

    Route::resource('category_bien', CategoryBienController::class);
    Route::patch('category_bien/{categorieBien}/suspend', [CategoryBienController::class, 'suspend'])->name('category_bien.suspend');
    Route::patch('category_bien/{categorieBien}/reactivate', [CategoryBienController::class, 'reactivate'])->name('category_bien.reactivate');

    Route::resource('parameter_model', ParameterModelController::class);

    Route::resource('model_subscription', ModelSubscriptionController::class);

    Route::resource('category_ticket', CategoryTicketController::class);
    Route::patch('category_ticket/{categorieTicket}/suspend', [CategoryTicketController::class, 'suspend'])->name('category_ticket.suspend');
    Route::patch('category_ticket/{categorieTicket}/reactivate', [CategoryTicketController::class, 'reactivate'])->name('category_ticket.reactivate');

    Route::get('/ticket/list', [TicketController::class, 'index'])->name('tckt.index');
    Route::get('/ticket/list/closed', [TicketController::class, 'index02'])->name('tckt.index02');
    Route::put('/ticket/{ticket}/closed', [TicketController::class, 'close'])->name('tckt.close');

    Route::get('announcement/list', [AnnouncementController::class, 'index'])->name('announcement.list');
    Route::get('announcement/list/block', [AnnouncementController::class, 'index02'])->name('announcement.list02');
    Route::get('announcement/admin/{bien}/details', [AnnouncementController::class, 'details'])->name('announcement.details');

    Route::get('announcement/admin/reporting', [ReportingController::class, 'index'])->name('report.index');
    Route::get('announcement/admin/report/{bien}', [ReportingController::class, 'show'])->name('report.show');
    Route::put('announcement/admin/{bien}/block', [ReportingController::class, 'block'])->name('announcement.block');
    Route::put('announcement/admin/{bien}/reactivate', [ReportingController::class, 'reactivate'])->name('announcement.reactivate');

});

#Pour super admin et abonné
Route::middleware(['auth', 'checkUserType:0,2','check.email.verified'])->group(function () {
    Route::resource('announcement', AnnouncementController::class);
    Route::put('announcement/{bien}/terminate', [AnnouncementController::class, 'terminate'])->name('announcement.terminate');
    Route::put('announcement/{bien}/relaunch', [AnnouncementController::class, 'relaunch'])->name('announcement.relaunch');

});

#Pour super admin, admin et adonné
Route::middleware(['auth','checkUserType:0,1,2','check.email.verified'])->group(function () {
    Route::put('/profile/profile/update/{id}', [ProfileController::class, 'update'])->name('update.profile');
    Route::put('/profile/password/update/{id}', [ProfileController::class, 'updatePassword'])->name('update.password');
    //Route::resource('ticket', TicketController::class);

});

#Pour abonné
Route::middleware(['auth', 'checkUserType:2','check.email.verified'])->group(function () {
    Route::post('/announcement/{bien}/reporting', [ReportingController::class, 'report'])->name('announcement.report');
    Route::get('/ticket', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');

});


#Pour les sans connecté (visiteurs)
Route::middleware(['check.email.verified'])->group(function () {
    Route::get('/', [HomeController::class, 'indexHome'])->name('acceuil');
    Route::get('announcement/{id}/details', [HomeController::class, 'show'])->name('announcement.show.costumer');

});

require __DIR__.'/auth.php';
