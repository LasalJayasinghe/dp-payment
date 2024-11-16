<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SuppliersController;

Route::match(['get', 'post'], '/', [AuthController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('auth.signup');

Route::middleware('auth')->group(function () {
    
    Route::match(['get', 'post'], '/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::post('/upload-signature', [AuthController::class, 'uploadSignature'])->name('auth.profile.signature');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('auth.password');
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/requests', [RequestController::class, 'index'])->name('request.show');
    Route::match(['get', 'post'], '/requests/create', [RequestController::class, 'createRequest'])->name('request.create');
    Route::get('/requests/supplier/{id}', [RequestController::class, 'getAccountsForSupplier'])->name('request.supplierAccounts');
    Route::get('/requests/account/{id}', [RequestController::class, 'getAccountDetails'])->name('request.accountDetails');
    Route::get('/requests/{id}/info', [RequestController::class, 'getRequestDeatails'])->name('request.details');
    Route::post('/requests/{id}/update', [RequestController::class, 'updateRequest'])->name('request.update');
    Route::post('/requests/update-status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');

    Route::get('requests/chat-status/{id}', [RequestController::class, 'getChatStatus'])->name('requests.chatStatus');
    Route::post('requests/enable-chat/{id}', [RequestController::class, 'enableChat'])->name('requests.enableChat');
    Route::post('requests/send-chat-message/{id}', [RequestController::class, 'sendChatMessage'])->name('requests.sendChatMessage');
    Route::get('/chat/{request_id}', [RequestController::class, 'getMessages'])->name('chat.getMessages');

    Route::get('/requests/pending-check', [RequestController::class, 'index'])->name('requests.pending-check');
    Route::get('/requests/pending-approval', [RequestController::class, 'index'])->name('requests.pending-approval');
    Route::get('/requests/waiting-signature', [RequestController::class, 'index'])->name('requests.waiting-signature');
    Route::get('/requests/approved', [RequestController::class, 'index'])->name('requests.approved');
    Route::get('/requests/rejected', [RequestController::class, 'index'])->name('requests.rejected');
    
    Route::match(['get', 'post'], '/category/create', [CategoryController::class, 'createCategory'])->name('category.create');
    Route::get('/category/show', [CategoryController::class, 'showCategory'])->name('category.show');
    
    Route::match(['get', 'post'], '/supplier/create', [SuppliersController::class, 'createSupplier'])->name('supplier.create');
    Route::match(['get', 'post'], '/supplier/account', [SuppliersController::class, 'addAccount'])->name('supplier.account');
    Route::get('/supplier/show', [SuppliersController::class, 'showSuppliers'])->name('supplier.show');
});
