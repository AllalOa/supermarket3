@extends('layouts.appp')

@section('title', 'Inventory Management')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-6">
        Inventory Management
    </h2>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="mb-6 flex items-center justify-between bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md">
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove();" class="text-green-800 hover:text-green-900 font-bold">
                ✖
            </button>
        </div>
    @endif

    {{-- ❌ Error Message --}}
    @if(session('error'))
        <div class="mb-6 flex items-center justify-between bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-md">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove();" class="text-red-800 hover:text-red-900 font-bold">
                ✖
            </button>
        </div>
    @endif

    {{-- 📊 Inventory Table --}}
    <div class="overflow-x-auto rounded-xl shadow-lg backdrop-blur-sm bg-white/50 border border-gray-200">
        <table class="w-full bg-white/50 text-left">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 text-sm uppercase">
                <tr>
                    <th class="p-4 font-semibold">Barcode</th>
                    <th class="p-4 font-semibold">Product Name</th>
                    <th class="p-4 font-semibold">Category</th>
                    <th class="p-4 font-semibold">Price</th>
                    <th class="p-4 font-semibold">Unit Price</th>
                    <th class="p-4 font-semibold">Quantity</th>
                    
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition duration-200 ease-in-out">
                        <td class="p-4 font-semibold text-gray-900">
                            <span class="px-2 py-1 text-sm font-medium bg-gray-100 rounded">
                                {{ $product->barcode }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-700">{{ $product->name }}</td>
                        <td class="p-4 text-gray-700">{{ $product->category }}</td>
                        <td class="p-4 text-gray-900 font-bold">
                            {{ number_format($product->price, 2) }} 
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ number_format($product->unit_price, 2) }} 
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-bold text-white rounded-full
                                {{ $product->quantity < 5 ? 'bg-red-500' : 'bg-green-500' }}">
                                {{ $product->quantity }}
                            </span>
                        </td>
                       
                       
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">No products available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
