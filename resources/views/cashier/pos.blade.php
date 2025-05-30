@extends('layouts.app-cashier')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Section - Product Grid -->
            <div class="lg:col-span-2">
                <!-- Search and Filter Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="productSearch" placeholder="Search products..." 
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                            </div>
                        <!-- Barcode Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" id="barcodeInput" placeholder="Scan barcode..." autofocus
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <i class="fas fa-barcode absolute left-3 top-3 text-gray-400"></i>
                        </div>
                            </div>
                        </div>
                    <!-- Category Filters -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        <button class="category-btn active px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            data-category="all">All</button>
                        <button class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            data-category="Food">Food</button>
                        <button class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            data-category="Beverages">Beverages</button>
                        <button class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            data-category="Snacks">Snacks</button>
                        <button class="category-btn px-4 py-2 rounded-full text-sm font-medium transition-colors"
                            data-category="Household">Household</button>
                            </div>
                        </div>

                <!-- Product Grid -->
                <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Product cards will be dynamically added here -->
                        </div>
                    </div>

            <!-- Right Section - Cart -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm h-full flex flex-col">
                    <!-- Cart Header -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold">Shopping Cart</h2>
                            <div class="text-sm text-gray-600">Bill #<span id="billId">{{ $billId ?? '1001' }}</span></div>
                    </div>
                </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4" style="max-height: calc(100vh - 400px);">
                        <div id="cartItems" class="space-y-4">
                            <!-- Cart items will be dynamically added here -->
            </div>
                        <!-- Empty Cart Message -->
                        <div id="emptyCart" class="text-center py-8">
                            <i class="fas fa-shopping-cart text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Your cart is empty</p>
                            <p class="text-gray-400 text-sm">Add products by clicking on the cards</p>
                    </div>
                        </div>

                    <!-- Cart Summary -->
                    <div class="border-t border-gray-200 p-4 bg-gray-50">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-medium">0.00 DA</span>
                    </div>
                            <div id="discountRow" class="flex justify-between text-sm text-green-600" style="display: none;">
                            <span>Discount:</span>
                                <span id="discountAmount" class="font-medium">-0.00 DA</span>
                        </div>
                            <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                            <span>Total:</span>
                            <span id="totalAmount">0.00 DA</span>
                        </div>
                    </div>

                        <!-- Cart Actions -->
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <button onclick="showDiscountModal()" class="btn-discount flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i class="fas fa-percent mr-2"></i>
                                Discount
                        </button>
                            <button onclick="checkout()" class="btn-checkout flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-cash-register mr-2"></i>
                                Checkout
                        </button>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

    <!-- Modals -->
        <!-- Discount Modal -->
    <div id="discountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Apply Discount</h3>
                <button onclick="closeModal('discountModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            <div class="p-4">
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="discountType" value="percentage" checked class="mr-2">
                                Percentage (%)
                            </label>
                        <label class="flex items-center">
                            <input type="radio" name="discountType" value="amount" class="mr-2">
                                Fixed Amount
                            </label>
                        </div>
                    <input type="number" id="discountValue" placeholder="Enter discount value" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="grid grid-cols-4 gap-2">
                        <button onclick="setDiscountValue(5)" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">5%</button>
                        <button onclick="setDiscountValue(10)" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">10%</button>
                        <button onclick="setDiscountValue(15)" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">15%</button>
                        <button onclick="setDiscountValue(20)" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">20%</button>
                        </div>
                    <button onclick="applyDiscount()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Apply Discount
                    </button>
                        </div>
                </div>
            </div>
        </div>

        <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg w-full max-w-md mx-4">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Receipt</h3>
                <button onclick="closeReceipt()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
                </div>
            <div class="p-4">
                <div class="bg-white p-4 border border-gray-200 rounded-lg">
                    <div class="text-center mb-4">
                        <h2 class="text-xl font-bold">{{ auth()->user()->foyer->name ?? 'Supermarket Pro' }}</h2>
                        <p class="text-gray-600">{{ auth()->user()->foyer->address ?? 'Main Street, City' }}</p>
                        <p class="text-gray-600">Tel: {{ auth()->user()->phone ?? '(Not Available)' }}</p>
                        </div>
                    <div class="border-t border-b border-gray-200 py-2 mb-4">
                            <p>Bill #<span id="receiptBillId"></span></p>
                            <p>Date: <span id="receiptDate">{{ now()->format('Y-m-d H:i:s') }}</span></p>
                            <p>Cashier: <span id="receiptCashier">{{ auth()->user()->name }}</span></p>
                        </div>
                    <table class="w-full mb-4">
                        <thead class="border-b border-gray-200">
                            <tr>
                                <th class="text-left py-2">Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="receiptItems">
                                <!-- Receipt items will be added here -->
                            </tbody>
                        </table>
                    <div class="border-t border-gray-200 pt-2 space-y-1">
                        <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span id="receiptSubtotal">0.00 DA</span>
                            </div>
                        <div id="receiptDiscountRow" class="flex justify-between text-green-600" style="display:none">
                                <span>Discount:</span>
                                <span id="receiptDiscount">-0.00 DA</span>
                            </div>
                        <div class="flex justify-between font-bold pt-2 border-t border-gray-200">
                                <span>Total:</span>
                                <span id="receiptTotal">0.00 DA</span>
                            </div>
                        </div>
                    <div class="text-center mt-4 text-gray-600">
                            <p>Thank you for shopping with us!</p>
                            <p>Please come again</p>
                        </div>
                    </div>
                <div class="flex gap-4 mt-4">
                    <button onclick="printReceipt()" class="flex-1 flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-print mr-2"></i> Print
                        </button>
                    <button onclick="emailReceipt()" class="flex-1 flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-envelope mr-2"></i> Email
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>
    
    <!-- Audio Element for Beep Sound -->
    <audio id="beepSound" preload="auto">
        <source src="{{ asset('sounds/beep.mp3') }}" type="audio/mpeg">
    </audio>
