@extends('layouts.app-magazinier')

@section('title', 'Inventory Management')

@section('content')
<!-- Inventory Content -->
<div id="inventory" class="content-section py-8 px-4 md:px-6 bg-gray-50">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Inventory Management</h1>
            <p class="mt-2 text-gray-600">Manage and track your warehouse products</p>
        </div>
        
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="#" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow transition-all duration-300 flex items-center font-medium">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
            <a href="#" 
               class="bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 px-5 py-2.5 rounded-lg shadow-sm transition-all duration-300 flex items-center font-medium">
                <i class="fas fa-file-export mr-2"></i> Export
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
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" id="searchInput" placeholder="Search products..." 
                               class="pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 w-full md:w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                    </div>
                    
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
        
        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Image</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Product Details</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Category</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Inventory</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Price</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Status</th>
                        <th class="py-4 px-6 text-left font-semibold text-gray-600 uppercase text-xs tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="inventoryList" class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                            <!-- Product Image -->
                            <td class="py-4 px-6">
                                <div class="h-16 w-16 rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-white">
                                    @if($product->product_picture)
                                        <img src="{{ asset('storage/' . $product->product_picture) }}" 
                                            alt="{{ $product->name }}" 
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Product Details -->
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500 mt-1">SKU: #{{ $product->id }}</div>
                            </td>

                            <!-- Category -->
                            <td class="py-4 px-6">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1.5 rounded-full inline-flex items-center">
                                    <i class="fas fa-tag mr-1.5 text-indigo-600"></i>
                                    {{ $product->category }}
                                </span>
                            </td>

                            <!-- Inventory Details -->
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    @if($product->quantity > 8)
                                        <div class="w-12 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                                        </div>
                                    @elseif($product->quantity > 0)
                                        <div class="w-12 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($product->quantity / 10) * 100 }}%"></div>
                                        </div>
                                    @else
                                        <div class="w-12 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-red-500 h-2 rounded-full" style="width: 5%"></div>
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $product->quantity }}</span>
                                    <span class="text-xs text-gray-500 ml-1">units</span>
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ number_format($product->price, 2) }}</div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                @if($product->quantity == 0)
                                    <span class="px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-full shadow-sm inline-flex items-center">
                                        <i class="fas fa-times-circle mr-1.5"></i> Out of Stock
                                    </span>
                                @elseif($product->quantity <= 5)
                                    <span class="px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-full shadow-sm inline-flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1.5"></i> Low Stock
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 text-xs font-medium text-white bg-green-500 rounded-full shadow-sm inline-flex items-center">
                                        <i class="fas fa-check-circle mr-1.5"></i> In Stock
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg p-2 transition-colors duration-200">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10">
                                            <a href="{{ route('products.edit', $product->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                <i class="fas fa-edit mr-2"></i> Edit Product
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                <i class="fas fa-clone mr-2"></i> Duplicate
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash-alt mr-2"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-800 bg-indigo-100 hover:bg-indigo-200 p-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-indigo-100 p-6 rounded-full mb-4">
                                        <i class="fas fa-box-open text-4xl text-indigo-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800">No products found</h3>
                                    <p class="text-gray-500 text-sm mt-2 max-w-md mx-auto">Your inventory is currently empty. Start adding products to track and manage your inventory.</p>
                                    <a href="{{ route('products.create') }}" class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow transition-all duration-300 flex items-center font-medium">
                                        <i class="fas fa-plus mr-2"></i> Add Your First Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer with Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium">{{ count($products) > 0 ? 1 : 0 }}</span> to 
                <span class="font-medium">{{ count($products) }}</span> of 
                <span class="font-medium">{{ count($products) }}</span> products
            </div>
            
            <!-- Pagination -->
            @if(isset($products) && method_exists($products, 'links') && $products->hasPages())
                <div>
                    {{ $products->links() }}
                </div>
            @else
                <div class="flex justify-center space-x-1">
                    <button disabled class="px-3 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button class="px-3 py-1 rounded-md bg-indigo-600 text-white">1</button>
                    <button disabled class="px-3 py-1 rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition-transform hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-xl mr-4">
                    <i class="fas fa-boxes text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ count($products) }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                    View All Products
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- In Stock -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition-transform hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-xl mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">In Stock Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->where('quantity', '>', 5)->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium flex items-center">
                    View In Stock
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- Low Stock -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition-transform hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-xl mr-4">
                    <i class="fas fa-exclamation-circle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Low Stock Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->where('quantity', '>', 0)->where('quantity', '<=', 5)->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="#" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium flex items-center">
                    View Low Stock
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- Out of Stock -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 transition-transform hover:shadow-lg">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-xl mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Out of Stock Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $products->where('quantity', 0)->count() }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="#" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                    View Out of Stock
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
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