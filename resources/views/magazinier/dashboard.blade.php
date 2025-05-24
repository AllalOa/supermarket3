@extends('layouts.app-magazinier')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Stock Management</h1>
                    <p class="mt-2 text-sm text-gray-600">Monitor your inventory and orders</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">
                        <i class="fas fa-clock mr-2"></i>{{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Products Card -->
            <a href="{{route('magazinier.inventory')}}" class="block group">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-200 group-hover:shadow-lg group-hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-xl text-white"></i>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Inventory
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-gray-500">Total Products</p>
                        <div class="mt-2 flex items-baseline">
                            <p id="totalProducts" class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                            <span class="ml-2 text-sm text-gray-500">items</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Pending Orders Card -->
            <a href="{{ route('magazinier.orders')}}" class="block group" rel="noopener noreferrer">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-200 group-hover:shadow-lg group-hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-xl text-white"></i>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            Orders
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-gray-500">Pending Orders</p>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                            <span class="ml-2 text-sm text-gray-500">orders</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Low Stock Items Card -->
            <a href="{{route('magazinier.inventory')}}" class="block group">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-200 group-hover:shadow-lg group-hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-xl text-white"></i>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Alert
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-gray-500">Low Stock Items</p>
                        <div class="mt-2 flex items-baseline">
                            <p id="lowStock" class="text-3xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
                            <span class="ml-2 text-sm text-gray-500">items</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="" 
                       class="group flex items-center p-4 rounded-xl border border-gray-200 hover:border-green-200 hover:bg-green-50 transition-all duration-200">
                        <div class="shrink-0 w-10 h-10 bg-gradient-to-br from-green-400 to-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700">Add Product</p>
                            <p class="text-xs text-gray-500 group-hover:text-green-600">Create new item</p>
                        </div>
                    </a>

                    <a href="{{ route('magazinier.inventory') }}" 
                       class="group flex items-center p-4 rounded-xl border border-gray-200 hover:border-blue-200 hover:bg-blue-50 transition-all duration-200">
                        <div class="shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-700">View Inventory</p>
                            <p class="text-xs text-gray-500 group-hover:text-blue-600">Check stock levels</p>
                        </div>
                    </a>

                    <a href="{{ route('magazinier.orders') }}" 
                       class="group flex items-center p-4 rounded-xl border border-gray-200 hover:border-purple-200 hover:bg-purple-50 transition-all duration-200">
                        <div class="shrink-0 w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-purple-700">Manage Orders</p>
                            <p class="text-xs text-gray-500 group-hover:text-purple-600">Process pending orders</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection