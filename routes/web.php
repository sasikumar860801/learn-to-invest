<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\finance;
use App\Http\Controllers\XirrController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\usersController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//     Route::get('/pl-report', function () {
//     return view('pl-report');
// })->middleware(['auth', 'verified'])->name('pl.report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     Route::get('/users/pl-report', [usersController::class, 'pl_report'])->name('pl.report');
    Route::get('/users/dashboard', [usersController::class, 'dashboard'])->name('dashboard');
    Route::post('/users/buy-stock', [usersController::class, 'buystock'])->name('buystock');
        Route::post('/users/exit-stock', [usersController::class, 'exitstock'])->name('users_exitstock');


});

Route::middleware(['auth', 'role:admin'])->get('/admin/check', function () {
    return response()->json(['success' => true]);
});




Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home',[finance::class,'index'])->name('index');
    Route::get('/cmp/{id}', [finance::class, 'cmp'])->name('cmp');
    Route::post('/exit-stock', [finance::class, 'exitStock'])->name('exitstock');
    Route::post('/home', [finance::class, 'store'])->name('store');
    Route::get('/get-market-cap', [ScraperController::class, 'getMarketCap']);
    Route::get('/filterByMarketCap', [finance::class, 'filterByMarketCap'])->name('filterByMarketCap');
    Route::get('/pl_report', [finance::class, 'pl_report'])->name('pl_report');
    Route::get('/admin/user_list', [finance::class, 'user_list'])->name('user_list');
    Route::get('/admin/user_list_detail/{id}', [finance::class, 'user_list_detail'])->name('user_list_detail');
     Route::get('/admin/recent_buy', [finance::class, 'recent_buy'])->name('recent_buy');
      Route::get('/admin/recent_sell', [finance::class, 'recent_sell'])->name('recent_sell');
});
    Route::get('/search', [finance::class, 'search'])->name('search');
        Route::get('/fetch-chart/{id}', [finance::class, 'fetchChart'])->name('fetch.chart');


Route::middleware(['auth', 'role:admin'])->get('/download-db', function () {
    $filePath = 'exports/learn-to-invest&table.sql';

    if (!Storage::exists($filePath)) {
        abort(404, 'File not found.');
    }

    return Storage::download($filePath, 'learn-to-invest&table.sql');
})->name('download.db');

require __DIR__.'/auth.php';
