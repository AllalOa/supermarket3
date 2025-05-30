@extends('layouts.app-cashier')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="cashier-container">
        <!-- Input Section -->
        <div class="input-section">
            <div class="input-group">
                <input type="text" id="barcodeInput" placeholder="Enter barcode" autofocus class="input-field" onfocus="setActiveInput('barcodeInput')">
                <input type="number" id="quantityInput" placeholder="Quantity" value="1" min="1" class="input-field" onfocus="setActiveInput('quantityInput')">
                <button onclick="addProduct()" class="btn-add">Add Product</button>
                <button onclick="endTransaction()" class="btn-end">End Transaction</button>
            </div>

            <!-- Numeric Keypad -->
            <div class="numeric-keypad">
                <button onclick="appendNumber('1')" class="keypad-btn">1</button>
                <button onclick="appendNumber('2')" class="keypad-btn">2</button>
                <button onclick="appendNumber('3')" class="keypad-btn">3</button>
                <button onclick="appendNumber('4')" class="keypad-btn">4</button>
                <button onclick="appendNumber('5')" class="keypad-btn">5</button>
                <button onclick="appendNumber('6')" class="keypad-btn">6</button>
                <button onclick="appendNumber('7')" class="keypad-btn">7</button>
                <button onclick="appendNumber('8')" class="keypad-btn">8</button>
                <button onclick="appendNumber('9')" class="keypad-btn">9</button>
                <button onclick="appendNumber('0')" class="keypad-btn zero-btn">0</button>
                <button onclick="clearInput()" class="keypad-btn clear-btn">C</button>
            </div>
        </div>

        <!-- Bill Preview (Hidden Initially) -->
        <div id="billPreview" class="hidden">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-[#2b2d42]">Supermarket Pro</h2>
                <p class="text-sm text-gray-500">123 Main Street, City</p>
                <p class="text-sm text-gray-500 mt-2">Tel: {{ auth()->user()->phone ?? '(Not Available)' }}</p>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500">Bill ID: <span id="billId">{{ $billId ?? 'N/A' }}</span></p>
            
            </div>

            <table class="w-full mb-4">
                <thead>
                    <tr class="border-b">
                        <th class="text-left pb-2 text-sm font-semibold">Product</th>
                        <th class="text-center pb-2 text-sm font-semibold">Qty</th>
                        <th class="text-right pb-2 text-sm font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody id="billItems"></tbody>
            </table>

            <div class="border-t pt-4">
                <div class="flex justify-between mb-2 font-semibold text-lg">
                    <span>Total:</span>
                    <span id="billTotal">0.00 DA</span>
                </div>
                <div class="text-sm text-gray-500 mt-4">
                    <p>Cashier: <span id="cashierName">{{ auth()->user()->name }}</span></p>
                    <p class="text-sm text-gray-500">Date: <span id="saleDate">{{ $saleDate }}</span></p>
                    <p class="text-sm text-gray-500">Time: <span id="saleTime">{{ $saleTime }}</span></p>
                    <p>Thank you for shopping with us!</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Ensure the body and html take full height and prevent scrolling */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden; /* Prevent scrolling */
}

/* Cashier Container */
.cashier-container {
    display: flex;
    justify-content: center;
    align-items: flex-start; /* Align items to the top */
    height: calc(100vh - 60px); /* Adjust height to account for navbar */
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 20px; /* Reduced top and bottom padding */
    overflow: hidden; /* Prevent scrolling */
}

/* Input Section */
.input-section {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    max-height: 100%; /* Ensure it doesn't overflow */
    margin-top: 20px; /* Raise the input section higher */
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 300px;
}

/* Input Fields */
.input-field {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 18px;
    width: 100%;
}

/* Numeric Keypad */
.numeric-keypad {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 columns */
    gap: 10px;
    width: 200px;
    max-height: 100%; /* Ensure it doesn't overflow */
}

.keypad-btn {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    font-size: 20px;
    cursor: pointer;
    text-align: center;
}

.keypad-btn:hover {
    background-color: #e9e9e9;
}

.zero-btn {
    grid-column: span 2; /* Make the "0" button span 2 columns */
}

.clear-btn {
    background-color: #dc3545;
    color: white;
}

.clear-btn:hover {
    background-color: #c82333;
}

/* Buttons */
.btn-add, .btn-end {
    padding: 15px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
    width: 100%;
}

.btn-add {
    background-color: #28a745;
    color: white;
}

.btn-add:hover {
    background-color: #218838;
}

.btn-end {
    background-color: #dc3545;
    color: white;
}

.btn-end:hover {
    background-color: #c82333;
}

/* Bill Preview */
#billPreview {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 400px;
    padding: 20px;
    margin-left: 20px;
    max-height: calc(100vh - 100px); /* Ensure it fits within the viewport */
    overflow-y: auto; /* Add scroll only to the bill preview if needed */
}

/* Hidden Class */
.hidden {
    display: none;
}

/* When bill is shown, adjust the layout */
.cashier-container.bill-shown {
    justify-content: flex-start;
}

.cashier-container.bill-shown .input-section {
    flex: 1;
}

.cashier-container.bill-shown #billPreview {
    display: block;
}
</style>
@endpush

@push('scripts')
<script>
    let activeInput = null; // Track the currently focused input

    // Function to set the active input
    function setActiveInput(inputId) {
        activeInput = document.getElementById(inputId);
    }

    // Function to append a number to the active input
    function appendNumber(number) {
        if (activeInput) {
            activeInput.value += number;
        }
    }

    // Function to clear the active input
    function clearInput() {
        if (activeInput) {
            activeInput.value = '';
        }
    }

    // Function to add a product to the bill
    function addProduct() {
        let barcode = document.getElementById('barcodeInput').value;
        let quantity = document.getElementById('quantityInput').value || 1;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/newsale', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ barcode, quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Show the bill preview if it's hidden
            const billPreview = document.getElementById('billPreview');
            const cashierContainer = document.querySelector('.cashier-container');
            if (billPreview.classList.contains('hidden')) {
                billPreview.classList.remove('hidden');
                cashierContainer.classList.add('bill-shown'); // Add the class to change the layout
            }

            // Update the bill preview
            updateBillPreview(data.transaction, data.bill);

            // Clear inputs
            document.getElementById('barcodeInput').value = '';
            clearInput();
        })
        .catch(error => console.error('Error:', error));
    }

    // Function to update the bill preview
    function updateBillPreview(transaction, bill) {
        const billItems = document.getElementById('billItems');
        const billTotal = document.getElementById('billTotal');

        // Add the new item to the bill
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="text-left py-2 text-sm">${transaction.name}</td>
            <td class="text-center py-2 text-sm">${transaction.quantity}</td>
            <td class="text-right py-2 text-sm">${transaction.total.toFixed(2)} DA</td>
        `;
        billItems.appendChild(newRow);

        // Update the total
        billTotal.textContent = `${bill.total.toFixed(2)} DA`;
    }

    // Function to end the transaction
    function endTransaction() {
        if (confirm('Are you sure you want to end this transaction?')) {
            window.location.href = "{{ route('end.sale') }}";
        }
    }
</script>
@endpush