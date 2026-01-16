<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/{tenant:slug}/menu', [MenuController::class, 'index'])->name('client.menu');
Route::get('/menu/{tenant:slug}/{qrTable}', [MenuController::class, 'table'])->name('client.menu');
Route::post('/menu/{tenant:slug}/{qrTable}/order', [MenuController::class, 'storeOrder'])->name('client.order.store');



require __DIR__.'/auth.php';
