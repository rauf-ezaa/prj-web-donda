<?php

use App\Http\Controllers\PersedianController;
use Illuminate\Support\Facades\Route;

Route::resource('persediaan', PersedianController::class);
