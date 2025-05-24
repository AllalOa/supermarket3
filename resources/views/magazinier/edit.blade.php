@extends('layouts.app-magazinier')

@section('title', 'Edit Product')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
            <p class="mt-1 text-sm text-gray-600">Update product information</p>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form id="editProductForm" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Product Image Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                            <div class="w-40 h-40 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                @if($product->product_picture)
                                    <img src="{{ asset('storage/' . $product->product_picture) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="product_picture" class="block text-sm font-medium text-gray-700 mb-2">Update Image</label>
                            <input type="file" 
                                   id="product_picture" 
                                   name="product_picture" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ $product->name }}" 
                                   required
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select id="category" 
                                    name="category" 
                                    required
                                    class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">Select a category</option>
                                <option value="Food" {{ $product->category == 'Food' ? 'selected' : '' }}>Food</option>
                                <option value="Beverages" {{ $product->category == 'Beverages' ? 'selected' : '' }}>Beverages</option>
                                <option value="Electronics" {{ $product->category == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (DA)</label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ $product->price }}" 
                                   step="0.01"
                                   required
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>

                        <!-- Unit Price -->
                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price (DA)</label>
                            <input type="number" 
                                   id="unit_price" 
                                   name="unit_price" 
                                   value="{{ $product->unit_price }}" 
                                   step="0.01"
                                   required
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ $product->quantity }}"
                                   required
                                   min="0" 
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('magazinier.inventory') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-800 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('editProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while we update the product',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Create FormData object
    const formData = new FormData(this);

    // Send AJAX request
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || 'Network response was not ok');
                }
                return data;
            });
        } else {
            throw new Error('Received non-JSON response from server');
        }
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Product updated successfully!',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("magazinier.inventory") }}';
                }
            });
        } else {
            throw new Error(data.message || 'Failed to update product');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong while updating the product. Please try again.',
            showConfirmButton: true
        }).then(() => {
            // Optionally reload the page to get a fresh state
            // window.location.reload();
        });
    });
});
</script>
@endpush
@endsection
