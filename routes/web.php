<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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



// Apply role middleware to supervisor routes
Route::middleware('rolemanager:supervisor')->group(function () {
    
    Route::get('/inventory', function () {
        return view('magazinier.inventory'); 
    })->name('supervisor.inventory');

    Route::get('/inventory', [ProductController::class, 'inventory'])->name('supervisor.inventory');

    
    Route::get('/add-cashier', function () {
        return view('supervisor.add-cashier');
    })->name('add.cashier');
    
    Route::get('/add-magazinier', function () {
        return view('supervisor.add-magazinier');
    })->name('add.magazinier');
    
  
    
    Route::get('/analytics', function () {
        return view('analytics');
    })->name('analytics');
    
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    // Other supervisor routes...
});

// Apply role middleware to magazinier routes
Route::middleware('rolemanager:magazinier')->group(function () {
   
    Route::get('/magazinier/dashboard', function () {
        return view('magazinier.dashboard'); 
    })->name('magazinier.dashboard');
    
    Route::get('/inventory', function () {
        return view('magazinier.inventory'); 
    })->name('magazinier.inventory');
    Route::view('/add-product', 'magazinier.addProduct')->name('addProduct');
    Route::view('/receive-order', 'magazinier.receiveOrder')->name('receiveOrder');
    
    Route::get('/products/create', [ProductController::class, 'create'])->name('addProduct');
    Route::post('/products/store', [ProductController::class, 'store'])->name('storeProduct');
    
    
    //fetching product information
    Route::get('/inventory', [ProductController::class, 'inventory'])->name('magazinier.inventory');
    
    Route::get('/magazinier/dashboard', [ProductController::class, 'index'])->name('magazinier.dashboard');
    
    
    //product deleate/update
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    // Other magazinier routes...
});







// Apply role middleware to cashier routes
Route::middleware('rolemanager:cashier')->group(function () {
  
//cashier routes


Route::get('/newsale', function () {
    return view('cashier.newsale'); // Replace 'newsale' with the actual Blade view name
})->name('newsale');


Route::get('/MakeAnOrder', function () {
    return view('cashier.demand2'); 
})->name('MakeAnOrder');
    // Other cashier routes...
});






require __DIR__.'/auth.php';
