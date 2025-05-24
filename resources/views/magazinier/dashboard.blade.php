@extends('layouts.app-magazinier')
@section('title', 'Dashboard')
@section('content')
<!-- Dashboard Content -->
<div id="dashboard" class="content-section py-6 px-4 md:px-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Warehouse Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Products Card -->
        <a href="{{route('magazinier.inventory')}}" class="block">
            <div class="bg-gradient-to-br from-blue-50 to-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 border border-blue-100 relative overflow-hidden">
                <div class="flex justify-between items-center">
                    <div class="relative z-10">
                        <p class="text-gray-600 font-medium mb-1">Total Products</p>
                        <p id="totalProducts" class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full relative z-10">
                        <i class="fas fa-boxes text-2xl text-blue-600"></i>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full -mr-8 -mt-8 opacity-30"></div>
            </div>
        </a>
        
        <!-- Pending Orders Card -->
        <a href="{{ route('magazinier.orders')}}" class="block">
            <div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 border border-purple-100 relative overflow-hidden">
                <div class="flex justify-between items-center">
                    <div class="relative z-10">
                        <p class="text-gray-600 font-medium mb-1">Pending Orders</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $pendingOrders }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full relative z-10">
                        <i class="fas fa-clipboard-list text-2xl text-purple-600"></i>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-100 rounded-full -mr-8 -mt-8 opacity-30"></div>
            </div>
        </a>
        
        <!-- Low Stock Items Card -->
        <a href="{{route('magazinier.inventory')}}" class="block">
            <div class="bg-gradient-to-br from-red-50 to-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-300 border border-red-100 relative overflow-hidden">
                <div class="flex justify-between items-center">
                    <div class="relative z-10">
                        <p class="text-gray-600 font-medium mb-1">Low Stock Items</p>
                        <p id="lowStock" class="text-3xl font-bold text-gray-800">{{ $lowStockProducts }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full relative z-10">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-24 h-24 bg-red-100 rounded-full -mr-8 -mt-8 opacity-30"></div>
            </div>
        </a>
    </div>
    
    <!-- Recent Activity Section -->
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            @if(isset($recentActivities) && count($recentActivities) > 0)
                @foreach($recentActivities as $activity)
                <div class="flex items-center gap-3 p-3 border-b border-gray-100">
                    <div class="flex-shrink-0">
                        @if($activity->type == 'order')
                            <div class="bg-purple-100 p-2 rounded-full">
                                <i class="fas fa-shopping-cart text-purple-500"></i>
                            </div>
                        @elseif($activity->type == 'inventory')
                            <div class="bg-blue-100 p-2 rounded-full">
                                <i class="fas fa-box text-blue-500"></i>
                            </div>
                        @else
                            <div class="bg-gray-100 p-2 rounded-full">
                                <i class="fas fa-history text-gray-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-800">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-gray-500 text-center py-4">No recent activities to display</p>
            @endif
        </div>
    </div>
    
    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Recent Orders Chart -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Orders</h2>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <!-- Replace with your actual chart -->
                <p class="text-gray-500">Order chart will appear here</p>
            </div>
        </div>
        
        <!-- Inventory Status -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Inventory Status</h2>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <!-- Replace with your actual chart -->
                <p class="text-gray-500">Inventory chart will appear here</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Access Buttons -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <a href="{{ route('magazinier.inventory') }}" class="flex items-center justify-center gap-2 bg-white p-4 rounded-xl shadow hover:shadow-md transition-all duration-300 border border-gray-100 text-center">
            <i class="fas fa-boxes text-blue-500"></i>
            <span class="font-medium text-gray-700">Manage Inventory</span>
        </a>
        <a href="{{ route('magazinier.orders') }}" class="flex items-center justify-center gap-2 bg-white p-4 rounded-xl shadow hover:shadow-md transition-all duration-300 border border-gray-100 text-center">
            <i class="fas fa-clipboard-list text-purple-500"></i>
            <span class="font-medium text-gray-700">View Orders</span>
        </a>
        <a href="#" class="flex items-center justify-center gap-2 bg-white p-4 rounded-xl shadow hover:shadow-md transition-all duration-300 border border-gray-100 text-center">
            <i class="fas fa-truck text-green-500"></i>
            <span class="font-medium text-gray-700">Shipments</span>
        </a>
        <a href="#" class="flex items-center justify-center gap-2 bg-white p-4 rounded-xl shadow hover:shadow-md transition-all duration-300 border border-gray-100 text-center">
            <i class="fas fa-file-alt text-yellow-500"></i>
            <span class="font-medium text-gray-700">Reports</span>
        </a>
    </div>
</div>
@endsection