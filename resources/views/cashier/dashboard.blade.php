@extends('layouts.app-cashier')
    
    
@section('content')
  <!-- Add your custom content for the cashier dashboard here -->

  <div class="flex gap-8">
    <!-- Left Section -->
    <div class="flex-1">
        <a href="{{ route('newsale')}}"><button  id="newSaleBtn" class="w-full mb-6 bg-[#4361ee] text-white py-3 rounded-lg font-semibold hover:bg-[#3f37c9] transition">
        Start New Sale
      </button></a>
      

      <!-- Recent Transactions -->
      <div id="recentTransactions" class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>
        <div class="space-y-3">
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <div>
              <p class="font-medium text-[#2b2d42]">TRX-001245</p>
              <p class="text-sm text-[#6c757d]">2024-02-15 14:30</p>
            </div>
            <span class="text-[#4361ee] font-semibold">$45.99</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <div>
              <p class="font-medium text-[#2b2d42]">TRX-001244</p>
              <p class="text-sm text-[#6c757d]">2024-02-15 13:45</p>
            </div>
            <span class="text-[#4361ee] font-semibold">$32.50</span>
          </div>
        </div>
      </div>

      <!-- New Sale Form -->
      <div id="newSaleForm" class="hidden bg-white rounded-xl shadow-sm p-6 mt-6">
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
  </div>

@endsection


