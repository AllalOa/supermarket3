<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/supervisor/dashboard', function () {
    return view('supervisor.dashboard');
})->middleware(['auth', 'verified','rolemanager:supervisor'])->name('supervisor.dashboard');

Route::get('/cashier/dashboard', function () {
    return view('cashier.dashboard');
})->middleware(['auth', 'verified','rolemanager:cashier'])->name('cashier.dashboard');

Route::get('/magazinier/dashboard', function () {
    return view('magazinier.dashboard');
})->middleware(['auth', 'verified','rolemanager:magazinier'])->name('magazinier.dashboard');






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/add-cashier', function () {
    return view('supervisor.add-cashier');
})->name('add.cashier');

Route::get('/add-magazinier', function () {
    return view('supervisor.add-magazinier');
})->name('add.magazinier');

Route::get('/inventory', function () {
    return view('inventory');
})->name('inventory');

Route::get('/analytics', function () {
    return view('analytics');
})->name('analytics');

Route::get('/settings', function () {
    return view('settings');
})->name('settings');



//cashier routes


Route::get('/newsale', function () {
    return view('cashier.newsale'); // Replace 'newsale' with the actual Blade view name
})->name('newsale');


Route::get('/MakeAnOrder', function () {
    return view('cashier.demand2'); 
})->name('MakeAnOrder');



require __DIR__.'/auth.php';
