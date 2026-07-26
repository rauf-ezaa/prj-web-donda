<?php


use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
   Route::middleware(['auth', 'role.redirect:staf'])->get('staff', [DashboardController::class, 'staff'])->name('dashboard.pengguna');
Route::middleware(['auth', 'role.redirect:admin'])->get('admin', [DashboardController::class, 'admin'])->name('dashboard.staf');
Route::middleware(['auth', 'role.redirect:spv'])->get('spv', [DashboardController::class, 'spv'])->name('dashboard.spv');});
