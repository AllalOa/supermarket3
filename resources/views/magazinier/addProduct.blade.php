@extends('layouts.app-magazinier')
@section('title', 'Add Product')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="mt-2 text-sm text-gray-600">Fill in the product details below</p>
        </div>

        <!-- Add Product Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 sm:p-8">
                <form id="addProductForm" action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Product Image Upload Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-md">
                                <label class="flex flex-col items-center px-4 py-6 bg-gray-50 rounded-xl border-2 border-gray-200 border-dashed cursor-pointer hover:bg-gray-100 transition-colors">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <span class="text-sm font-medium text-gray-600">Drop your product image here</span>
                                        <span class="text-xs text-gray-500 mt-1">or click to browse</span>
                                    </div>
                                    <input type="file" name="product_picture" accept="image/*" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Barcode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-barcode text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="barcode" 
                                           required 
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors"
                                           placeholder="Enter barcode">
                                </div>
                            </div>

                            <!-- Product Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="name" 
                                           required 
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors"
                                           placeholder="Enter product name">
                                </div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tags text-gray-400"></i>
                                    </div>
                                    <select name="category" 
                                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors appearance-none">
                                        <option value="">Select a category</option>
                                        <option value="Food">Food</option>
                                        <option value="Beverages">Beverages</option>
                                        <option value="Electronics">Electronics</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-cubes text-gray-400"></i>
                                    </div>
                                    <input type="number" 
                                           name="quantity" 
                                           required 
                                           min="0"
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors"
                                           placeholder="Enter quantity">
                                </div>
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price (DA) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <input type="number" 
                                           name="price" 
                                           required 
                                           step="0.01"
                                           min="0"
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors"
                                           placeholder="Enter price">
                                </div>
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price (DA) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-money-bill text-gray-400"></i>
                                    </div>
                                    <input type="number" 
                                           name="unit_price" 
                                           required 
                                           step="0.01"
                                           min="0"
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-colors"
                                           placeholder="Enter unit price">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t">
                        <a href="{{ route('magazinier.inventory') }}" 
                           class="px-6 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-800 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add this at the end of your content section -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addProductForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Product added successfully',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("magazinier.inventory") }}';
                    }
                });
            } else {
                throw new Error(data.message || 'Failed to add product');
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Something went wrong',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
@endpush
@endsection