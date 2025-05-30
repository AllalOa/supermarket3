@extends('layouts.app-cashier')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Transaction History</h1>
                    <p class="mt-2 text-sm text-gray-600">View and manage your past transactions</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">
                        Total: {{ $bills->total() ?? 0 }} transactions
                    </span>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-200">
            <form action="{{ route('cashier.transactions') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                        name="search" 
                        placeholder="Search by bill number..." 
                        value="{{ $search ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <input type="date" 
                        name="date" 
                        value="{{ $date ?? '' }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                    @if($search ?? false || $date ?? false)
                        <a href="{{ route('cashier.transactions') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bills List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-900">Transactions List</h2>
                    </div>
                </div>
            </div>

            @if(isset($bills) && $bills->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search ?? false || $date ?? false)
                            No transactions match your search criteria. Try adjusting your filters.
                        @else
                            There are no transactions to display yet.
                        @endif
                    </p>
                </div>
            @elseif(isset($bills))
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Bill #</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Items</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($bills as $bill)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-blue-50 flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold text-sm">#{{ $bill->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ $bill->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $bill->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $bill->transactions->count() }} items
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($bill->total, 2) }} DA</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="showBillDetails({{ $bill->id }})" 
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-600 hover:text-blue-800 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-blue-800 transition duration-150 ease-in-out">
                                        <svg class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bills->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $bills->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500 mb-2">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Error Loading Transactions</h3>
                    <p class="text-gray-500">
                        Unable to load transactions. Please try refreshing the page.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bill Modal -->
<div id="billModal" class="modal">
    <div class="modal-content receipt-content">
        <div class="modal-header">
            <h2 class="text-xl font-semibold text-gray-800">Receipt Details</h2>
            <button class="close-modal" onclick="closeBillModal()">
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="receipt">
                <div class="receipt-header">
                    <h2 class="text-xl font-bold mb-1">{{ $user->foyer->name ?? 'Supermarket Pro' }}</h2>
                    <p class="text-gray-600">{{ $user->foyer->address ?? 'Main Street, City' }}</p>
                    <p class="text-gray-600">Tel: {{ auth()->user()->phone ?? '(Not Available)' }}</p>
                </div>
                <div class="receipt-info">
                    <p>Bill #<span id="modalBillId" class="font-medium"></span></p>
                    <p>Date: <span id="modalBillDate" class="font-medium"></span></p>
                    <p>Cashier: <span id="modalBillCashier" class="font-medium">{{ auth()->user()->name }}</span></p>
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
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="modalTotal" class="font-bold">0.00 DA</span>
                    </div>
                </div>
                <div class="receipt-footer">
                    <p class="text-gray-600">Thank you for shopping with us!</p>
                    <p class="text-gray-500 text-sm">Please come again</p>
                </div>
            </div>
            <div class="receipt-actions">
                <button class="btn-print-now">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <button class="btn-email">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal and Receipt Styles */
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
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        background-color: white;
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
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .receipt {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .receipt-info {
        border-top: 1px dashed #e5e7eb;
        border-bottom: 1px dashed #e5e7eb;
        padding: 1rem 0;
        margin-bottom: 1rem;
    }

    .receipt-table {
        width: 100%;
        margin-bottom: 1.5rem;
        border-collapse: collapse;
    }

    .receipt-table th {
        text-align: left;
        padding: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
    }

    .receipt-table td {
        padding: 0.5rem;
        color: #4b5563;
    }

    .receipt-summary {
        border-top: 1px dashed #e5e7eb;
        padding-top: 1rem;
        margin-bottom: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .summary-row.total {
        font-weight: 600;
        color: #111827;
        font-size: 1.125rem;
    }

    .receipt-footer {
        text-align: center;
        border-top: 1px dashed #e5e7eb;
        padding-top: 1rem;
    }

    .receipt-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-print-now,
    .btn-email {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.15s ease;
    }

    .btn-print-now {
        background-color: #4f46e5;
        color: white;
    }

    .btn-print-now:hover {
        background-color: #4338ca;
    }

    .btn-email {
        background-color: #10b981;
        color: white;
    }

    .btn-email:hover {
        background-color: #059669;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
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
                
                // Update total amount
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

    // Toast notification function
    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.style.cssText = 'position:fixed;bottom:1rem;right:1rem;z-index:9999;';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `bg-white shadow-lg rounded-lg p-4 mb-4 border-l-4 ${
            type === 'error' ? 'border-red-500' : 'border-green-500'
        }`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-check-circle text-green-500'} mr-2"></i>
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">×</button>
            </div>
        `;

        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
</script>

@endsection 