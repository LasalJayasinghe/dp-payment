<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\ReportController;
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
    // Route::get('/requests/history', [RequestController::class, 'getHistoryRequests'])->name('request.history');

    Route::get('/allRequests', [RequestController::class, 'getAllUserRequests'])->name('request.userRequests');
    Route::match(['get', 'post'], '/request/create', [RequestController::class, 'createRequest'])->name('request.create');
    Route::match(['get', 'post'], '/request/update/{id}', [RequestController::class, 'settledRequest'])->name('request.settle.update');
    Route::match(['get', 'post'], '/requests/uploadFiles', [RequestController::class, 'uploadFiles'])->name('request.uploadFiles');
    Route::post('/request/delete-file', [RequestController::class, 'deleteFile'])->name('request.deleteFile');

    Route::get('/requests/supplier/{id}', [RequestController::class, 'getAccountsForSupplier'])->name('request.supplierAccounts');
    Route::get('/requests/account/{id}', [RequestController::class, 'getAccountDetails'])->name('request.accountDetails');
    Route::get('/requests/{id}/info', [RequestController::class, 'getRequestDeatails'])->name('request.details');
    Route::post('/requests/{id}/update', [RequestController::class, 'updateRequest'])->name('request.update');
    Route::post('/requests/update-status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');

    Route::get('/files/{requestId}', [RequestController::class, 'getFiles'])->name('documents');
    Route::get('/categories', [CategoryController::class, 'getCategories'])->name('categories.get');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');


    Route::get('requests/chat-status/{id}', [RequestController::class, 'getChatStatus'])->name('requests.chatStatus');
    Route::post('requests/enable-chat/{id}', [RequestController::class, 'enableChat'])->name('requests.enableChat');
    Route::post('requests/send-chat-message/{id}', [RequestController::class, 'sendChatMessage'])->name('requests.sendChatMessage');
    Route::get('/chat/{request_id}', [RequestController::class, 'getMessages'])->name('chat.getMessages');

    Route::get('/requests/index', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/hsitory', [RequestController::class, 'history'])->name('requests.history');


    Route::match(['get', 'post'], '/category/create', [CategoryController::class, 'createCategory'])->name('category.create');
    Route::get('/category/show', [CategoryController::class, 'showCategory'])->name('category.show');

    Route::match(['get', 'post'], '/supplier/create', [SuppliersController::class, 'createSupplier'])->name('supplier.create');
    Route::match(['get', 'post'], '/supplier/account', [SuppliersController::class, 'addAccount'])->name('supplier.account');
    Route::get('/supplier/show', [SuppliersController::class, 'showSuppliers'])->name('supplier.show');
    Route::delete('/supplier/{id}', [SuppliersController::class, 'destroy'])->name('supplier.destroy');
    Route::get('/supplier/{supplierId}/accounts', [SuppliersController::class, 'getAccounts']);
    Route::get('/suppliers/list', [SuppliersController::class, 'getSuppliers'])->name('suppliers.list');

    Route::get('/transaction', [ReportController::class, 'getTransactionReport'])->name('transaction.report');
    Route::get('/transaction/export', [ReportController::class, 'exportTransactionReport'])->name('transaction.report.export');
    Route::get('/supplier/report', [ReportController::class, 'getSupplierReport'])->name('supplier.report');
    Route::get('/supplier/report/export', [ReportController::class, 'exportReport'])->name('supplier.report.export');

    Route::get('/payment-request/pdf', [PaymentRequestController::class, 'generatePdf'])->name('payment-request.pdf');
    Route::post('/request/approve', [RequestController::class, 'approveRequest'])->name('request.approve');
    Route::get('/chart-data', [ChartController::class, 'getChartData'])->name('chart.data');

    Route::get('cash-accounts', [\App\Http\Controllers\CashAccountController::class, 'index'])
        ->name('cash-accounts');
    Route::post('create-cash-account', [\App\Http\Controllers\CashAccountController::class, 'create'])
        ->name('create-cash-account');
    Route::patch('cash-account/{id}/{status}/status-change', [\App\Http\Controllers\CashAccountController::class, 'accountStatusToggle'])
        ->name('cash-account-status-change');
    Route::patch('cash-account-funds-transfer', [\App\Http\Controllers\CashAccountController::class, 'credit'])
        ->name('cash-account-funds-transfer');
    Route::get('cash-account-detail/{id}', [\App\Http\Controllers\CashAccountController::class, 'detail'])
        ->name('cash-account-detail');
    Route::delete('cash-account-remove/{id}', [\App\Http\Controllers\CashAccountController::class, 'remove'])
        ->name('cash-account-remove');
});
