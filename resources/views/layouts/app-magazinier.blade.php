<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Supermarket Pro - Magazinier')</title>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Include Alpine.js -->
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
    .low-stock { background-color: #fef3c7; }
    .critical-stock { background-color: #fee2e2; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex">

  <!-- Sidebar -->
  <aside class="sidebar w-64 fixed h-screen bg-white shadow-xl z-30">
    <!-- Logo -->
    <div class="p-5 border-b border-gray-100">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
          <i class="fas fa-store text-white"></i>
        </div>
        <span class="text-xl font-semibold text-gray-800">Supermarket Pro</span>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4">
      <ul class="space-y-1">
        <li>
          <a href="{{ route('magazinier.dashboard') }}" 
             class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('magazinier.dashboard') ? 'active' : 'text-gray-700' }}">
              <i class="fas fa-home w-5 mr-3"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="{{ route('magazinier.inventory') }}" 
             class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('magazinier.inventory') ? 'active' : 'text-gray-700' }}">
              <i class="fas fa-boxes w-5 mr-3"></i> Manage Inventory
          </a>
        </li>
        <li>
          <a href="{{ route('addProduct') }}" 
             class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('addProduct') ? 'active' : 'text-gray-700' }}">
              <i class="fas fa-plus-circle w-5 mr-3"></i> Add Product
          </a>
        </li>
        <li>
          <a href="{{ route('magazinier.orders') }}" 
             class="menu-item flex items-center p-3 text-sm font-medium {{ request()->routeIs('magazinier.orders') ? 'active' : 'text-gray-700' }}">
              <i class="fas fa-truck-loading w-5 mr-3"></i> Received Orders
          </a>
        </li>
      </ul>
    </nav>

    <!-- Promotional Banner -->
    <div class="p-4 mt-8">
      <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100">
        <p class="text-sm text-gray-700 mb-3">Need help? Contact support</p>
        <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 text-sm font-medium">Support Center</button>
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
          <a href="{{ route('magazinier.dashboard') }}" class="text-gray-500 hover:text-blue-600">Home</a>
          <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
          <span class="text-blue-600 font-medium">@yield('breadcrumb', 'Dashboard')</span>
        </div>
          
        <!-- Right side menu items -->
        <div class="flex items-center space-x-6">
          <!-- Notifications -->
          <div class="relative inline-block">
            @include('partials.magazinier-notification-dropdown')
          </div>
          
          <!-- User Profile -->
          <div class="relative">
            <button id="user-menu-button" class="flex items-center space-x-3 focus:outline-none">
              @if (Auth::check())
                <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('default-avatar.png') }}"
                  alt="Profile Picture" class="w-10 h-10 rounded-xl object-cover border-2 border-gray-200">
              @endif
              <div class="hidden md:block text-left">
                <p class="text-sm font-medium">{{ Auth::check() ? Auth::user()->name : 'Magazinier' }}</p>
                <p class="text-xs text-gray-500">Magazinier</p>
              </div>
              <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
            </button>
            
            <!-- User Dropdown Menu -->
            <div id="user-dropdown" class="dropdown absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-40">
              <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-sm font-medium">{{ Auth::check() ? Auth::user()->name : 'Magazinier' }}</p>
                <p class="text-xs text-gray-500">{{ Auth::check() ? Auth::user()->email : 'magazinier@example.com' }}</p>
              </div>
              <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                <i class="fas fa-user w-5 mr-2 text-gray-400"></i> Profile
              </a>
              <div class="border-t border-gray-100 my-1"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
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
        &copy; {{ date('Y') }} Supermarket Pro. All rights reserved.
        <span class="mx-1">·</span>
        <a href="#" class="text-blue-500 hover:text-blue-700 transition duration-300">Contact Support</a>
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
     <div id="notifications-container" class="fixed bottom-5 right-5 flex flex-col-reverse gap-3 z-50">
      <!-- Notifications will be dynamically added here -->
  </div>
  
  <!-- Audio element for notification sound -->
 <!-- Notification sound -->
<audio id="notification-sound" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
  
  <!-- Load Pusher JS library -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  
  <!-- Load Laravel Echo (via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
  
  <script>
  


      // Initialize Echo with Pusher
      window.Echo = new Echo({
          broadcaster: 'pusher',
          key: '7920bc1f6b143eecdd33',
          cluster: 'eu',
          encrypted: true
      });
  
      // Debug connection status
      window.Echo.connector.pusher.connection.bind('connected', () => {
          console.log('Successfully connected to Pusher!');
      });
  
      window.Echo.connector.pusher.connection.bind('error', (error) => {
          console.error('Pusher connection error:', error);
      });
  
      // Listen for notifications
      const userId = {{ auth()->id() }};
      console.log('Listening on channel: magazinier.' + userId);
      
      window.Echo.channel(`magazinier.${userId}`)
          .listen('.App\\Events\\NewOrderNotification', (data) => {
              console.log('Received notification:', data);
      
              
              // Create notification element
              createNotification(data.message);
          });
      
      
          // Function to play notification sound
    function playNotificationSound() {
          const sound = document.getElementById('notification-sound');
          
        sound.currentTime = 0;
        sound.play().catch(console.warn);
  
      }  
      // Function to create and animate notifications
      function createNotification(message) {
        playNotificationSound();
          // Create container
          const notification = document.createElement('div');
          notification.className = 'notification bg-white shadow-lg rounded-lg p-4 border-l-4 border-blue-500 transform translate-x-full opacity-0 transition-all duration-500 flex gap-3 items-start max-w-sm';
          
          // Create icon
          const icon = document.createElement('div');
          icon.className = 'text-blue-500 mt-1';
          icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>`;
          
          // Create content
          const content = document.createElement('div');
          content.className = 'flex-1';
          
          const title = document.createElement('h4');
          title.className = 'font-semibold text-gray-800 mb-1';
          title.textContent = 'New Order';
          
          const messageEl = document.createElement('p');
          messageEl.className = 'text-gray-600 text-sm';
          messageEl.textContent = message;
          
          content.appendChild(title);
          content.appendChild(messageEl);
          
          // Create close button
          const closeBtn = document.createElement('button');
          closeBtn.className = 'text-gray-400 hover:text-gray-600 transition-colors';
          closeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>`;
          
          // Assemble notification
          notification.appendChild(icon);
          notification.appendChild(content);
          notification.appendChild(closeBtn);
          
          // Add to container
          const container = document.getElementById('notifications-container');
          container.appendChild(notification);
          
          // Animate in (small delay to ensure the DOM has updated)
          setTimeout(() => {
              notification.classList.remove('translate-x-full', 'opacity-0');
          }, 10);
          
          // Set up auto-removal timer
          const timeoutId = setTimeout(() => {
              removeNotification(notification);
          }, 100000);
          
          // Setup close button functionality
          closeBtn.addEventListener('click', () => {
              clearTimeout(timeoutId);
              removeNotification(notification);
          });
      }
      
      // Function to remove notification with animation
      function removeNotification(notification) {
          notification.classList.add('opacity-0', 'translate-x-full');
          
          // Remove from DOM after animation completes
          setTimeout(() => {
              notification.remove();
          }, 500);
      }
      
      // For testing purposes, uncomment to simulate notifications
      /*
      setInterval(() => {
          createNotification("Test notification " + new Date().toLocaleTimeString());
      }, 3000);
      */
  </script>

  <style>
      .notification {
          transition: transform 0.5s ease-out, opacity 0.5s ease-out;
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
      }
  </style>
  @stack('styles')
  @stack('scripts')
</body>
</html>