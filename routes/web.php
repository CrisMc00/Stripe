<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post('/pagar2', [StripeController::class, 'pagar2']);

Route::post('/pagar1', [StripeController::class, 'pagar1']);

Route::get('/success', function () {
    return view('success');
})->name('success');
Route::get('/cancel', function () {
    return view('index');
});

Route::get('/index2', function () {
    return view('indexx');
})->name('cancel');

Route::get('/crearProductos', [StripeController::class, 'crearProductos']);
Route::post('/pagar', [StripeController::class, 'pago']);

// ============================================
// 🆕 NUEVAS FUNCIONALIDADES
// ============================================

// Reembolsos
Route::get('/refund', [StripeController::class, 'refund']);
Route::get('/refunds', function () {
    return view('refunds');
})->name('refunds');

// Historial de Compras y Reembolsos
Route::get('/historial-compras', [StripeController::class, 'historialCompras'])->name('historial.compras');
Route::post('/refund-purchase', [StripeController::class, 'refundPurchase']);

// Suscripciones
Route::post('/suscripcion', [StripeController::class, 'crearSuscripcion']);
Route::post('/suscripcion-premium', [StripeController::class, 'suscripcionPremium']);
Route::get('/crearPreciosRecurrentes', [StripeController::class, 'crearPreciosRecurrentes']);

// Consultas
Route::get('/session', [StripeController::class, 'obtenerSesion']);

// Webhook (sin CSRF)
Route::post('/stripe/webhook', [StripeController::class, 'webhook']);