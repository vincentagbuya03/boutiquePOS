<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReturnAndRefundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingsController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('products/{product}/inventory/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::patch('products/{product}/inventory/update', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('products/{product}/inventory/show', [InventoryController::class, 'show'])->name('inventory.show');
    
    Route::resource('sales', SalesController::class, ['except' => ['edit', 'update', 'destroy']]);
    Route::get('sales-report', [SalesController::class, 'report'])->name('sales.report');
    
    Route::post('returns', [ReturnAndRefundController::class, 'store'])->name('returns.store');
    Route::get('returns/create', [ReturnAndRefundController::class, 'create'])->name('returns.create');
    Route::get('returns', [ReturnAndRefundController::class, 'index'])->name('returns.index');
    Route::get('returns/{return}', [ReturnAndRefundController::class, 'show'])->name('returns.show');
    Route::patch('returns/{return}/approve', [ReturnAndRefundController::class, 'approve'])->name('returns.approve');
    Route::patch('returns/{return}/reject', [ReturnAndRefundController::class, 'reject'])->name('returns.reject');
    Route::get('returns-report', [ReturnAndRefundController::class, 'report'])->name('returns.report');
    
    Route::resource('suppliers', SupplierController::class);
    Route::resource('batches', ProductBatchController::class);
    
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/profit', [ReportController::class, 'profitAnalysis'])->name('reports.profit');
    
    Route::resource('users', UserController::class);
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');
});
