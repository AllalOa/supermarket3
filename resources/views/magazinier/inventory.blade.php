@extends('layouts.app-magazinier')

@section('title', 'Inventory Management')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold text-gray-900">Inventory Management</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your products and stock levels</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Search Bar with Icon -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search products..." 
                           class="pl-10 pr-4 py-2 w-64 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                </div>
                <!-- Add Product Button -->
                <a href="{{ route('addProduct') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Add Product
                </a>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header with Search and Filters -->
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-4">
                            <i class="fas fa-boxes text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Product Inventory</h2>
                            <p class="text-sm text-gray-500">Total: {{ count($products) }} products</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative">
                            <select class="pl-4 pr-10 py-2.5 rounded-lg border border-gray-200 w-full appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="all">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="furniture">Furniture</option>
                                <option value="clothing">Clothing</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <tr class="border-b border-gray-200 hover:bg-gray-50/50 transition duration-200 ease-in-out">
                            <!-- Product Image -->
                            <td class="py-3 px-4">
                                @if($product->product_picture)
                                    <img src="{{ asset('storage/' . $product->product_picture) }}" 
                                         alt="Product Image" 
                                         class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                @else
                                    <img src="{{ asset('default-product.png') }}" 
                                         alt="Default Image" 
                                         class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                @endif
                            </td>
                            
                            <!-- Product Name -->
                            <td class="py-3 px-4 text-gray-700">{{ $product->name }}</td>

                            <!-- Category -->
                            <td class="py-3 px-4 text-gray-700">{{ $product->category }}</td>

                            <!-- Quantity -->
                            <td class="py-3 px-4">{{ $product->quantity }}</td>

                            <!-- Price -->
                            <td class="py-3 px-4 text-gray-900 font-bold">
                                {{ number_format($product->price, 2) }}
                            </td>

                            <!-- Status -->
                            <td class="py-3 px-4">
                                @if($product->quantity == 0)
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-red-500 rounded-full">Out of Stock</span>
                                @elseif($product->quantity <= 5)
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-yellow-500 rounded-full">Low Stock</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">Available</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Edit Button -->
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="text-blue-500 hover:text-blue-700 flex items-center transition-colors duration-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700 flex items-center transition-colors duration-200">
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        
        searchInput.addEventListener('keyup', function() {
            const searchQuery = this.value.toLowerCase();
            const rows = document.querySelectorAll('#inventoryList tr');
            
            rows.forEach(function(row) {
                if (row.querySelector('td:nth-child(2)')) {
                    const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const category = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    
                    if (productName.includes(searchQuery) || category.includes(searchQuery)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