</div>

        <!-- JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSearch = document.getElementById('productSearch');
                const barcodeInput = document.getElementById('barcodeInput');
                const productGrid = document.getElementById('productGrid');
                const cartItems = document.getElementById('cartItems');
                const emptyCart = document.getElementById('emptyCart');
                const beepSound = document.getElementById('beepSound');
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

    // Category buttons
    document.querySelectorAll('.category-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            
            // Add active class to clicked button
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('active', 'bg-blue-600', 'text-white');
            
            // Filter products
            filterByCategory(this.dataset.category);
        });
    });

                function handleProductSearch() {
                    const searchTerm = productSearch.value.trim();

                    if (searchTerm.length < 2) {
            loadAllProducts();
                        return;
                    }

                    fetch(`/pos/products?search=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                displayProducts(data);
                        })
                        .catch(error => {
                            console.error('Error searching for products:', error);
                            showToast('Error searching for products', 'error');
                        });
                }

    function loadAllProducts() {
        fetch('/pos/products')
            .then(response => response.json())
            .then(data => {
                displayProducts(data);
            })
            .catch(error => {
                console.error('Error loading products:', error);
                showToast('Error loading products', 'error');
            });
    }

    function displayProducts(products) {
        productGrid.innerHTML = '';

                    products.forEach(product => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow-sm overflow-hidden cursor-pointer transform transition-transform hover:scale-105';
            card.innerHTML = `
                <div class="aspect-w-1 aspect-h-1">
                    <img src="${product.product_picture ? '/storage/' + product.product_picture : '/images/default-product.jpg'}" 
                        alt="${product.name}"
                        class="w-full h-48 object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">${product.name}</h3>
                    <p class="text-gray-600 text-sm mb-2">${product.category}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-blue-600 font-semibold">${product.unit_price} DA</span>
                    </div>
                </div>
            `;
            
            card.addEventListener('click', () => addToCart(product));
            productGrid.appendChild(card);
        });
    }

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

    function filterByCategory(category) {
        if (category === 'all') {
            loadAllProducts();
            return;
        }

                    fetch(`/pos/products?category=${encodeURIComponent(category)}`)
                        .then(response => response.json())
                        .then(data => {
                displayProducts(data);
                        })
                        .catch(error => {
                            console.error('Error filtering products:', error);
                            showToast('Error loading products', 'error');
                        });
    }

                function addToCart(product) {
                    const existingItemIndex = cart.findIndex(item => item.product_id === product.id);

                    if (existingItemIndex !== -1) {
                        cart[existingItemIndex].quantity += 1;
                        cart[existingItemIndex].total = parseFloat((cart[existingItemIndex].quantity * cart[existingItemIndex].price).toFixed(2));
                    } else {
                        cart.push({
                            product_id: product.id,
                            product_name: product.name,
                            barcode: product.barcode,
                            price: parseFloat(product.unit_price),
                            quantity: 1,
                            total: parseFloat(product.unit_price)
                        });
                    }

                    // Play beep sound
                    beepSound.currentTime = 0; // Reset sound to start
                    beepSound.play().catch(error => console.log('Error playing sound:', error));

                    updateCartDisplay();
                    showToast(`Added ${product.name} to cart`, 'success');
                }

                function updateCartDisplay() {
                    if (cart.length === 0) {
            emptyCart.style.display = 'block';
            cartItems.innerHTML = '';
                    } else {
                        emptyCart.style.display = 'none';
                        renderCartItems();
                    }

                    calculateTotals();
                }

                function renderCartItems() {
        cartItems.innerHTML = '';

                    cart.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
            cartItem.innerHTML = `
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">${item.product_name}</h4>
                    <p class="text-sm text-gray-600">${parseFloat(item.price).toFixed(2)} DA</p>
                    </div>
                <div class="flex items-center gap-2">
                    <button onclick="changeQuantity(${index}, -1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300">
                        <i class="fas fa-minus text-sm"></i>
                    </button>
                    <span class="w-8 text-center">${item.quantity}</span>
                    <button onclick="changeQuantity(${index}, 1)" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 hover:bg-gray-300">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                    <button onclick="removeItem(${index})" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 hover:bg-red-200 text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            cartItems.appendChild(cartItem);
                    });
    }

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

                function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = subtotal;

        document.getElementById('subtotal').innerText = subtotal.toFixed(2) + ' DA';
        document.getElementById('totalAmount').innerText = total.toFixed(2) + ' DA';
    }

                window.checkout = function() {
                    if (cart.length === 0) {
                        showToast('Cart is empty', 'warning');
                        return;
                    }

                    const billId = document.getElementById('billId').innerText;
                    const totalText = document.getElementById('totalAmount').innerText;
                    const total = parseFloat(totalText.replace(' DA', ''));

                    const transformedItems = cart.map(item => ({
            id: item.product_id,
                        quantity: item.quantity,
            unit_price: parseFloat(item.price),
            total: parseFloat((item.price * item.quantity).toFixed(2))
                    }));

                    const saleData = {
            billId: billId,
                        items: transformedItems,
            total: parseFloat(total.toFixed(2))
        };

                    fetch('/pos/process-sale', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
                            },
                            body: JSON.stringify(saleData)
                        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    try {
                        return Promise.reject(JSON.parse(text));
                    } catch (e) {
                        return Promise.reject({ message: text });
                    }
                });
            }
            return response.json();
        })
                        .then(data => {
                            if (data.success) {
                showReceipt(data);
                                resetCart();
                                showToast('Sale completed successfully', 'success');
                            } else {
                throw new Error(data.message || 'Unknown error');
                            }
                        })
                        .catch(error => {
                            console.error('Error processing sale:', error);
                            showToast('Error processing sale: ' + (error.message || 'Unknown error'), 'error');
                        });
                };

    function showReceipt(data) {
        const modal = document.getElementById('receiptModal');
        document.getElementById('receiptBillId').textContent = data.bill_id;
                    const receiptItems = document.getElementById('receiptItems');
        
                    receiptItems.innerHTML = '';
        data.items.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                <td class="py-2">${item.product_name}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-right">${parseFloat(item.price).toFixed(2)}</td>
                <td class="text-right">${(item.quantity * item.price).toFixed(2)}</td>
            `;
                        receiptItems.appendChild(row);
                    });

        document.getElementById('receiptSubtotal').innerText = data.total.toFixed(2) + ' DA';
        document.getElementById('receiptTotal').innerText = data.total.toFixed(2) + ' DA';
        
        // Update receipt date and cashier
        document.getElementById('receiptDate').innerText = new Date().toLocaleString();
        document.getElementById('receiptCashier').innerText = '{{ auth()->user()->name }}';
        
        modal.classList.remove('hidden');
                }

                function resetCart() {
                    cart = [];
                    updateCartDisplay();

                    const billIdElement = document.getElementById('billId');
                    const currentBillId = parseInt(billIdElement.innerText);
                    billIdElement.innerText = (currentBillId + 1).toString();
                }

                function showToast(message, type = 'success') {
                    const toast = document.createElement('div');
        toast.className = `flex items-center p-4 mb-4 rounded-lg shadow-lg transition-all transform translate-x-full
            ${type === 'success' ? 'bg-green-100 text-green-800' : 
              type === 'error' ? 'bg-red-100 text-red-800' : 
              'bg-yellow-100 text-yellow-800'}`;

                    let icon = 'fa-check-circle';
                    if (type === 'error') icon = 'fa-exclamation-circle';
                    if (type === 'warning') icon = 'fa-exclamation-triangle';

                    toast.innerHTML = `
            <i class="fas ${icon} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.getElementById('toastContainer').appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
        });

                    setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
                    }, 5000);
                }

                function debounce(func, delay) {
                    let timeout;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(context, args), delay);
                    };
                }

    // Initial load
    loadAllProducts();
});

