<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
