<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Pro</title>

    <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .group:hover .absolute {
            opacity: 1;
        }

        body {
            font-family: 'Inter', sans-serif;
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #93c5fd;
            --success: #10b981;
            --danger: #ef4444;
            --surface: #f9fafb;
        }

        .sidebar {
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .menu-item {
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background-color: #f3f4f6;
        }

        .menu-item.active {
            background-color: #eff6ff;
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
        }

        .dropdown {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
        }

        .dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 50;
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex">

    <!-- Sidebar - Styled like template 2 but keeping structure from template 1 -->
    <aside class="sidebar w-64 fixed h-screen bg-white shadow-xl z-30">
        <!-- Logo -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <i class="fas fa-store text-white"></i>
                </div>
                <span class="text-xl font-semibold text-gray-800">Supermarket Pro</span>
            </div>
        </div>

        <!-- Navigation Menu for Cashier - keeping original menu items but with new styling -->
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('cashier.dashboard') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('cashier.dashboard') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-cash-register w-5 mr-3"></i> POS System
                    </a>
                </li>
                <li>
                    <a href="{{ route('MakeAnOrder') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('MakeAnOrder') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-shopping-cart w-5 mr-3"></i> Make an Order
                        <span class="ml-auto px-2 py-1 text-xs bg-blue-500 text-white rounded-full">New</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cashier.orders') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('cashier.orders') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-history w-5 mr-3"></i> My Orders History
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="menu-item flex items-center p-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-cog w-5 mr-3"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>
       

   
        <!-- Promotional Banner -->
        <div class="p-4 mt-8">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100">
                <p class="text-sm text-gray-700 mb-3">Need help with transactions?</p>
                <button
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 text-sm font-medium">Quick
                    Guide</button>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-0 md:ml-64 transition-all duration-300 flex flex-col">
        <!-- Top Navigation - Styled like template 2 but keeping structure from template 1 -->
        <header class="bg-white sticky top-0 z-20 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Mobile Hamburger Menu -->
                <button id="sidebar-toggle" class="md:hidden text-gray-500 hover:text-blue-500 transition duration-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="hidden md:flex items-center space-x-2 text-sm">
                    <a href="#" class="text-gray-500 hover:text-blue-600">Home</a>
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="text-blue-600 font-medium">Cashier Dashboard</span>
                </div>

                <!-- Right side user profile - keeping original structure but with new styling -->
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <div class="relative inline-block">
                        @include('partials.cashier-notification-dropdown')
                    </div>
                    
                    <!-- User Profile -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-3 focus:outline-none">
                            @if (Auth::check())
                                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('default-avatar.png') }}"
                                    alt="Profile Picture"
                                    class="w-10 h-10 rounded-xl object-cover border-2 border-gray-200">
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Cashier' }}</p>
                                <p class="text-xs text-gray-500">Cashier</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div id="user-dropdown"
                            class="dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-40">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium">{{ Auth::user()->name ?? 'Cashier' }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'cashier@example.com' }}</p>
                            </div>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-5 mr-2 text-gray-400"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 p-6 text-center mt-auto">
            <div class="text-gray-500 text-sm">
                &copy; {{ now()->year }} Supermarket Pro. All rights reserved.
                <span class="mx-1">·</span>
                <a href="#" class="text-blue-500 hover:text-blue-700 transition duration-300">Support</a>
            </div>
        </footer>
    </main>

    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // User Dropdown Toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        userMenuButton.addEventListener('click', () => {
            userDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                userDropdown.classList.remove('show');
            }
        });

        // Notifications Dropdown Toggle
        const notificationsButton = document.getElementById('notifications-button');
        const notificationsDropdown = document.getElementById('notifications-dropdown');

        notificationsButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent event from bubbling up
            notificationsDropdown.classList.toggle('hidden');
        });

        // Close notifications dropdown when clicking outside
        document.addEventListener('click', (event) => {
            if (!notificationsButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });
    </script>
    

   <!-- Notifications Container -->
 
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  
  <!-- Load Laravel Echo (via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
  <div id="notifications-container" class="fixed bottom-5 right-5 flex flex-col-reverse gap-3 z-50">
    <!-- Notifications will be dynamically added here -->
</div>

<!-- Notification sound -->
<audio id="notification-sound" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>

<!-- Pusher and Echo -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script>
    // Initialize Pusher/Echo
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '7920bc1f6b143eecdd33',
        cluster: 'eu',
        encrypted: true,
        forceTLS: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    // Connection monitoring
    window.Echo.connector.pusher.connection.bind('connected', () => {
        console.log('Pusher connected successfully');
    });

    // Notification listener
    const cashierId = {{ auth()->id() }};
    window.Echo.private(`cashier.${cashierId}`)
        .listen('.order.status.updated', (data) => {
            console.log('Event received:', data);
            if (data && data.message) {
                createNotification(data.message, data.status, data.order_id);
            }
        });

 
 
        function playNotificationSound() {
        const sound = document.getElementById('notification-sound');
        sound.currentTime = 0;
        sound.play().catch(console.warn);
    }

    function createNotification(message, status, orderId) {
        playNotificationSound();
        const container = document.getElementById('notifications-container');
        
        // Debug the incoming status
        console.log("Notification status received:", status);
        
        // Make sure status exists and normalize to lowercase for comparison
        status = status ? status.toString().toLowerCase() : '';
        
        // Status configuration - check for exact match against "accepted"
        const isAccepted = status === 'approved';
        console.log("isAccepted:", isAccepted);
        
        const config = isAccepted ? {
            border: 'notification-accepted-border',
            bg: 'notification-accepted-bg',
            text: 'notification-accepted-text',
            icon: 'fa-circle-check',
            title: 'Order Accepted '
        } : {
            border: 'notification-rejected-border',
            bg: 'notification-rejected-bg',
            text: 'notification-rejected-text',
            icon: 'fa-circle-xmark',
            title: 'Order Rejected '
        };

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `
            notification transform translate-x-full opacity-0
            p-4 rounded-lg border-l-4 shadow-lg
            flex items-center gap-3 max-w-sm
            transition-all duration-500
            ${config.border} ${config.bg}
        `;

        notification.innerHTML = `
            <i class="fas ${config.icon} text-lg ${config.text}"></i>
            <div class="flex-1">
                <h4 class="font-semibold mb-1 ${config.text}">${config.title}</h4>
                <p class="text-gray-800 text-sm">${message}</p>
                <p class="text-xs text-gray-600 mt-1">Order #${orderId}</p>
            </div>
            <button onclick="removeNotification(this.parentElement)" 
                    class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => notification.classList.remove('translate-x-full', 'opacity-0'), 10);
        
        // Auto-remove after 5 seconds
        setTimeout(() => removeNotification(notification), 100000);
    }

    function removeNotification(element) {
        element.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => element.remove(), 500);
    }
</script>

<style>
    .notification {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    }

    /* Accepted Notification Styles */
    .notification-accepted-border { border-color: #22c55e; }
    .notification-accepted-bg { background-color: #f0fdf4; }
    .notification-accepted-text { color: #15803d; }

    /* Rejected Notification Styles */
    .notification-rejected-border { border-color: #ef4444; }
    .notification-rejected-bg { background-color: #fef2f2; }
    .notification-rejected-text { color: #b91c1c; }

    /* Animations */
    .notification {
        transition: 
            transform 0.5s cubic-bezier(0.4, 0, 0.2, 1),
            opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
            background-color 0.3s ease;
    }
</style>




    @stack('styles')
    @stack('scripts')
</body>

</html>
