<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
Route::post('/newsale', [TransactionController::class, 'addProductToBill'])->middleware('auth');


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
    
    Route::get('/supervisor/dashboard', [SupervisorDashboardController::class, 'index'])
    ->name('supervisor.dashboard');

    Route::get('/supervisor/cashiers', [SupervisorDashboardController::class, 'getCashiers'])
    ->name('supervisor.cashiers');

Route::get('/supervisor/magaziniers', [SupervisorDashboardController::class, 'getMagaziniers'])
    ->name('supervisor.magaziniers');
 
    Route::get('/supervisor/low-stock-items', [SupervisorDashboardController::class, 'getLowStockItems'])
    ->name('supervisor.lowStockItems');

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
    Route::get('/supervisor/inventory', [InventoryController::class, 'index'])->name('supervisor.inventory');
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

    Route::get('/magazinier/orders', [OrderController::class, 'showPendingOrders'])->name('magazinier.orders');
    Route::post('/orders/{id}/approve', [OrderController::class, 'validateOrder'])->name('orders.approve');
    Route::post('/orders/{id}/reject', [OrderController::class, 'rejectOrder'])->name('orders.reject');
 
});







// Apply role middleware to cashier routes
Route::middleware('rolemanager:cashier')->group(function () {
  
//cashier routes


Route::get('/newsale', function () {
    return view('cashier.newsale'); // Replace 'newsale' with the actual Blade view name
})->name('newsale');



    // Other cashier routes...
    Route::get('/newsale', [TransactionController::class, 'showSalePage'])->middleware('auth')->name('cashier.sale');
    Route::post('/newsale', [TransactionController::class, 'addProductToBill'])->middleware('auth');
    Route::get('/start-sale', [TransactionController::class, 'startNewSale'])->middleware('auth')->name('start.sale');
    Route::get('/end-sale', [TransactionController::class, 'endTransaction'])->middleware('auth')->name('end.sale');

    
    Route::get('/bill/{billId}/transactions', [TransactionController::class, 'getBillTransactions']);


    Route::get('/cashier/dashboard', [TransactionController::class, 'dashboard'])->name('cashier.dashboard');

    Route::post('/Order', [OrderController::class, 'createOrder'])->name('cashier.MakeAnOrder');
   
    Route::get('/orders', [OrderController::class, 'listOrdersForMagazinier']);
    Route::post('/orders/{order}/update', [OrderController::class, 'updateOrderStatus']);
    Route::get('/cashier/orders', [OrderController::class, 'cashierOrders'])->name('cashier.orders');

    Route::get('/get-product-price/{productId}', [ProductController::class, 'getProductPrice']);
    Route::get('/MakeAnOrder', [ProductController::class, 'showDemandForm'])->name('MakeAnOrder');

});





// test order


Route::get('/get-product-price/{productName}', [ProductController::class, 'getProductPrice']);

require __DIR__.'/auth.php';
