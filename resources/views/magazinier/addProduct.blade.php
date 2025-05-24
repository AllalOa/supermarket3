@extends('layouts.app-magazinier')
@section('title', 'Add Product')

@section('content')
<div class="max-w-2xl mx-auto my-8">
    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="h-10 w-1 bg-blue-600 rounded-full mr-3"></div>
            <h2 class="text-2xl font-bold text-gray-800">Add a New Product</h2>
        </div>
        
        @if(session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-50 rounded-lg border border-green-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm4 0v12h4V4H7zm5 0a1 1 0 011-1h3a1 1 0 011 1v12a1 1 0 01-1 1h-3a1 1 0 01-1-1V4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="barcode" class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <input type="text" name="name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="" disabled selected>Select a category</option>
                        <option value="Food">Food</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Home">Home</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="quantity" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input type="number" name="price" step="0.01" min="0" class="pl-8 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input type="number" name="unit_price" step="0.01" min="0" class="pl-8 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Picture</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload a file</span>
                                <input id="file-upload" name="product_picture" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-4">
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg mr-3 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection