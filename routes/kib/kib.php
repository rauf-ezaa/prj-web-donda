<?php

use App\Http\Controllers\KIBController;
use Illuminate\Support\Facades\Route;

Route::resource('kartu-inventaris-barang', KIBController::class);