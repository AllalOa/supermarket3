@extends('layouts.app-cashier')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Cashier Dashboard - Supermarket Pro</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            /* Custom Styles */
            :root {
                --primary: #3b82f6;
                /* Bleu vif */
                --primary-light: #60a5fa;
                --primary-dark: #2563eb;
                --secondary: #10b981;
                /* Vert (pour les accents) */
                --danger: #ef4444;
                /* Rouge */
                --success: #10b981;
                /* Vert */
                --warning: #f59e0b;
                /* Orange */
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

            .card {
                background-color: var(--white);
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .card-header {
                background-color: var(--primary);
                padding: 1rem 1.5rem;
                color: var(--white);
                font-size: 1.25rem;
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .quick-action-btn {
                padding: 1rem;
                background-color: var(--gray-100);
                border-radius: 0.5rem;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .quick-action-btn:hover {
                background-color: var(--gray-200);
                transform: translateY(-2px);
            }

            .quick-action-btn i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
                color: var(--primary);
            }

            .transaction-item {
                display: flex;
                justify-content: space-between;
                padding: 1rem;
                border-bottom: 1px solid var(--gray-200);
                transition: background-color 0.2s ease;
            }

            .transaction-item:hover {
                background-color: var(--gray-100);
            }

            .transaction-item:last-child {
                border-bottom: none;
            }

            /* Receipt Styles */
            .receipt-content {
                max-width: 400px;
                margin: auto;
            }

            .receipt {
                background-color: white;
                padding: 20px;
                font-family: 'Courier New', monospace;
            }

            .receipt-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .receipt-info {
                margin-bottom: 20px;
                padding: 10px 0;
                border-top: 1px dashed #ddd;
                border-bottom: 1px dashed #ddd;
            }

            .receipt-table {
                width: 100%;
                margin-bottom: 20px;
            }

            .receipt-table th {
                text-align: left;
                padding: 8px 4px;
                border-bottom: 1px solid #ddd;
            }

            .receipt-table td {
                padding: 8px 4px;
            }

            .receipt-summary {
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px dashed #ddd;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
            }

            .receipt-footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px dashed #ddd;
            }

            .receipt-actions {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }

            .btn-print-now,
            .btn-email {
                flex: 1;
                padding: 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                font-weight: 600;
            }

            .btn-print-now {
                background-color: #4f46e5;
                color: white;
            }

            .btn-email {
                background-color: #10b981;
                color: white;
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
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal.visible {
                display: flex;
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
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                margin-bottom: 0.75rem;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            }

            .toast.show {
                opacity: 1;
                transform: translateX(0);
            }

            .toast-success {
                border-left: 4px solid #10b981;
            }

            .toast-error {
                border-left: 4px solid #ef4444;
            }

            .toast-warning {
                border-left: 4px solid #f59e0b;
            }

            /* Receipt Modal Styles */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1000;
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
                margin: auto;
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

            .receipt {
                background-color: white;
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
                background-color: var(--success);
                color: var(--white);
            }

            .btn-email:hover {
                background-color: #059669;
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
        </style>
    </head>

    <body class="bg-gray-100">
        <div class="min-h-screen flex flex-col">


            <!-- Main Content -->
            <main class="flex-1 container mx-auto p-6">
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('new.sale') }}" class="group">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group-hover:border-blue-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-300">
                                    <i class="fas fa-cash-register text-blue-500 text-xl"></i>
                                </div>
                                <span class="text-xs font-medium text-blue-500 bg-blue-50 px-2 py-1 rounded-full">Quick Access</span>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">New Sale</h3>
                            <p class="text-sm text-gray-500">Start a new transaction</p>
                        </div>
                    </a>

                    <div class="group cursor-pointer" onclick="viewAllTransactions()">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group-hover:border-blue-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center group-hover:bg-indigo-100 transition-colors duration-300">
                                    <i class="fas fa-history text-indigo-500 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">View All</h3>
                            <p class="text-sm text-gray-500">Transaction history</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer" onclick="scanProduct()">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group-hover:border-blue-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition-colors duration-300">
                                    <i class="fas fa-barcode text-green-500 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Scan Product</h3>
                            <p class="text-sm text-gray-500">Quick product lookup</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer" onclick="priceCheck()">
                        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 group-hover:border-blue-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center group-hover:bg-yellow-100 transition-colors duration-300">
                                    <i class="fas fa-tag text-yellow-500 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Price Check</h3>
                            <p class="text-sm text-gray-500">Verify product prices</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Today's Sales -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">Today's Sales</h3>
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-blue-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if (isset($somme))
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-bold text-gray-800">{{ number_format($somme, 2, ',', ' ') }}</span>
                                    <span class="ml-2 text-gray-500">DA</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-green-500">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    <span>4.3% from yesterday</span>
                                </div>
                            @else
                                <p class="text-red-500 text-sm">No transactions found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Items Sold -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">Total Items Sold Today</h3>
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-green-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-gray-800">{{ $totalItemsSold }}</span>
                                <span class="ml-2 text-gray-500">items</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-green-500">
                                <i class="fas fa-shopping-basket mr-1"></i>
                                <span>Total items today</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customers -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">Total Customers</h3>
                                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-purple-500"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-gray-800">{{ $totalBillsToday }}</span>
                                <span class="ml-2 text-gray-500">today</span>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-receipt mr-1"></i>
                                <span>Total bills processed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bills -->
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">Recent Bills</h3>
                            <a href="#" class="text-sm text-blue-500 hover:text-blue-600 font-medium">View All</a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($recentBills as $bill)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="showBillDetails({{ $bill->id }})">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-receipt text-blue-500"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">Bill #{{ $bill->id }}</p>
                                                <p class="text-sm text-gray-500">{{ $bill->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-800">{{ number_format($bill->total, 2, ',', ' ') }} DA</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Completed
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Bill Details Modal -->
                <div id="billModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Receipt</h2>
                            <span class="close-modal" onclick="closeBillModal()">&times;</span>
                        </div>
                        <div class="modal-body">
                            <div class="receipt">
                                <div class="receipt-header">
                                    <h2>{{ $user->foyer->name ?? 'Supermarket Pro' }}</h2>
                                    <p>{{ $user->foyer->address ?? 'Main Street, City' }}</p>
                                    <p>Tel: {{ auth()->user()->phone ?? '(Not Available)' }}</p>
                                </div>
                                <div class="receipt-info">
                                    <p>Bill #<span id="modalBillId"></span></p>
                                    <p>Date: <span id="modalBillDate"></span></p>
                                    <p>Cashier: <span id="modalBillCashier">{{ auth()->user()->name }}</span></p>
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
                                    <tbody id="modalBillItems">
                                        <!-- Receipt items will be added here -->
                                    </tbody>
                                </table>
                                <div class="receipt-summary">
                                    <div class="summary-row">
                                        <span>Total:</span>
                                        <span id="modalTotal">0.00 DA</span>
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
            </main>
        </div>

        <!-- Toast Container -->
        <div id="toastContainer" class="toast-container"></div>

        <script>
            // Add any necessary JavaScript functions here
            function viewAllTransactions() {
                window.location.href = "{{ route('cashier.transactions') }}";
            }

            function scanProduct() {
                // Implement scan product functionality
            }

            function priceCheck() {
                // Implement price check functionality
            }

            function showBillDetails(billId) {
                console.log('Fetching bill details for ID:', billId);
                
                // Show loading state
                const modal = document.getElementById('billModal');
                modal.style.display = 'flex';
                modal.classList.add('visible');
                document.getElementById('modalBillItems').innerHTML = '<tr><td colspan="4" class="text-center py-4">Loading...</td></tr>';
                
                fetch(`/bill/${billId}/transactions`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.error || 'Failed to load bill details');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received bill data:', data);
                        const billItems = document.getElementById('modalBillItems');
                        
                        if (!data.bill || !data.transactions) {
                            throw new Error('Invalid bill data received');
                        }
                        
                        // Update bill info
                        document.getElementById('modalBillId').textContent = data.bill.id;
                        document.getElementById('modalBillDate').textContent = new Date(data.bill.created_at).toLocaleString();
                        document.getElementById('modalBillCashier').textContent = data.bill.cashier_name;
                        
                        // Clear previous items
                        billItems.innerHTML = '';
                        
                        if (data.transactions.length === 0) {
                            billItems.innerHTML = '<tr><td colspan="4" class="text-center py-4">No items found</td></tr>';
                            return;
                        }
                        
                        // Add each transaction to the table
                        data.transactions.forEach(transaction => {
                            billItems.innerHTML += `
                                <tr>
                                    <td>${transaction.name}</td>
                                    <td>${transaction.quantity}</td>
                                    <td>${parseFloat(transaction.unit_price).toFixed(2)}</td>
                                    <td>${parseFloat(transaction.total).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        
                        // Update total amount (keep DA only here)
                        document.getElementById('modalTotal').textContent = parseFloat(data.bill.total).toFixed(2) + ' DA';
                    })
                    .catch(error => {
                        console.error('Error fetching bill details:', error);
                        document.getElementById('modalBillItems').innerHTML = 
                            `<tr><td colspan="4" class="text-center py-4 text-red-500">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                ${error.message}
                            </td></tr>`;
                        showToast(error.message, 'error');
                    });
            }

            function closeBillModal() {
                const modal = document.getElementById('billModal');
                modal.classList.remove('visible');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('billModal');
                if (event.target == modal) {
                    closeBillModal();
                }
            }

            function showToast(message, type = 'success') {
                const toastContainer = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                        <div class="flex-grow">${message}</div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4">&times;</button>
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                setTimeout(() => toast.classList.add('show'), 10);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        </script>
    </body>

    </html>
@endsection
