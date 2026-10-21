<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Tu dashboard principal (Solo estadísticas)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Nueva ruta para registrar y ver movimientos
    Route::get('/movimientos', function () {
        return view('transactions');
    })->name('transactions');

    // Nueva ruta para gestionar cuentas/billeteras
    Route::get('/cuentas', function () {
        return view('wallets');
    })->name('wallets');

    // Nueva ruta para gestionar las categorías
    Route::get('/categorias', function () {
        return view('categories');
    })->name('categories');

});
