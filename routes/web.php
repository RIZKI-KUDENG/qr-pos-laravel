<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\MenuController;
use App\Livewire\ProductManager;
use App\Livewire\KitchenDisplay;
use App\Livewire\Ordermanager;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/menu/{tenant:slug}/{qrTable}', [MenuController::class, 'table'])->name('client.menu');
Route::post('/menu/{tenant:slug}/{qrTable}/order', [MenuController::class, 'storeOrder'])->name('client.order.store');

Route::get('/order/{tenant:slug}/{orderNumber}', [MenuController::class, 'showStatus'])->name('client.order.status');
Route::post('/order/{tenant:slug}/{orderNumber}/cancel', [MenuController::class, 'cancelOrder'])->name('client.order.cancel');

Route::middleware('auth')->group(function () {
    
    //POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/order', [PosController::class, 'store'])->name('pos.store');
    Route::get('/orders/{orderNumber}/print', [PosController::class, 'printStruk'])
    ->name('pos.print');
    Route::post('/pos/open-shift', [PosController::class, 'openShift'])->name('pos.openShift');
    Route::post('/pos/close-shift', [PosController::class, 'closeShift'])->name('pos.closeShift');

    //Livewire Routes
    Route::get('/products', ProductManager::class)->name('products.index');
    Route::get('/kitchen', KitchenDisplay::class)->name('kitchen.index');
    Route::get('/ordermanager', Ordermanager::class)->name('order.index');
});

require __DIR__.'/auth.php';
