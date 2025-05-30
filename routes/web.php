<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FoyerController;
use App\Models\Product;
use App\Models\Order;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupervisorNotificationController;
use App\Http\Controllers\MagazinierNotificationController;
use App\Http\Controllers\CashierNotificationController;

Route::post('/newsale', [TransactionController::class, 'addProductToBill'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Supervisor Routes
Route::middleware(['auth', 'rolemanager:supervisor'])->group(function () {
    // Dashboard
    Route::get('/supervisor/dashboard', [SupervisorDashboardController::class, 'index'])->name('supervisor.dashboard');
    
    // User Management
    Route::get('/supervisor/cashiers', [SupervisorDashboardController::class, 'getCashiers'])->name('supervisor.cashiers');
    Route::get('/supervisor/magaziniers', [SupervisorDashboardController::class, 'getMagaziniers'])->name('supervisor.magaziniers');
    Route::post('/supervisor/promote-user', [SupervisorDashboardController::class, 'promoteUser'])->name('supervisor.promoteUser');
    Route::post('/supervisor/demote-user', [SupervisorDashboardController::class, 'demoteUser'])->name('supervisor.demoteUser');
    Route::get('/supervisor/promoted-users', [SupervisorDashboardController::class, 'getPromotedUsers'])->name('supervisor.promotedUserss');
    
    // Suspension Management
    Route::get('/supervisor/suspended-users', [SupervisorDashboardController::class, 'suspendedUsers'])->name('supervisor.suspendedUsers');
    Route::post('/supervisor/suspend', [SupervisorDashboardController::class, 'suspendUser'])->name('supervisor.suspendUser');
    Route::post('/supervisor/reinstate-user', [SupervisorDashboardController::class, 'reinstateUser'])->name('supervisor.reinstateUser');
    
    // Inventory Management
    Route::get('/supervisor/inventory', [InventoryController::class, 'index'])->name('supervisor.inventory');
    Route::get('/supervisor/low-stock-items', [SupervisorDashboardController::class, 'getLowStockItems'])->name('supervisor.lowStockItems');
    
    // Analytics
    Route::get('/supervisor/analytics', [AnalyticsController::class, 'analytics'])->name('supervisor.analytics');
    Route::get('/orders/{id}', function ($id) {
        return Order::with('user')->findOrFail($id);
    });
    Route::get('/orders/{order}', [AnalyticsController::class, 'show']);
    Route::get('/order-details/{orderId}', [AnalyticsController::class, 'getOrderDetails']);
    Route::get('/all-activities', [AnalyticsController::class, 'getAllActivities']);
    
    // User Addition Views
    Route::view('/add-cashier', 'supervisor.add-cashier')->name('add.cashier');
    Route::view('/add-magazinier', 'supervisor.add-magazinier')->name('add.magazinier');
    
    // Foyer Management
    Route::get('/foyers', [FoyerController::class, 'index'])->name('supervisor.foyers');
    Route::post('/foyers', [FoyerController::class, 'store'])->name('foyers.store');
    Route::get('/foyers/{id}', [FoyerController::class, 'show'])->name('foyers.show');
    Route::put('/foyers/{id}', [FoyerController::class, 'update'])->name('foyers.update');
    Route::get('/foyers/{id}/edit', [FoyerController::class, 'edit'])->name('foyers.edit');
    Route::delete('/foyers/{id}', [FoyerController::class, 'destroy'])->name('foyers.destroy');
    
    // Foyer User Management
    Route::get('/foyers/{id}/available-users', [FoyerController::class, 'getAvailableUsers'])->name('foyers.users.available');
    Route::get('/foyers/chiefs/available', [FoyerController::class, 'getAvailableChiefs'])->name('foyers.chiefs.available');
    Route::post('/foyers/{id}/workers', [FoyerController::class, 'addWorker'])->name('foyers.workers.add');
    Route::delete('/foyers/{foyerId}/workers/{userId}', [FoyerController::class, 'removeWorker'])->name('foyers.workers.remove');
    
    // Settings
    Route::view('/settings', 'settings')->name('settings');

    // Notifications
    Route::get('/notifications/dropdown', [SupervisorNotificationController::class, 'getDropdownData'])->name('supervisor.notifications.dropdown');
    Route::post('/notifications/{notification}/mark-as-read', [SupervisorNotificationController::class, 'markAsRead'])->name('supervisor.notifications.markAsRead');
    Route::get('/notifications', [SupervisorNotificationController::class, 'index'])->name('supervisor.notifications.index');
});

// Rest of your routes...
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Apply role middleware to cashier routes
Route::middleware('rolemanager:cashier')->group(function () {
    //cashier routes
    Route::get('/startnewsale', [App\Http\Controllers\POSController::class, 'index'])->name('new.sale');
    
    // POS API Routes
    Route::get('/pos/product/{barcode}', [App\Http\Controllers\POSController::class, 'getProductByBarcode']);
    Route::get('/pos/search', [App\Http\Controllers\POSController::class, 'searchProducts']);
    Route::post('/pos/checkout', [App\Http\Controllers\POSController::class, 'checkout']);

    // Other cashier routes...
    Route::get('/newsale', [TransactionController::class, 'showSalePage'])->middleware('auth')->name('cashier.sale');
    Route::post('/newsale', [TransactionController::class, 'addProductToBill'])->middleware('auth');
    Route::get('/start-sale', [TransactionController::class, 'startNewSale'])->middleware('auth')->name('start.sale');
    Route::get('/end-sale', [TransactionController::class, 'endTransaction'])->middleware('auth')->name('end.sale');

    Route::get('/bill/{billId}/transactions', [TransactionController::class, 'getBillTransactions']);
    Route::get('/cashier/transactions', [TransactionController::class, 'getAllTransactions'])->name('cashier.transactions');

    Route::get('/cashier/dashboard', [TransactionController::class, 'dashboard'])->name('cashier.dashboard');

    Route::post('/Order', [OrderController::class, 'createOrder'])->name('cashier.MakeAnOrder');

    Route::get('/orders', [OrderController::class, 'listOrdersForMagazinier']);
    Route::post('/orders/{order}/update', [OrderController::class, 'updateOrderStatus']);
    Route::get('/cashier/orders', [OrderController::class, 'cashierOrders'])->name('cashier.orders');

    Route::get('/get-product-price/{productId}', [ProductController::class, 'getProductPrice']);
    Route::get('/MakeAnOrder', [ProductController::class, 'showDemandForm'])->name('MakeAnOrder');
    Route::get('/pos', [App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/products', [App\Http\Controllers\POSController::class, 'getProducts'])->name('pos.products');
    Route::get('/pos/product/barcode', [App\Http\Controllers\POSController::class, 'getProductByBarcode'])->name('pos.product.barcode');
    Route::post('/pos/process-sale', [App\Http\Controllers\POSController::class, 'processSale'])->name('pos.process-sale');

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

    // Notifications Routes
    Route::get('/magazinier/notifications/pending-orders', [App\Http\Controllers\MagazinierController::class, 'getPendingOrderNotifications'])
        ->name('magazinier.notifications.pending-orders');
});

// Magazinier Notification Routes
Route::middleware(['auth', 'rolemanager:magazinier'])->group(function () {
    Route::get('/magazinier/notifications', [MagazinierNotificationController::class, 'index'])->name('magazinier.notifications');
    Route::post('/notifications/{id}/mark-as-read', [MagazinierNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/magazinier/notifications/all', [MagazinierNotificationController::class, 'all'])->name('magazinier.notifications.all');
    Route::get('/magazinier/notifications/dropdown', [MagazinierNotificationController::class, 'index'])->name('magazinier.notifications.dropdown');
});

// test order

Route::get('/get-product-price/{productName}', [ProductController::class, 'getProductPrice']);

// Cashier Notification Routes
Route::middleware(['auth', 'rolemanager:cashier'])->group(function () {
    Route::get('/cashier/notifications', [CashierNotificationController::class, 'index'])->name('cashier.notifications');
    Route::post('/notifications/{notification}/mark-as-read', [CashierNotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/cashier/notifications/all', [CashierNotificationController::class, 'all'])->name('cashier.notifications.all');
});

require __DIR__ . '/auth.php';
