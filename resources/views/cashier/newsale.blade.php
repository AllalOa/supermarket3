@extends('layouts.app-cashier')

@section('content')
  <!-- New Sale Form -->
  <div id="newSaleForm" class=" bg-white rounded-xl shadow-sm p-6 mt-6">
    <div class="grid grid-cols-3 gap-4 mb-4">
      <input type="text" id="productName" placeholder="Product Name" 
             class="p-2 border rounded-lg focus:outline-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      <input type="number" id="productQty" placeholder="Quantity" min="1" 
             class="p-2 border rounded-lg focus:outline-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
      <input type="number" id="productPrice" placeholder="Price" min="0" step="0.01" 
             class="p-2 border rounded-lg focus:outline-[#4361ee] focus:ring-1 focus:ring-[#4361ee]">
    </div>
    <div class="flex gap-3">
      <button id="addItemBtn" class="bg-[#4361ee] text-white px-6 py-2 rounded-lg hover:bg-[#3f37c9] transition">
        Add Item
      </button>
      <button id="printBillBtn" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
        Complete Sale
      </button>
    </div>
  </div>

  <!-- Right Section - Bill Preview -->
  <div id="billPreview" class="hidden w-[400px] bg-white rounded-xl shadow-sm p-6 h-fit sticky top-8">
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-[#2b2d42]">Supermarket Pro</h2>
      <p class="text-sm text-gray-500">123 Main Street, City</p>
      <p class="text-sm text-gray-500 mt-2">Tel: (555) 123-4567</p>
    </div>

    <div class="mb-4">
      <p class="text-sm text-gray-500">Transaction ID: <span id="trxId">TRX-001246</span></p>
      <p class="text-sm text-gray-500">Date: <span id="saleDate"></span></p>
      <p class="text-sm text-gray-500">Time: <span id="saleTime"></span></p>
    </div>

    <table class="w-full mb-4">
      <thead>
        <tr class="border-b">
          <th class="text-left pb-2 text-sm font-semibold">Product</th>
          <th class="text-center pb-2 text-sm font-semibold">Qty</th>
          <th class="text-right pb-2 text-sm font-semibold">Total</th>
        </tr>
      </thead>
      <tbody id="billItems">
        <!-- Items will be added here dynamically -->
      </tbody>
    </table>

    <div class="border-t pt-4">
      <div class="flex justify-between mb-2 font-semibold text-lg">
        <span>Total:</span>
        <span id="billTotal">$0.00</span>
      </div>
      <div class="text-sm text-gray-500 mt-4">
        <p>Cashier: John Doe</p>
        <p>Thank you for shopping with us!</p>
      </div>
    </div>
  </div>

@push('scripts') 
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const newSaleBtn = document.getElementById('newSaleBtn');
      const recentTransactions = document.getElementById('recentTransactions');
      const newSaleForm = document.getElementById('newSaleForm');
      const billPreview = document.getElementById('billPreview');
      const billItems = document.getElementById('billItems');
      const billTotal = document.getElementById('billTotal');
      const saleDate = document.getElementById('saleDate');
      const saleTime = document.getElementById('saleTime');

      let currentTotal = 0;
      let items = [];
      let saleStarted = false;

      // Event listener for starting new sale
      newSaleBtn.addEventListener('click', () => {
        console.log("New Sale button clicked!");
        recentTransactions.classList.add('hidden');
        newSaleForm.classList.remove('hidden');
        newSaleBtn.classList.add('hidden');
        const now = new Date();
        saleDate.textContent = now.toLocaleDateString();
        saleTime.textContent = now.toLocaleTimeString();
        saleStarted = true;
      });

      // Event listener for adding items to the bill
      document.getElementById('addItemBtn').addEventListener('click', () => {
        const name = document.getElementById('productName').value;
        const qty = parseInt(document.getElementById('productQty').value);
        const price = parseFloat(document.getElementById('productPrice').value);

        if (name && qty > 0 && price > 0) {
          if (!billPreview.classList.contains('hidden')) {
            billPreview.classList.remove('hidden');
          }

          const total = qty * price;
          items.push({ name, qty, price, total });

          const newRow = document.createElement('tr');
          newRow.className = 'border-b';
          newRow.innerHTML = `
            <td class="py-2 text-sm">${name}</td>
            <td class="text-center text-sm">${qty}</td>
            <td class="text-right text-sm">$${total.toFixed(2)}</td>
          `;
          billItems.appendChild(newRow);

          currentTotal += total;
          billTotal.textContent = `$${currentTotal.toFixed(2)}`;

          // Clear inputs
          document.getElementById('productName').value = '';
          document.getElementById('productQty').value = '';
          document.getElementById('productPrice').value = '';
        }
      });

      // Event listener for completing the sale
      document.getElementById('printBillBtn').addEventListener('click', () => {
        // Add transaction to recent transactions
        const newTransaction = document.createElement('div');
        newTransaction.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
        newTransaction.innerHTML = `
          <div>
            <p class="font-medium text-[#2b2d42]">TRX-001246</p>
            <p class="text-sm text-[#6c757d]">${new Date().toLocaleString()}</p>
          </div>
          <span class="text-[#4361ee] font-semibold">$${currentTotal.toFixed(2)}</span>
        `;
        recentTransactions.querySelector('div.space-y-3').prepend(newTransaction);

        // Reset everything
        billItems.innerHTML = '';
        currentTotal = 0;
        billTotal.textContent = '$0.00';
        items = [];
        recentTransactions.classList.remove('hidden');
        newSaleForm.classList.add('hidden');
        newSaleBtn.classList.remove('hidden');
        billPreview.classList.add('hidden');
        saleStarted = false;
      });
    });
  </script>
@endpush
@endsection


