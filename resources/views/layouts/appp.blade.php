<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Supermarket Pro')</title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Alpine.js if not already included -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

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

    <!-- Sidebar -->
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

        <!-- Search Bar -->
        <div class="p-4">
            <div class="relative">
                <input type="text" placeholder="Search..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('supervisor.analytics') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('supervisor.analytics') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-chart-line w-5 mr-3"></i> Analytics
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.dashboard') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('supervisor.dashboard') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-home w-5 mr-3"></i> Dashboard
                    </a>
                </li>
                
                <li>
            <a href="{{ route('supervisor.foyers') }}"
                class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('supervisor.foyers') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-house-user w-5 mr-3"></i> Gestion des Foyers
            </a>
        </li>
                <li>
                    <a href="{{ route('add.cashier') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('add.cashier') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-user-plus w-5 mr-3"></i> Add Cashier
                        <span class="ml-auto px-2 py-1 text-xs bg-blue-500 text-white rounded-full">New</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('add.magazinier') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('add.magazinier') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-boxes w-5 mr-3"></i> Add Magazinier
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.inventory') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('supervisor.inventory') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-tasks w-5 mr-3"></i> Inventory
                        <span class="ml-auto px-2 py-1 text-xs bg-blue-500 text-white rounded-full">3</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('settings') }}"
                        class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('settings') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-cog w-5 mr-3"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Promotional Banner -->
        <div class="p-4 mt-8">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100">
                <p class="text-sm text-gray-700 mb-3">Unlock premium features with our Pro plan</p>
                <button
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 text-sm font-medium">Upgrade
                    Now</button>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-0 md:ml-64 transition-all duration-300 flex flex-col">
        <!-- Top Navigation -->
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
                    <span class="text-blue-600 font-medium">Dashboard</span>
                </div>

                <!-- Right side menu items -->
                <div class="flex items-center space-x-6">
                    <!-- Theme Switcher -->
                    <button class="text-gray-500 hover:text-blue-600 transition duration-300">
                        <i class="fas fa-moon text-lg"></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-500 hover:text-blue-600 transition duration-300 relative">
                            <i class="fas fa-bell text-lg"></i>
                            <span
                                class="notification-badge flex h-5 w-5 items-center justify-center bg-red-500 text-white text-xs rounded-full">3</span>
                        </button>
                    </div>

                    <!-- Messages -->


                    <!-- User Profile -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-3 focus:outline-none">
                            @if (Auth::check())
                                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('default-avatar.png') }}"
                                    alt="Profile Picture"
                                    class="w-10 h-10 rounded-xl object-cover border-2 border-gray-200">
                            @else
                                <img src="/api/placeholder/40/40" alt="User Profile"
                                    class="w-10 h-10 rounded-xl object-cover border-2 border-gray-200">
                            @endif
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium">{{ Auth::check() ? Auth::user()->name : 'John Doe' }}
                                </p>
                                <p class="text-xs text-gray-500">Supervisor</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
                        </button>

                        <!-- User Dropdown Menu -->
                        <div id="user-dropdown"
                            class="dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-40">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium">{{ Auth::check() ? Auth::user()->name : 'John Doe' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::check() ? Auth::user()->email : 'admin@example.com' }}</p>
                            </div>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-5 mr-2 text-gray-400"></i> Profile
                            </a>
                            <a href="#"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog w-5 mr-2 text-gray-400"></i> Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
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

        <!-- Yielded Content Area -->
        <div class="flex-1 p-6">
            <!-- Your existing content here -->
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 p-6 text-center mt-auto">
            <div class="text-gray-500 text-sm">
                &copy; 2025 Supermarket Pro. All rights reserved.
                <span class="mx-1">·</span>
                <a href="#" class="text-blue-500 hover:text-blue-700 transition duration-300">Terms</a>
                <span class="mx-1">·</span>
                <a href="#" class="text-blue-500 hover:text-blue-700 transition duration-300">Privacy</a>
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
    </script>


<div id="notifications-container" class="fixed bottom-5 right-5 flex flex-col-reverse gap-3 z-50">
  <!-- Notifications will be dynamically added here -->
</div>

