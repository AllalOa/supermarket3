@extends('layouts.app-cashier')
    
@section('content')
  <!-- Add your custom content for the cashier dashboard here -->
  @if (session('success'))
    <div class="bg-green-400 text-white p-4 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

  <div class="flex gap-8">
    <!-- Left Section -->
    <div class="flex-1">
      <a href="{{ route('start.sale') }}" class="btn-add">
        <button id="newSaleBtn" class="w-full mb-6 bg-[#4361ee] text-white py-3 rounded-lg font-semibold hover:bg-[#3f37c9] transition">
          Start New Sale
        </button>
      </a>

      <!-- Recent Transactions -->
      <div id="recentTransactions" class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>
        <div class="space-y-3">
          @foreach ($transactions as $transaction)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
              <div>
                <p class="font-medium text-[#2b2d42]">{{ $transaction->transaction_id }}</p>
                <p class="text-sm text-[#6c757d]">{{ $transaction->created_at->format('Y-m-d H:i') }}</p>
              </div>
              <span class="text-[#4361ee] font-semibold">{{ number_format($transaction->total, 2) . 'DA' }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection
