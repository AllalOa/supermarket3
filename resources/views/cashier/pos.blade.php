

@extends('layouts.app-cashier')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supermarket Pro - Cashier</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --secondary: #14b8a6;
            --danger: #ef4444;
            --danger-light: #f87171;
            --success: #10b981;
            --warning: #f59e0b;
            --warning-light: #fbbf24;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --white: #ffffff;
        }

        .cashier-container {
            display: flex;
            gap: 1.5rem;
            max-width: 1600px;
            margin: 0 auto;
            padding: 1rem;
            height: calc(100vh - 2rem);
        }

        .left-panel, .right-panel {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 48%;
        }

        .card {
            background-color: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-header {
            background-color: var(--gray-100);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 5%;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .input-group {
            padding: 1.2rem;
        }

        .input-wrapper {
            margin-bottom: 1rem;
        }

        .input-wrapper label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-icon i {
            position: absolute;
            left: 1rem;
            font-size: 1rem;
            color: var(--gray-500);
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: all 0.15s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .quick-categories {
            padding: 0 1.5rem 1.5rem;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .category-btn {
            padding: 0.5rem 1rem;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .category-btn:hover {
            background-color: var(--gray-200);
        }

        .category-btn.active {
            background-color: var(--primary-light);
            color: var(--white);
            border-color: var(--primary);
        }

        .action-buttons {
            padding: 0 1.5rem 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .btn-add, .btn-clear {
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-add {
            background-color: var(--success);
            color: var(--white);
            flex: 2;
        }

        .btn-add:hover {
            background-color: #059669;
        }

        .btn-clear {
            background-color: var(--gray-200);
            color: var(--gray-700);
            flex: 1;
        }

        .btn-clear:hover {
            background-color: var(--gray-300);
        }

        .numeric-keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
            padding: 1.5rem;
        }

        .keypad-btn {
            padding: 1rem 0;
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .keypad-btn:hover {
            background-color: var(--gray-100);
            transform: translateY(-2px);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .clear-btn {
            background-color: var(--danger-light);
            color: var(--white);
            border: none;
        }

        .clear-btn:hover {
            background-color: var(--danger);
        }

        .backspace-btn {
            background-color: var(--warning-light);
            color: var(--white);
            border: none;
            grid-column: span 2;
        }

        .backspace-btn:hover {
            background-color: var(--warning);
        }

        .bill-items-container {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .bill-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bill-table th {
            padding: 0.75rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            background-color: var(--white);
            z-index: 10;
        }

        .bill-table td {
            padding: 0.75rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-100);
        }

        .bill-table .item-remove-btn {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
            opacity: 0.7;
            transition: all 0.15s ease;
        }

        .bill-table .item-remove-btn:hover {
            opacity: 1;
        }

        .item-quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: 4px;
            color: var(--gray-700);
            cursor: pointer;
            font-size: 12px;
            transition: all 0.15s ease;
        }

        .quantity-btn:hover {
            background-color: var(--gray-200);
        }

        .quantity-display {
            padding: 0 8px;
            font-weight: 500;
        }

        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: var(--gray-400);
            text-align: center;
        }

        .empty-cart-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        .bill-summary {
            padding: 1.5rem;
            background-color: var(--gray-100);
            border-top: 1px solid var(--gray-200);
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .summary-item.discount {
            color: var(--success);
            font-weight: 500;
        }

        .summary-item.total {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--gray-300);
        }

        .checkout-actions {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .btn-discount, .btn-checkout {
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-discount {
            background-color: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            flex: 1;
        }

        .btn-discount:hover {
            background-color: var(--gray-100);
        }

        .btn-checkout {
            background-color: var(--primary);
            color: var(--white);
            flex: 2;
        }

        .btn-checkout:hover {
            background-color: var(--primary-dark);
        }

        .shortcuts-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
            padding: 1rem;
        }

        .btn-void, .btn-hold, .btn-recent, .btn-print {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 0.5rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            gap: 0.5rem;
            text-align: center;
        }

        .btn-void {
            background-color: var(--gray-100);
            color: var(--danger);
        }

        .btn-void:hover {
            background-color: var(--danger-light);
            color: var(--white);
        }

        .btn-hold, .btn-recent, .btn-print {
            background-color: var(--gray-100);
            color: var(--gray-700);
        }

        .btn-hold:hover, .btn-recent:hover, .btn-print:hover {
            background-color: var(--gray-200);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.visible {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: var(--white);
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal.visible .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .close-modal {
            font-size: 1.5rem;
            color: var(--gray-500);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .close-modal:hover {
            color: var(--gray-700);
        }

        .modal-body {
            padding: 1rem;
        }

        .discount-options {
            margin-bottom: 1.5rem;
        }

        .discount-type {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .discount-type label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .discount-value {
            margin-bottom: 1rem;
        }

        .discount-value input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            font-size: 1rem;
        }

        .discount-presets {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .discount-presets button {
            flex: 1;
            padding: 0.5rem;
            background-color: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .discount-presets button:hover {
            background-color: var(--gray-200);
        }

        .btn-apply-discount {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--success);
            color: var(--white);
            border: none;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-apply-discount:hover {
            background-color: #059669;
        }

        .receipt-content {
            max-width: 400px;
        }

        .receipt {
            background-color: var(--white);
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--gray-800);
            border: 1px solid var(--gray-200);
            border-radius: 0.375rem;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 1rem;
        }

        .receipt-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .receipt-info {
            border-top: 1px dashed var(--gray-300);
            border-bottom: 1px dashed var(--gray-300);
            padding: 0.75rem 0;
            margin-bottom: 1rem;
        }

        .receipt-table {
            width: 100%;
            margin-bottom: 1rem;
        }

        .receipt-table th {
            text-align: left;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-300);
        }

        .receipt-table td {
            padding: 0.25rem 0;
        }

        .receipt-summary {
            border-top: 1px dashed var(--gray-300);
            padding-top: 0.75rem;
            margin-bottom: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: 1rem;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed var(--gray-300);
        }

        .receipt-footer {
            text-align: center;
            border-top: 1px dashed var(--gray-300);
            padding-top: 0.75rem;
        }

        .receipt-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-print-now, .btn-email {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-print-now {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-print-now:hover {
            background-color: var(--primary-dark);
        }

        .btn-email {
            background-color: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-email:hover {
            background-color: var(--gray-200);
        }

        /* Product Search Results */
        .product-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 100;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .product-search-results.active {
            display: block;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-200);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: var(--gray-100);
        }

        .search-result-name {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .search-result-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .bill-item-row {
            transition: background-color 0.3s ease;
        }

        .bill-item-row.highlight {
            background-color: #e6f7ff;
            animation: highlight 1s ease;
        }

        @keyframes highlight {
            0% { background-color: var(--success); }
            100% { background-color: transparent; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
        }

        .toast {
            display: flex;
            align-items: center;
            padding: 1rem;
            background-color: var(--white);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 0.75rem;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .toast-success .toast-icon {
            color: var(--success);
        }

        .toast-error .toast-icon {
            color: var(--danger);
        }

        .toast-warning .toast-icon {
            color: var(--warning);
        }

        .toast-message {
            flex-grow: 1;
            font-size: 0.875rem;
            color: var(--gray-700);
        }

        .toast-close {
            color: var(--gray-500);
            cursor: pointer;
            font-size: 1rem;
            margin-left: 0.75rem;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .cashier-container {
                flex-direction: column;
                height: 100%;
            }

            .left-panel, .right-panel {
                width: 100%;
                height: 100%;
            }

            .bill-card {
                height: 100%;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="cashier-container">
        <!-- Left Panel: Product Input & Controls -->
        <div class="left-panel">
            <!-- Search & Input Section -->
            <div class="card input-card">
                <div class="card-header">
                    <h2>Add Products</h2>
                </div>
                <div class="input-group">
                    <div class="input-wrapper">
                        <label for="productSearch">Product Search</label>
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="productSearch" placeholder="Search by name or description" class="input-field">
                            <div id="searchResults" class="product-search-results"></div>
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <label for="barcodeInput">Barcode / Product Code</label>
                        <div class="input-with-icon">
                            <i class="fas fa-barcode"></i>
                            <input type="text" id="barcodeInput" placeholder="Scan or enter barcode" autofocus class="input-field">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <label for="quantityInput">Quantity</label>
                        <div class="input-with-icon">
                            <i class="fas fa-hashtag"></i>
                            <input type="number" id="quantityInput" placeholder="Quantity" value="1" min="1" class="input-field">
                        </div>
                    </div>
                </div>
                <!-- Quick Product Categories -->
                <div class="quick-categories">
                    <h3>Quick Categories</h3>
                    <div class="category-buttons">
                        <button class="category-btn" data-category="grocery" onclick="filterByCategory('grocery')">Grocery</button>
                        <button class="category-btn" data-category="dairy" onclick="filterByCategory('dairy')">Dairy</button>
                        <button class="category-btn" data-category="bakery" onclick="filterByCategory('bakery')">Bakery</button>
                        <button class="category-btn" data-category="produce" onclick="filterByCategory('produce')">Produce</button>
                        <button class="category-btn" data-category="beverages" onclick="filterByCategory('beverages')">Beverages</button>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button onclick="addProduct()" class="btn-add">
                        <i class="fas fa-plus"></i> Add to Cart
                    </button>
                    <button onclick="clearInputs()" class="btn-clear">
                        <i class="fas fa-sync"></i> Clear
                    </button>
                </div>
            </div>
            <!-- Numeric Keypad -->
            
        </div>
        <!-- Right Panel: Cart & Checkout -->
        <div class="right-panel">
            <!-- Bill/Cart -->
            <div class="card bill-card">
                <div class="card-header">
                    <h2>Current Transaction</h2>
                    <div class="bill-id">Bill #<span id="billId">{{ $billId ?? '1001' }}</span></div>
                </div>
                <div class="bill-items-container">
                    <table class="bill-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="billItems">
                            <!-- Items will be added here -->
                        </tbody>
                    </table>
                    <div id="emptyCart" class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <p>Your cart is empty</p>
                        <p class="empty-cart-subtext">Scan a product or select from categories to add items</p>
                    </div>
                </div>
                <!-- Subtotals Section -->
                <div class="bill-summary">
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span id="subtotal">0.00 DA</span>
                    </div>
                    <div class="summary-item">
                        <span>Tax (19%):</span>
                        <span id="tax">0.00 DA</span>
                    </div>
                    <div id="discountRow" class="summary-item discount" style="display:none;">
                        <span>Discount:</span>
                        <span id="discountAmount">-0.00 DA</span>
                    </div>
                    <div class="summary-item total">
                        <span>Total:</span>
                        <span id="totalAmount">0.00 DA</span>
                    </div>
                </div>
                <!-- Checkout Actions -->
                <div class="checkout-actions">
                    <button onclick="showDiscountModal()" class="btn-discount">
                        <i class="fas fa-percent"></i> Discount
                    </button>
                    <button onclick="checkout()" class="btn-checkout">
                        <i class="fas fa-cash-register"></i> Checkout
                    </button>
                </div>
            </div>
        
           
        </div>
    </div>

    <!-- Discount Modal -->
    <div id="discountModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Apply Discount</h2>
                <span class="close-modal" onclick="closeModal('discountModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="discount-options">
                    <div class="discount-type">
                        <label>
                            <input type="radio" name="discountType" value="percentage" checked>
                            Percentage (%)
                        </label>
                        <label>
                            <input type="radio" name="discountType" value="amount">
                            Fixed Amount
                        </label>
                    </div>
                    <div class="discount-value">
                        <input type="number" id="discountValue" placeholder="Enter discount value">
                    </div>
                    <div class="discount-presets">
                        <button onclick="setDiscountValue(5)">5%</button>
                        <button onclick="setDiscountValue(10)">10%</button>
                        <button onclick="setDiscountValue(15)">15%</button>
                        <button onclick="setDiscountValue(20)">20%</button>
                    </div>
                </div>
                <button onclick="applyDiscount()" class="btn-apply-discount">Apply Discount</button>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="modal">
        <div class="modal-content receipt-content">
            <div class="modal-header">
                <h2>Receipt</h2>
                <span class="close-modal" onclick="closeModal('receiptModal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="receipt">
                    <div class="receipt-header">
                        <h2> enpei Supermarket </h2>
                        <p>enpei Street, City</p>
                        <p>Tel: (123) 456-7890</p>
                    </div>
                    <div class="receipt-info">
                        <p>Bill #<span id="receiptBillId">1001</span></p>
                        <p>Date: <span id="receiptDate">01/01/2023</span></p>
                        <p>Cashier: <span id="receiptCashier">Admin</span></p>
                    </div>
                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="receiptItems">
                            <!-- Receipt items will be added here -->
                        </tbody>
                    </table>
                    <div class="receipt-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="receiptSubtotal">0.00 DA</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax (19%):</span>
                            <span id="receiptTax">0.00 DA</span>
                        </div>
                        <div id="receiptDiscountRow" class="summary-row" style="display:none">
                            <span>Discount:</span>
                            <span id="receiptDiscount">-0.00 DA</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="receiptTotal">0.00 DA</span>
                        </div>
                    </div>
                    <div class="receipt-footer">
                        <p>Thank you for shopping with us!</p>
                        <p>Please come again</p>
                    </div>
                </div>
                <div class="receipt-actions">
                    <button class="btn-print-now">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="btn-email">
                        <i class="fas fa-envelope"></i> Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- JavaScript -->
    <script>
        // Sample product data
        const products = [
            { id: 1, name: 'Milk', price: 120.00, barcode: '8901234567890', category: 'dairy' },
            { id: 2, name: 'Bread', price: 85.50, barcode: '8901234567891', category: 'bakery' },
            { id: 3, name: 'Eggs (12)', price: 220.00, barcode: '8901234567892', category: 'dairy' },
            { id: 4, name: 'Cheese', price: 350.00, barcode: '8901234567893', category: 'dairy' },
            { id: 5, name: 'Tomatoes (1kg)', price: 170.00, barcode: '8901234567894', category: 'produce' },
            { id: 6, name: 'Chicken (1kg)', price: 580.00, barcode: '8901234567895', category: 'meat' },
            { id: 7, name: 'Rice (5kg)', price: 450.00, barcode: '8901234567896', category: 'grocery' },
            { id: 8, name: 'Coca Cola (2L)', price: 130.00, barcode: '8901234567897', category: 'beverages' },
            { id: 9, name: 'Apples (1kg)', price: 190.00, barcode: '8901234567898', category: 'produce' },
            { id: 10, name: 'Potato Chips', price: 75.00, barcode: '8901234567899', category: 'snacks' }
        ];

        let cart = [];
        let discountPercentage = 0;
        let discountAmount = 0;
        let activeInput = null;
        
        document.addEventListener("DOMContentLoaded", function() {
            updateCartDisplay();
            
            // Set active input field event listeners
            document.getElementById('barcodeInput').addEventListener('focus', function() {
                activeInput = this;
            });
            
            document.getElementById('quantityInput').addEventListener('focus', function() {
                activeInput = this;
            });
            
            document.getElementById('productSearch').addEventListener('focus', function() {
                activeInput = this;
            });
            
            document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    addProductByBarcode();
                }
            });
            
            // Search functionality
            document.getElementById('productSearch').addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                const searchResults = document.getElementById('searchResults');
                
                if (searchValue.length < 2) {
                    searchResults.classList.remove('active');
                    return;
                }
                
                const filteredProducts = products.filter(product => 
                    product.name.toLowerCase().includes(searchValue)
                );
                
                displaySearchResults(filteredProducts);
            });
        });
        
        function appendNumber(number) {
            if (activeInput) {
                const cursorPos = activeInput.selectionStart;
                const currentValue = activeInput.value;
                const newValue = currentValue.substring(0, cursorPos) + number + currentValue.substring(cursorPos);
                activeInput.value = newValue;
                activeInput.selectionStart = cursorPos + 1;
                activeInput.selectionEnd = cursorPos + 1;
                activeInput.focus();
            }
        }
        
        function appendDecimal() {
            if (activeInput && !activeInput.value.includes('.')) {
                const cursorPos = activeInput.selectionStart;
                const currentValue = activeInput.value;
                const newValue = currentValue.substring(0, cursorPos) + '.' + currentValue.substring(cursorPos);
                activeInput.value = newValue;
                activeInput.selectionStart = cursorPos + 1;
                activeInput.selectionEnd = cursorPos + 1;
                activeInput.focus();
            }
        }
        
        function clearInput() {
            if (activeInput) {
                activeInput.value = '';
                activeInput.focus();
            }
        }
        
        function backspace() {
            if (activeInput) {
                const cursorPos = activeInput.selectionStart;
                if (cursorPos > 0) {
                    const currentValue = activeInput.value;
                    const newValue = currentValue.substring(0, cursorPos - 1) + currentValue.substring(cursorPos);
                    activeInput.value = newValue;
                    activeInput.selectionStart = cursorPos - 1;
                    activeInput.selectionEnd = cursorPos - 1;
                }
                activeInput.focus();
            }
        }
        
        function clearInputs() {
            document.getElementById('productSearch').value = '';
            document.getElementById('barcodeInput').value = '';
            document.getElementById('quantityInput').value = '1';
            document.getElementById('searchResults').classList.remove('active');
        }
        
        function displaySearchResults(filteredProducts) {
            const searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = '';
            
            if (filteredProducts.length > 0) {
                filteredProducts.forEach(product => {
                    const resultItem = document.createElement('div');
                    resultItem.className = 'search-result-item';
                    resultItem.innerHTML = `
                        <div class="search-result-name">${product.name}</div>
                        <div class="search-result-details">
                            <span>${product.price.toFixed(2)} DA</span>
                            <span>${product.category}</span>
                        </div>
                    `;
                    resultItem.addEventListener('click', function() {
                        addProductById(product.id);
                        document.getElementById('productSearch').value = '';
                        searchResults.classList.remove('active');
                    });
                    searchResults.appendChild(resultItem);
                });
                searchResults.classList.add('active');
            } else {
                searchResults.classList.remove('active');
            }
        }
        
        function addProductByBarcode() {
            const barcode = document.getElementById('barcodeInput').value;
            const product = products.find(p => p.barcode === barcode);
            
            if (product) {
                addProductToCart(product);
                document.getElementById('barcodeInput').value = '';
                document.getElementById('barcodeInput').focus();
                showToast('success', `${product.name} added to cart`);
            } else {
                showToast('error', 'Product not found');
            }
        }
        
        function addProductById(productId) {
            const product = products.find(p => p.id === productId);
            if (product) {
                addProductToCart(product);
                showToast('success', `${product.name} added to cart`);
            }
        }
        
        function addProduct() {
            const barcode = document.getElementById('barcodeInput').value;
            if (barcode) {
                addProductByBarcode();
            } else {
                const searchValue = document.getElementById('productSearch').value;
                if (searchValue) {
                    const product = products.find(p => p.name.toLowerCase().includes(searchValue.toLowerCase()));
                    if (product) {
                        addProductToCart(product);
                        clearInputs();
                        showToast('success', `${product.name} added to cart`);
                    } else {
                        showToast('error', 'Product not found');
                    }
                } else {
                    showToast('warning', 'Please enter a product name or barcode');
                }
            }
        }
        
        function addProductToCart(product) {
            const quantity = parseInt(document.getElementById('quantityInput').value) || 1;
            const existingItem = cart.find(item => item.id === product.id);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: quantity,
                    total: product.price * quantity
                });
            }
            
            updateCartDisplay();
        }
        
        function updateCartDisplay() {
            const billItems = document.getElementById('billItems');
            const emptyCart = document.getElementById('emptyCart');
            
            billItems.innerHTML = '';
            
            if (cart.length > 0) {
                emptyCart.style.display = 'none';
                
                cart.forEach((item, index) => {
                    item.total = item.price * item.quantity;
                    
                    const row = document.createElement('tr');
                    row.className = 'bill-item-row';
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.price.toFixed(2)} DA</td>
                        <td>
                            <div class="item-quantity-control">
                                <button class="quantity-btn" onclick="updateItemQuantity(${index}, -1)">-</button>
                                <span class="quantity-display">${item.quantity}</span>
                                <button class="quantity-btn" onclick="updateItemQuantity(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td>${item.total.toFixed(2)} DA</td>
                        <td><button class="item-remove-btn" onclick="removeItem(${index})"><i class="fas fa-times"></i></button></td>
                    `;
                    billItems.appendChild(row);
                });
            } else {
                emptyCart.style.display = 'flex';
            }
            
            calculateTotals();
        }
        
        function updateItemQuantity(index, change) {
            if (cart[index]) {
                const newQuantity = cart[index].quantity + change;
                if (newQuantity > 0) {
                    cart[index].quantity = newQuantity;
                    updateCartDisplay();
                } else if (newQuantity === 0) {
                    removeItem(index);
                }
            }
        }
        
        function removeItem(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }
        
        function calculateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
            const taxRate = 0.19;
            const tax = subtotal * taxRate;
            
            let totalWithDiscount = subtotal + tax;
            let displayDiscountAmount = 0;
            
            if (discountPercentage > 0) {
                displayDiscountAmount = subtotal * (discountPercentage / 100);
                totalWithDiscount -= displayDiscountAmount;
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountAmount').textContent = `-${displayDiscountAmount.toFixed(2)} DA`;
            } else if (discountAmount > 0) {
                displayDiscountAmount = discountAmount;
                totalWithDiscount -= displayDiscountAmount;
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountAmount').textContent = `-${displayDiscountAmount.toFixed(2)} DA`;
            } else {
                document.getElementById('discountRow').style.display = 'none';
            }
            
            document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' DA';
            document.getElementById('tax').textContent = tax.toFixed(2) + ' DA';
            document.getElementById('totalAmount').textContent = totalWithDiscount.toFixed(2) + ' DA';
        }
        
        function filterByCategory(category) {
            const buttons = document.querySelectorAll('.category-btn');
            buttons.forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // Show product search results filtered by category
            const filteredProducts = products.filter(product => product.category === category);
            displaySearchResults(filteredProducts);
        }
        
        function showDiscountModal() {
            if (cart.length === 0) {
                showToast('warning', 'Add items to cart before applying discount');
                return;
            }
            
            document.getElementById('discountValue').value = '';
            document.getElementById('discountModal').classList.add('visible');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('visible');
        }
        
        function setDiscountValue(value) {
            document.getElementById('discountValue').value = value;
        }
        
        function applyDiscount() {
            const discountType = document.querySelector('input[name="discountType"]:checked').value;
            const discountValue = parseFloat(document.getElementById('discountValue').value);
            
            if (isNaN(discountValue) || discountValue <= 0) {
                showToast('error', 'Please enter a valid discount value');
                return;
            }
            
            if (discountType === 'percentage') {
                if (discountValue > 100) {
                    showToast('error', 'Discount percentage cannot exceed 100%');
                    return;
                }
                discountPercentage = discountValue;
                discountAmount = 0;
                showToast('success', `${discountValue}% discount applied`);
            } else {
                const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
                if (discountValue > subtotal) {
                    showToast('error', 'Discount amount cannot exceed subtotal');
                    return;
                }
                discountAmount = discountValue;
                discountPercentage = 0;
                showToast('success', `${discountValue.toFixed(2)} DA discount applied`);
            }
            
            calculateTotals();
            closeModal('discountModal');
        }
        
        function checkout() {
            if (cart.length === 0) {
                showToast('warning', 'Cart is empty');
                return;
            }
            
            populateReceipt();
            document.getElementById('receiptModal').classList.add('visible');
        }
        
        function populateReceipt() {
            const today = new Date();
            const dateString = today.toLocaleDateString();
            
            document.getElementById('receiptBillId').textContent = document.getElementById('billId').textContent;
            document.getElementById('receiptDate').textContent = dateString;
            document.getElementById('receiptCashier').textContent = 'Admin';
            
            const receiptItems = document.getElementById('receiptItems');
            receiptItems.innerHTML = '';
            
            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${item.price.toFixed(2)}</td>
                    <td>${item.total.toFixed(2)}</td>
                `;
                receiptItems.appendChild(row);
            });
            
            const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
            const tax = subtotal * 0.19;
            document.getElementById('receiptSubtotal').textContent = subtotal.toFixed(2) + ' DA';
            document.getElementById('receiptTax').textContent = tax.toFixed(2) + ' DA';
            
            let totalWithDiscount = subtotal + tax;
            if (discountPercentage > 0) {
                const discountValue = subtotal * (discountPercentage / 100);
                totalWithDiscount -= discountValue;
                document.getElementById('receiptDiscountRow').style.display = 'flex';
                document.getElementById('receiptDiscount').textContent = `-${discountValue.toFixed(2)} DA`;
            } else if (discountAmount > 0) {
                totalWithDiscount -= discountAmount;
                document.getElementById('receiptDiscountRow').style.display = 'flex';
                document.getElementById('receiptDiscount').textContent = `-${discountAmount.toFixed(2)} DA`;
            } else {
                document.getElementById('receiptDiscountRow').style.display = 'none';
            }
            
            document.getElementById('receiptTotal').textContent = totalWithDiscount.toFixed(2) + ' DA';
        }
        
        function showToast(type, message) {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            let icon;
            switch(type) {
                case 'success':
                    icon = 'fas fa-check-circle';
                    break;
                case 'error':
                    icon = 'fas fa-exclamation-circle';
                    break;
                case 'warning':
                    icon = 'fas fa-exclamation-triangle';
                    break;
                default:
                    icon = 'fas fa-info-circle';
            }
            
            toast.innerHTML = `
                <div class="toast-icon"><i class="${icon}"></i></div>
                <div class="toast-message">${message}</div>
                <div class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></div>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>  

@endsection