<!-- Connection status indicator -->
<div id="connection-status"
  class="fixed bottom-5 left-5 p-2 rounded-lg bg-gray-800 text-white text-sm opacity-0 transition-opacity duration-300">
  Connecting...
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
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Echo with Pusher
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

  const supervisorId = {{ auth()->id() }};
  const supervisorChannel = window.Echo.private(`supervisor.${supervisorId}`);

  // Existing Zero Stock Alert Listener
  supervisorChannel.listen('.zero.stock.alert', (data) => {
      console.log("📢 Zero Stock Alert Received:", data);

      if (data && data.message && data.product) {
          createNotification(
              data.message,
              "Stock Alert",
              "zero_stock",
              data.product.id,
              data.product.name,
              data.product.quantity
          );
      }
  });

  // New Low Stock Alert Listener
  supervisorChannel.listen('.low.stock.alert', (data) => {
      console.log("📢 Low Stock Alert Received:", data);

      if (data && data.message && data.product) {
          createNotification(
              data.message,
              "Low Stock Alert",
              "low_stock",
              data.product.id,
              data.product.name,
              data.product.quantity
          );
      }
  });

  // Function to play notification sound
  function playNotificationSound() {
      const sound = document.getElementById('notification-sound');
      sound.currentTime = 0;
      sound.play().catch(console.warn);
  }

  // Function to create notifications
  function createNotification(message, title, type, id = null, productName = null, quantity = null) {
      playNotificationSound();

      const container = document.getElementById('notifications-container');
      const notification = document.createElement('div');

      // Notification styling based on type
      let borderColor, bgColor, iconClass, textColor;
      switch (type) {
          case 'zero_stock':
              borderColor = 'border-red-500';
              bgColor = 'bg-red-50';
              iconClass = 'fa-circle-exclamation';
              textColor = 'text-red-700';
              break;
          case 'low_stock':
              borderColor = 'border-yellow-500';
              bgColor = 'bg-yellow-50';
              iconClass = 'fa-triangle-exclamation';
              textColor = 'text-yellow-700';
              break;
          default:
              borderColor = 'border-blue-500';
              bgColor = 'bg-blue-50';
              iconClass = 'fa-circle-info';
              textColor = 'text-blue-700';
              break;
      }

      notification.className = `
          notification transform translate-x-full opacity-0
          p-4 rounded-lg border-l-4 shadow-lg
          flex items-center gap-3 max-w-sm
          transition-all duration-500
          ${borderColor} ${bgColor}
      `;

      // Additional info for stock alerts
      let additionalInfo = '';
      if (id !== null) {
          additionalInfo += `<p class="text-xs text-gray-600 mt-1">Product ID: ${id}</p>`;
          if (productName !== null) {
              additionalInfo += `<p class="text-xs text-gray-600 mt-1">${productName}</p>`;
          }
          if (quantity !== null) {
              additionalInfo += `<p class="text-xs text-gray-600">Current Stock: ${quantity}</p>`;
          }
      }

      notification.innerHTML = `
          <i class="fas ${iconClass} text-lg ${textColor}"></i>
          <div class="flex-1">
              <h4 class="font-semibold mb-1 ${textColor}">${title}</h4>
              <p class="text-gray-800 text-sm">${message}</p>
              ${additionalInfo}
          </div>
          <button onclick="removeNotification(this.parentElement)" 
                  class="text-gray-500 hover:text-gray-700 transition-colors">
              <i class="fas fa-times"></i>
          </button>
      `;

      container.appendChild(notification);
      
      // Animate in
      setTimeout(() => notification.classList.remove('translate-x-full', 'opacity-0'), 10);
      
      // Auto-remove after 10 seconds
      setTimeout(() => removeNotification(notification), 100000);
  }

  // Function to remove notifications
  function removeNotification(element) {
      element.classList.add('translate-x-full', 'opacity-0');
      setTimeout(() => element.remove(), 500);
  }
});
</script>

<style>
  /* CSS for notification borders (if you're not using Tailwind) */
  .notification-accepted-border {
      border-left-color: rgb(34, 197, 94);
  }
  .notification-rejected-border {
      border-left-color: rgb(239, 68, 68);
  }
  .notification-accepted-bg {
      background-color: rgb(240, 253, 244);
  }
  .notification-rejected-bg {
      background-color: rgb(254, 242, 242);
  }
  .notification-accepted-text {
      color: rgb(21, 128, 61);
  }
  .notification-rejected-text {
      color: rgb(185, 28, 28);
  }
</style>

    @stack('styles')
    @stack('scripts')
</body>

</html>
