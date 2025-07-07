<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\finance;
use App\Http\Controllers\XirrController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\ScraperController;

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

Route::middleware(['auth', 'role:admin'])->get('/admin/check', function () {
    return response()->json(['success' => true]);
});





Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home',[finance::class,'index'])->name('index');
    Route::get('/search', [finance::class, 'search'])->name('search');
    Route::get('/fetch-chart/{id}', [finance::class, 'fetchChart'])->name('fetch.chart');
    Route::get('/cmp/{id}', [finance::class, 'cmp'])->name('cmp');
    Route::post('/exit-stock', [finance::class, 'exitStock'])->name('exitstock');
    Route::post('/home', [finance::class, 'store'])->name('store');
    Route::get('/get-market-cap', [ScraperController::class, 'getMarketCap']);
    Route::get('/filterByMarketCap', [finance::class, 'filterByMarketCap'])->name('filterByMarketCap');
    Route::get('/pl_report', [finance::class, 'pl_report'])->name('pl_report');
});


require __DIR__.'/auth.php';
