<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post('/checkout', [StripeController::class, 'checkout']);

Route::post('/checkout2', [StripeController::class, 'checkout2']);

Route::get('/success', function () {
    return view('success');
})->name('success');
Route::get('/cancel', function () {
    return view('index');
})->name('cancel');