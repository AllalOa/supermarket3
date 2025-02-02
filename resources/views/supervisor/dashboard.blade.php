@extends('layouts.appp')

@section('title', 'Dashboard')

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-[#6c757d]">Today's Sales</p>
          <p class="text-3xl font-bold text-[#2b2d42]">$2,450</p>
        </div>
        <i class="fas fa-shopping-cart text-2xl text-[#4361ee]"></i>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-[#6c757d]">Transactions</p>
          <p class="text-3xl font-bold text-[#2b2d42]">48</p>
        </div>
        <i class="fas fa-receipt text-2xl text-[#4361ee]"></i>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-[#6c757d]">Customers</p>
          <p class="text-3xl font-bold text-[#2b2d42]">62</p>
        </div>
        <i class="fas fa-users text-2xl text-[#4361ee]"></i>
      </div>
    </div>
  </div>
@endsection

