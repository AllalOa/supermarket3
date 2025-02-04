@extends('layouts.app-magazinier')



@section('title', 'Edit Product')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-semibold text-[#2b2d42] mb-6">Edit Product</h2>
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')  <!-- This is required to indicate an update request -->

            <div class="mb-4">
                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" id="product_name" name="name" value="{{ $product->name }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <input type="text" id="category" name="category" value="{{ $product->category }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="{{ $product->quantity }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" id="price" name="price" value="{{ $product->price }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price</label>
                <input type="number" id="unit_price" name="unit_price" value="{{ $product->unit_price }}" class="border p-2 w-full rounded">
            </div>

            <button type="submit" class="px-6 py-2 bg-[#4361ee] text-white rounded">Update Product</button>
        </form>
    </div>
@endsection
