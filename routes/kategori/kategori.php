<?php

use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

Route::resource('category', KategoriController::class);