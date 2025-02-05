@extends('layouts.app-magazinier')

@section('content')
<div class="container mx-auto p-6" x-data="{ showModal: false, selectedOrder: null }">
    <h2 class="text-2xl font-bold mb-4">Pending Orders</h2>
    
    <div class="bg-white shadow-md rounded-lg p-4">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Demandeur</th>
                    <th class="border border-gray-300 px-4 py-2">Products</th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border border-gray-300 cursor-pointer hover:bg-gray-200 transition"
                        @click="selectedOrder = {{ $order->toJson() }}; showModal = true">
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $order->id }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $order->cashier->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <ul class="list-disc list-inside">
                                @foreach ($order->details as $detail)
                                    <li class="text-gray-700">
                                        {{ $detail->product->name }} - <span class="font-bold">{{ $detail->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <div class="flex space-x-2 justify-center">
                                <!-- Accept Button -->
                                <form action="{{ route('magazinier.validateOrder', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition">
                                         Validate
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form action="{{ route('magazinier.rejectOrder', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition">
                                         Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Order Details Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" x-cloak x-transition>
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 relative">
            <!-- Close Button -->
            <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl" @click="showModal = false">
                ✖
            </button>
            
            <h2 class="text-xl font-bold mb-4">Order Details</h2>

            <div class="mb-4">
                <p><strong>Order ID:</strong> <span x-text="selectedOrder?.id"></span></p>
                <p><strong>Demandeur:</strong> <span x-text="selectedOrder?.cashier?.name"></span></p>
            </div>

            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Product</th>
                        <th class="border border-gray-300 px-4 py-2">Quantity</th>
                        <th class="border border-gray-300 px-4 py-2">Price</th>
                        <th class="border border-gray-300 px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="detail in selectedOrder.details">
                        <tr>
                            <td class="border border-gray-300 px-4 py-2" x-text="detail.product.name"></td>
                            <td class="border border-gray-300 px-4 py-2" x-text="detail.quantity"></td>
                            <td class="border border-gray-300 px-4 py-2" x-text="detail.product.price"></td>
                            <td class="border border-gray-300 px-4 py-2" x-text="(detail.quantity * detail.product.price).toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <p class="text-lg font-bold">Total: <span x-text="selectedOrder.details.reduce((sum, detail) => sum + (detail.quantity * detail.product.price), 0).toFixed(2)"></span> </p>
            </div>
        </div>
    </div>
</div>
@endsection
