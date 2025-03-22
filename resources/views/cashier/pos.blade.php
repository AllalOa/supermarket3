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

            .left-panel,
            .right-panel {
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

            .btn-add,
            .btn-clear {
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

            .btn-discount,
            .btn-checkout {
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

            .btn-void,
            .btn-hold,
            .btn-recent,
            .btn-print {
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

            .btn-hold,
            .btn-recent,
            .btn-print {
                background-color: var(--gray-100);
                color: var(--gray-700);
            }

            .btn-hold:hover,
            .btn-recent:hover,
            .btn-print:hover {
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

            .btn-print-now,
            .btn-email {
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
                0% {
                    background-color: var(--success);
                }

                100% {
                    background-color: transparent;
                }
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
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

                .left-panel,
                .right-panel {
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
                                <input type="text" id="productSearch" placeholder="Search by name or description"
                                    class="input-field">
                                <div id="searchResults" class="product-search-results"></div>
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label for="barcodeInput">Barcode / Product Code</label>
                            <div class="input-with-icon">
                                <i class="fas fa-barcode"></i>
                                <input type="text" id="barcodeInput" placeholder="Scan or enter barcode" autofocus
                                    class="input-field">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <label for="quantityInput">Quantity</label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="number" id="quantityInput" placeholder="Quantity" value="1"
                                    min="1" class="input-field">
                            </div>
                        </div>
                    </div>
                    <!-- Quick Product Categories -->
                    <div class="quick-categories">
                        <h3>Quick Categories</h3>

                        <div class="category-buttons">
                            <button class="category-btn" data-category="Food">Food</button>
                            <button class="category-btn" data-category="Dairy">Dairy</button>
                            <button class="category-btn" data-category="Bakery">Bakery</button>
                            <button class="category-btn" data-category="Produce">Produce</button>
                            <button class="category-btn" data-category="Beverages">Beverages</button>
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
                    <span class="close-modal" onclick="closeReceipt()">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="receipt">
                        <div class="receipt-header">
                            <h2> enpei Supermarket </h2>
                            <p>enpei Street, City</p>
                            <p>Tel: (123) 456-7890</p>
                        </div>
                        <div class="receipt-info">
                            <p>Bill #<span id="receiptBillId"></span></p>
                            <p>Date: <span id="receiptDate">{{ now()->format('Y-m-d H:i:s') }}</span></p>
                            <p>Cashier: <span id="receiptCashier">{{ auth()->user()->name }}</span></p>
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
            // Add this script to your blade file just before </body>
            document.addEventListener('DOMContentLoaded', function() {
                const productSearch = document.getElementById('productSearch');
                const barcodeInput = document.getElementById('barcodeInput');
                const searchResults = document.getElementById('searchResults');
                const billItems = document.getElementById('billItems');
                const emptyCart = document.getElementById('emptyCart');
                let cart = [];

                // Initialize meta info
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Setup event listeners
                productSearch.addEventListener('input', debounce(handleProductSearch, 300));
                barcodeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchByBarcode();
                    }
                });

                // Product Search
                function handleProductSearch() {
                    const searchTerm = productSearch.value.trim();

                    if (searchTerm.length < 2) {
                        searchResults.classList.remove('active');
                        return;
                    }

                    fetch(`/pos/products?search=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            displaySearchResults(data);
                        })
                        .catch(error => {
                            console.error('Error searching for products:', error);
                            showToast('Error searching for products', 'error');
                        });
                }

                function displaySearchResults(products) {
                    searchResults.innerHTML = '';

                    if (products.length === 0) {
                        searchResults.innerHTML = '<div class="search-result-item">No products found</div>';
                        searchResults.classList.add('active');
                        return;
                    }

                    products.forEach(product => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.dataset.product = JSON.stringify(product);
                        item.innerHTML = `
                <div class="search-result-name">${product.name}</div>
                <div class="search-result-details">
                    <span>Barcode: ${product.barcode}</span>
                    <span>Price: ${product.unit_price} DA</span>
                </div>
            `;
                        item.addEventListener('click', function() {
                            const selectedProduct = JSON.parse(this.dataset.product);
                            addToCart(selectedProduct);
                            searchResults.classList.remove('active');
                            productSearch.value = '';
                        });
                        searchResults.appendChild(item);
                    });

                    searchResults.classList.add('active');
                }

                // Barcode Search
                function searchByBarcode() {
                    const barcode = barcodeInput.value.trim();

                    if (!barcode) return;

                    fetch(`/pos/product/barcode?barcode=${encodeURIComponent(barcode)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Product not found');
                            }
                            return response.json();
                        })
                        .then(product => {
                            addToCart(product);
                            barcodeInput.value = '';
                            barcodeInput.focus();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Product not found', 'error');
                            barcodeInput.value = '';
                            barcodeInput.focus();
                        });
                }

                // Add product to cart from button click
                function addProduct() {
                    // This function will be used when clicking "Add to Cart" button
                    const barcode = barcodeInput.value.trim();
                    if (barcode) {
                        searchByBarcode();
                    } else {
                        showToast('Please enter a barcode or search for a product', 'warning');
                    }
                }

                // Filter by category
                // Modify the filterByCategory function
                document.querySelectorAll('.category-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const category = this.dataset.category;
                        filterByCategory(category);
                    });
                });

                // Global filter function
                window.filterByCategory = function(category) {
                    const categoryButtons = document.querySelectorAll('.category-btn');
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('active');
                        if (btn.dataset.category === category) {
                            btn.classList.add('active');
                        }
                    });

                    fetch(`/pos/products?category=${encodeURIComponent(category)}`)
                        .then(response => response.json())
                        .then(data => {
                            displaySearchResults(data);
                            document.getElementById('productSearch').value = '';
                        })
                        .catch(error => {
                            console.error('Error filtering products:', error);
                            showToast('Error loading products', 'error');
                        });
                };

                // Update the displaySearchResults function to handle category results
                function displaySearchResults(products) {
                    const searchResults = document.getElementById('searchResults');
                    searchResults.innerHTML = '';

                    if (products.length === 0) {
                        searchResults.innerHTML = '<div class="search-result-item">No products found</div>';
                        searchResults.classList.add('active');
                        return;
                    }

                    products.forEach(product => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.dataset.product = JSON.stringify(product);
                        item.innerHTML = `
            <div class="search-result-name">${product.name}</div>
            <div class="search-result-details">
                <span>Barcode: ${product.barcode}</span>
                <span>Price: ${product.unit_price} DA</span>
            </div>
        `;
                        item.addEventListener('click', function() {
                            const selectedProduct = JSON.parse(this.dataset.product);
                            addToCart(selectedProduct);
                            searchResults.classList.remove('active');
                        });
                        searchResults.appendChild(item);
                    });

                    searchResults.classList.add('active');
                }

                // Cart Functions
                function addToCart(product) {
                    const quantityInput = document.getElementById('quantityInput');
                    const quantity = parseInt(quantityInput.value) || 1;

                    // Check if product is already in cart
                    const existingItemIndex = cart.findIndex(item => item.product_id === product.id);

                    if (existingItemIndex !== -1) {
                        // Update quantity if product already in cart
                        cart[existingItemIndex].quantity += quantity;
                        cart[existingItemIndex].total = parseFloat((cart[existingItemIndex].quantity * cart[
                            existingItemIndex].price).toFixed(2));
                    } else {
                        // Add new item to cart
                        cart.push({
                            product_id: product.id,
                            product_name: product.name,
                            barcode: product.barcode,
                            price: parseFloat(product.unit_price),
                            quantity: quantity,
                            total: parseFloat((quantity * product.unit_price).toFixed(2))
                        });
                    }

                    // Reset quantity input
                    quantityInput.value = 1;

                    // Update the UI
                    updateCartDisplay();
                    showToast(`Added ${quantity} ${product.name} to cart`, 'success');
                }

                function updateCartDisplay() {
                    // Show/hide empty cart message
                    if (cart.length === 0) {
                        emptyCart.style.display = 'flex';
                        billItems.innerHTML = '';
                    } else {
                        emptyCart.style.display = 'none';
                        renderCartItems();
                    }

                    // Update totals
                    calculateTotals();
                }

                function renderCartItems() {
                    billItems.innerHTML = '';

                    cart.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.className = 'bill-item-row';
                        row.innerHTML = `
                <td>${item.product_name}</td>
                <td>${item.price.toFixed(2)} DA</td>
                <td>
                    <div class="item-quantity-control">
                        <button class="quantity-btn" onclick="changeQuantity(${index}, -1)">-</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn" onclick="changeQuantity(${index}, 1)">+</button>
                    </div>
                </td>
                <td>${item.total.toFixed(2)} DA</td>
                <td>
                    <button class="item-remove-btn" onclick="removeItem(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
                        billItems.appendChild(row);
                    });

                    // Define global functions for the onclick attributes
                    window.changeQuantity = function(index, change) {
                        const newQuantity = cart[index].quantity + change;

                        if (newQuantity < 1) return;

                        cart[index].quantity = newQuantity;
                        cart[index].total = parseFloat((cart[index].quantity * cart[index].price).toFixed(2));

                        updateCartDisplay();
                    };

                    window.removeItem = function(index) {
                        cart.splice(index, 1);
                        updateCartDisplay();
                    };
                }

                function calculateTotals() {
                    const subtotalElement = document.getElementById('subtotal');
                    const totalElement = document.getElementById('totalAmount');

                    // Calculate total (which is now just the sum of all items)
                    const total = cart.reduce((sum, item) => sum + item.total, 0);

                    // Update display
                    subtotalElement.innerText = total.toFixed(2) + ' DA';
                    totalElement.innerText = total.toFixed(2) + ' DA';
                }

                // Checkout function
                window.checkout = function() {
                    if (cart.length === 0) {
                        showToast('Cart is empty', 'warning');
                        return;
                    }

                    // Get bill data
                    const billId = document.getElementById('billId').innerText;
                    const totalText = document.getElementById('totalAmount').innerText;
                    const total = parseFloat(totalText.replace(' DA', ''));

                    // Transform cart items to match backend expectations
                    // In your checkout function, modify the transformedItems to include product_name
                    const transformedItems = cart.map(item => ({
                        product_id: item.product_id,
                        product_name: item.product_name, // Add this line
                        unit_price: item.price,
                        price: item.price, // Add this for receipt display
                        quantity: item.quantity,
                        total: item.total
                    }));

                    // Prepare data for submission
                    const saleData = {
                        bill_id: billId,
                        items: transformedItems,
                        subtotal: total,
                        tax: 0, // Assuming no tax for simplicity
                        discount_amount: 0, // Assuming no discount
                        discount_type: null,
                        total: total
                    };

                    // Submit sale
                    fetch('/pos/process-sale', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(saleData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showReceipt(saleData);
                                resetCart();
                                showToast('Sale completed successfully', 'success');
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error processing sale:', error);
                            showToast('Error processing sale: ' + (error.message || 'Unknown error'), 'error');
                        });
                };

                function showReceipt(saleData) {
                    const receiptModal = document.getElementById('receiptModal');
                    const receiptBillId = document.getElementById('receiptBillId');
                    const receiptDate = document.getElementById('receiptDate');
                    const receiptCashier = document.getElementById('receiptCashier');
                    const receiptItems = document.getElementById('receiptItems');
                    const receiptTotal = document.getElementById('receiptTotal');

                    // Set basic receipt info

                    receiptBillId.textContent = saleData.bill_id;
                    // Replace with actual user name from Auth

                    // Add items to receipt
                    receiptItems.innerHTML = '';
                    saleData.items.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>${item.price.toFixed(2)}</td>
                <td>${item.total.toFixed(2)}</td>
            `;
                        receiptItems.appendChild(row);
                    });

                    // Set total amount
                    receiptTotal.innerText = saleData.total.toFixed(2) + ' DA';

                    // Show receipt modal
                    receiptModal.classList.add('visible');
                }

                function resetCart() {
                    cart = [];
                    updateCartDisplay();

                    // Generate new bill ID
                    const billIdElement = document.getElementById('billId');
                    const currentBillId = parseInt(billIdElement.innerText);
                    billIdElement.innerText = (currentBillId + 1).toString();
                }

                // Helper Functions
                function clearInputs() {
                    productSearch.value = '';
                    barcodeInput.value = '';
                    document.getElementById('quantityInput').value = 1;
                    barcodeInput.focus();
                }

                // Toast notifications
                function showToast(message, type = 'success') {
                    const toastContainer = document.getElementById('toastContainer');
                    const toast = document.createElement('div');
                    toast.className = `toast toast-${type}`;

                    let icon = 'fa-check-circle';
                    if (type === 'error') icon = 'fa-exclamation-circle';
                    if (type === 'warning') icon = 'fa-exclamation-triangle';

                    toast.innerHTML = `
            <div class="toast-icon"><i class="fas ${icon}"></i></div>
            <div class="toast-message">${message}</div>
            <div class="toast-close" onclick="this.parentElement.remove()">×</div>
        `;

                    toastContainer.appendChild(toast);

                    // Animate in
                    setTimeout(() => {
                        toast.classList.add('show');
                    }, 10);

                    // Auto-remove after 5 seconds
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => {
                            toast.remove();
                        }, 300);
                    }, 5000);
                }

                // Debounce function for search input
                function debounce(func, delay) {
                    let timeout;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(context, args), delay);
                    };
                }

                // Initial focus on barcode input
                barcodeInput.focus();
            });

            function closeReceipt() {
                const receiptModal = document.getElementById('receiptModal');
                receiptModal.classList.remove('visible');
            }
        </script>
    </body>

    </html>
@endsection
