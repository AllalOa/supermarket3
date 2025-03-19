@extends('layouts.app-magazinier')

@section('title', 'Dashboard')

@section('content')
   <!-- Dashboard Content -->
   <div id="dashboard" class="content-section">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
       
      <a href="{{route('magazinier.inventory')}}">
 <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-[#6c757d]">Total Products</p>
              <p id="totalProducts" class="text-3xl font-bold text-[#2b2d42]">{{ $totalProducts }}</p>

            </div>
            <i class="fas fa-boxes text-2xl text-[#4361ee]"></i>
          </div>
        </div>

      </a>
      
     
        <a href="{{ route('magazinier.orders')}}"  rel="noopener noreferrer">
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-[#6c757d]">Pending Orders</p>
              <p class="text-3xl font-bold text-[#2b2d42]">{{ $pendingOrders }}</p>
            </div>
            <i class="fas fa-clipboard-list text-2xl text-[#4361ee]"></i>
          </div>
        </div>
        </a>

        <a href="{{route('magazinier.inventory')}}">

<div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
    <div class="flex justify-between items-center">
      <div>
        <p class="text-[#6c757d]">Low Stock Items</p>
        <p id="lowStock" class="text-3xl font-bold text-[#2b2d42]">{{ $lowStockProducts }}</p>
      </div>
      <i class="fas fa-exclamation-triangle text-2xl text-[#ff4d4d]"></i>
    </div>
  </div>
</a>
        
      </div>
    </div>
 
@endsection