window.showDiscountModal = function() {
    document.getElementById('discountModal').classList.remove('hidden');
};

window.closeModal = function(modalId) {
    document.getElementById(modalId).classList.add('hidden');
};

window.closeReceipt = function() {
    document.getElementById('receiptModal').classList.add('hidden');
};

window.setDiscountValue = function(value) {
    document.getElementById('discountValue').value = value;
};

window.applyDiscount = function() {
    const type = document.querySelector('input[name="discountType"]:checked').value;
    const value = parseFloat(document.getElementById('discountValue').value);
    
    if (isNaN(value) || value <= 0) {
        showToast('Please enter a valid discount value', 'error');
        return;
    }

    const subtotalText = document.getElementById('subtotal').innerText;
    const subtotal = parseFloat(subtotalText.replace(' DA', ''));
    
    let discountAmount;
    if (type === 'percentage') {
        if (value > 100) {
            showToast('Discount percentage cannot exceed 100%', 'error');
            return;
        }
        discountAmount = (subtotal * value) / 100;
    } else {
        if (value > subtotal) {
            showToast('Discount amount cannot exceed subtotal', 'error');
            return;
        }
        discountAmount = value;
    }

    document.getElementById('discountRow').style.display = 'flex';
    document.getElementById('discountAmount').innerText = '-' + discountAmount.toFixed(2) + ' DA';
    
    const total = subtotal - discountAmount;
    
    document.getElementById('totalAmount').innerText = total.toFixed(2) + ' DA';
    
    closeModal('discountModal');
    showToast('Discount applied successfully', 'success');
};

window.printReceipt = function() {
    const receiptContent = document.querySelector('#receiptModal .bg-white.p-4.border');
    const printWindow = window.open('', '', 'width=600,height=600');
    const foyerName = '{{ auth()->user()->foyer->name ?? "Supermarket Pro" }}';
    const foyerAddress = '{{ auth()->user()->foyer->address ?? "Main Street, City" }}';
    const cashierPhone = '{{ auth()->user()->phone ?? "(Not Available)" }}';
    
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Receipt - ${foyerName}</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .mb-4 { margin-bottom: 1rem; }
                    .py-2 { padding: 0.5rem 0; }
                    .border-t { border-top: 1px solid #e5e7eb; }
                    .border-b { border-bottom: 1px solid #e5e7eb; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 0.5rem; }
                    .font-bold { font-weight: bold; }
                </style>
            </head>
            <body>
                ${receiptContent.innerHTML}
            </body>
    </html>
    `);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
};

window.emailReceipt = function() {
    // You can implement email functionality here
    showToast('Email functionality will be implemented soon', 'info');
};
</script>
@endsection