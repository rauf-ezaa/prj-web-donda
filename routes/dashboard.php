<?php


use App\Http\Controllers\DashboardAssetAdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:staf')->get('/staff', [DashboardController::class, 'dashboardStaff'])->name('dashboard.pengguna');
    Route::middleware('role:admin')->get('/admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.staf');
    Route::middleware('role:spv')->get('/spv', [DashboardController::class, 'index'])->name('dashboard.spv');
});
