@extends('layouts.app-magazinier')

@section('title', 'Inventory Management')

@section('content')
<!-- Inventory Content -->
<div id="inventory" class="content-section">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-[#2b2d42]">Inventory Management</h2>
            <input type="text" id="searchInput" placeholder="Search products..." class="px-4 py-2 rounded-lg border border-gray-200 w-64">
        </div>

        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left">Image</th>
                    <th class="py-3 px-4 text-left">Product Name</th>
                    <th class="py-3 px-4 text-left">Category</th>
                    <th class="py-3 px-4 text-left">Quantity</th>
                    <th class="py-3 px-4 text-left">Price</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryList">
                @forelse($products as $product)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <!-- Product Image -->
                        <td class="py-3 px-4">
                            @if($product->product_picture)
                                <img src="{{ asset('storage/' . $product->product_picture) }}" 
                                     alt="Product Image" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <img src="{{ asset('default-product.png') }}" 
                                     alt="Default Image" 
                                     class="w-16 h-16 object-cover rounded-lg">
                            @endif
                        </td>
                        
                        <!-- Product Name -->
                        <td class="py-3 px-4">{{ $product->name }}</td>

                        <!-- Category -->
                        <td class="py-3 px-4">{{ $product->category }}</td>

                        <!-- Quantity -->
                        <td class="py-3 px-4">{{ $product->quantity }}</td>

                        <!-- Price -->
                        <td class="py-3 px-4">{{ number_format($product->price, 2) }}</td>

                        <!-- Status -->
                        <td class="py-3 px-4">
                            @if($product->quantity == 0)
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">Out of Stock</span>
                            @elseif($product->quantity <= 5)
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded">Low Stock</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">Available</span>
                            @endif
                        </td>

                        <!-- Actions (Aligned) -->
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-4">
                                <!-- Edit Button -->
                                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">No products available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
