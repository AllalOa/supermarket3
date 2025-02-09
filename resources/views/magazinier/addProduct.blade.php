@extends('layouts.app-magazinier')

@section('title', 'Add Product')






@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-xl mx-auto">
    <h2 class="text-2xl font-semibold text-[#2b2d42] mb-6">Add a New Product</h2>

    @if(session('success'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Barcode</label>
            <input type="text" name="barcode" class="border p-2 w-full rounded-lg" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="name" class="border p-2 w-full rounded-lg" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" class="border p-2 w-full rounded-lg">
                <option value="Food">Food</option>
                <option value="Beverages">Beverages</option>
                <option value="Electronics">Electronics</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" name="quantity" class="border p-2 w-full rounded-lg" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" name="price" step="0.01" class="border p-2 w-full rounded-lg" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Unit Price</label>
            <input type="number" name="unit_price" step="0.01" class="border p-2 w-full rounded-lg" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Product Picture</label>
            <input type="file" name="product_picture" accept="image/*" class="w-full px-4 py-2.5 border rounded-lg">
        </div>

        <button type="submit" class="w-full px-6 py-3 bg-[#4361ee] text-white rounded-lg font-medium hover:bg-[#2b2d42] transition">
            Add Product
        </button>
    </form>
</div>
@endsection


