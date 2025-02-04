<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Pro - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .low-stock { background-color: #fef3c7; }
        .critical-stock { background-color: #fee2e2; }
    </style>
</head>
<body class="bg-gray-200 font-[Poppins] min-h-screen flex">

    <!-- Sidebar -->
    <div class="w-[280px] fixed h-screen bg-gradient-to-br from-[#2b2d42] to-[#1a1b27] text-white shadow-xl p-6">
        <div class="border-b border-white/10 pb-4 mb-6">
            <h4 class="text-xl font-semibold tracking-wide flex items-center gap-2">
                <i class="fas fa-store"></i>
                Supermarket Pro
            </h4>
        </div>
        <nav>
        <ul class="space-y-2">
    <li>
        <a href="{{ route('magazinier.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('magazinier.dashboard') ? 'bg-[#4361ee] text-white shadow-md' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-home w-5 text-center"></i>
            Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('magazinier.inventory') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('magazinier.inventory') ? 'bg-[#4361ee] text-white shadow-md' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-boxes w-5 text-center"></i>
            Manage Inventory
        </a>
    </li>
    <li>
        <a href="{{ route('addProduct') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('addProduct') ? 'bg-[#4361ee] text-white shadow-md' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-plus-circle w-5 text-center"></i>
            Add Product
        </a>
    </li>
    <li>
        <a href="{{ route('receiveOrder') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('receiveOrder') ? 'bg-[#4361ee] text-white shadow-md' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-truck-loading w-5 text-center"></i>
            Receive Orders
        </a>
    </li>
</ul>

        </nav>
    </div>

    <!-- Main Content -->
    <div class="ml-[280px] flex-1 p-8">
        <!-- Navbar -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-[#2b2d42]">Welcome, Magazinier</h1>
                <p class="text-[#6c757d]">Last login: Today at 09:42 AM</p>
            </div>
            <div class="flex items-center gap-3">
                <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="User">
                <div>
                    <h6 class="font-medium mb-0">John Doe</h6>
                    <small class="text-[#6c757d]">Magazinier</small>
                </div>
            </div>
        </div>

        <!-- Dynamic Content -->
        @yield('content')

        <!-- Footer -->
        <div class="text-center text-[#6c757d] mt-8">
            <p>&copy; 2024 Supermarket Pro. All rights reserved</p>
        </div>
    </div>

</body>
</html>
