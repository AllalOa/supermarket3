<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Pro - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        .low-stock {
            background-color: #fef3c7;
        }

        .critical-stock {
            background-color: #fee2e2;
        }
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
           {{ request()->routeIs('magazinier.dashboard') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-home w-5 text-center"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('magazinier.inventory') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('magazinier.inventory') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-boxes w-5 text-center"></i>
                        Manage Inventory
                    </a>
                </li>
                <li>
                    <a href="{{ route('addProduct') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('addProduct') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-plus-circle w-5 text-center"></i>
                        Add Product
                    </a>
                </li>
                <li>
                    <a href="{{ route('magazinier.orders') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('magazinier.orders') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <i class="fas fa-truck-loading w-5 text-center"></i>
                        Received Orders
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
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div></div>
    
                                <div class="ms-1">
                                    @if (Auth::check())
                                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('default-avatar.png') }}"
                                            alt="Profile Picture" class="w-14 h-14 rounded-full object-cover">
                                    @endif
                                </div>
                            </button>
                        </x-slot>
    
                        <x-slot name="content">
                            <x-dropdown-link>
    
                                <h6 class="font-medium mb-0">Hi,{{ Auth::user()->name }}</h6>
    
                            </x-dropdown-link>
                            <hr>
                            <x-dropdown-link>
                                {{ __('Profile') }}
                            </x-dropdown-link>
    
    
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
    
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                  this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                </div>
    
    
    
    
                    </x-slot>
                    </x-dropdown>
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
