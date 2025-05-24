@extends('layouts.app-magazinier')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ showModal: false, selectedOrder: null }">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="h-10 w-1 bg-blue-600 rounded-full mr-3"></div>
            <h2 class="text-2xl font-bold text-gray-800">Pending Orders</h2>
        </div>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">
            {{ count($orders) }} orders awaiting action
        </span>
    </div>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="mb-6 flex items-center justify-between bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    {{-- ❌ Error Message --}}
    @if(session('error'))
        <div class="mb-6 flex items-center justify-between bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-700 hover:text-red-900 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif
    
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Demandeur</th>
                        <th class="px-6 py-3">Products</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-blue-50 transition-colors duration-150 ease-in-out cursor-pointer"
                            @click="selectedOrder = {{ $order->toJson() }}; showModal = true">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                        {{ substr($order->cashier->name, 0, 1) }}
                                    </div>
                                    <span>{{ $order->cashier->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <ul class="space-y-1">
                                    @foreach ($order->details as $detail)
                                        <li class="flex items-center text-gray-700">
                                            <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                            </svg>
                                            {{ $detail->product->name }} 
                                            <span class="ml-1 px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full">
                                                x{{ $detail->quantity }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center" @click.stop>
                                <div class="flex space-x-3 justify-center">
                                    <form action="{{ route('orders.approve', $order->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="flex items-center bg-green-500 text-white px-3 py-1.5 rounded-lg hover:bg-green-600 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Validate
                                        </button>
                                    </form>

                                    <form action="{{ route('orders.reject', $order->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="flex items-center bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-600 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if(count($orders) == 0)
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 font-medium">No pending orders</p>
                                <p class="mt-1">All orders have been processed.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" x-cloak @click.self="showModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 overflow-hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Order Details</h2>
                <button class="text-gray-400 hover:text-gray-600 focus:outline-none" @click="showModal = false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-sm text-gray-500">Order ID</p>
                        <p class="text-lg font-semibold text-gray-800" x-text="'#' + selectedOrder?.id"></p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <p class="text-sm text-gray-500">Demandeur</p>
                        <p class="text-lg font-semibold text-gray-800" x-text="selectedOrder?.cashier?.name"></p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="px-4 py-3 border-b">Product</th>
                                <th class="px-4 py-3 border-b text-center">Quantity</th>
                                <th class="px-4 py-3 border-b text-right">Price</th>
                                <th class="px-4 py-3 border-b text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-for="detail in selectedOrder?.details" :key="detail.id">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700" x-text="detail.product.name"></td>
                                    <td class="px-4 py-3 text-sm text-gray-700 text-center">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs" x-text="detail.quantity"></span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 text-right" x-text="'$' + parseFloat(detail.product.price).toFixed(2)"></td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right" x-text="'$' + parseFloat(detail.quantity * detail.product.price).toFixed(2)"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="3" class="px-4 py-3 text-base font-semibold text-gray-900 text-right">Total</td>
                                <td class="px-4 py-3 text-base font-bold text-gray-900 text-right">
                                    <span x-text="'$' + (selectedOrder?.details?.reduce((sum, detail) => sum + (detail.quantity * detail.product.price), 0).toFixed(2) || '0.00')"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection