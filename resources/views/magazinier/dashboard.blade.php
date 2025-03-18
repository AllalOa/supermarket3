@extends('layouts.app-magazinier')

@section('title', 'Dashboard')

@section('content')
   <!-- Dashboard Content -->
   <div id="dashboard" class="content-section">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
       
      <a href="{{route('magazinier.inventory')}}">
 <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-[#6c757d]">Total Products</p>
              <p id="totalProducts" class="text-3xl font-bold text-[#2b2d42]">{{ $totalProducts }}</p>

            </div>
            <i class="fas fa-boxes text-2xl text-[#4361ee]"></i>
          </div>
        </div>

      </a>
      
     
        <a href="{{ route('magazinier.orders')}}"  rel="noopener noreferrer">
        <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-[#6c757d]">Pending Orders</p>
              <p class="text-3xl font-bold text-[#2b2d42]">{{ $pendingOrders }}</p>
            </div>
            <i class="fas fa-clipboard-list text-2xl text-[#4361ee]"></i>
          </div>
        </div>
        </a>

        <a href="{{route('magazinier.inventory')}}">

<div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer">
    <div class="flex justify-between items-center">
      <div>
        <p class="text-[#6c757d]">Low Stock Items</p>
        <p id="lowStock" class="text-3xl font-bold text-[#2b2d42]">{{ $lowStockProducts }}</p>
      </div>
      <i class="fas fa-exclamation-triangle text-2xl text-[#ff4d4d]"></i>
    </div>
  </div>
</a>
        
      </div>
    </div>
    <div id="notifications-container" class="fixed bottom-5 right-5 flex flex-col-reverse gap-3 z-50">
      <!-- Notifications will be dynamically added here -->
  </div>
  
  <!-- Audio element for notification sound -->
  <audio id="notification-sound" preload="auto">
      <!-- Direct URL to notification sound -->
      <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
      <!-- Fallback sound -->
      <source src="https://assets.mixkit.co/active_storage/sfx/1513/1513-preview.mp3" type="audio/mpeg">
  </audio>
  
  <!-- Load Pusher JS library -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  
  <!-- Load Laravel Echo (via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
  
  <script>
      // Initialize Echo with Pusher
      window.Echo = new Echo({
          broadcaster: 'pusher',
          key: '{{ env('PUSHER_APP_KEY') }}',
          cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
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
              
              // Play notification sound
              playNotificationSound();
              
              // Create notification element
              createNotification(data.message);
          });
      
      // Function to play notification sound
      function playNotificationSound() {
          const sound = document.getElementById('notification-sound');
          
          // Reset the audio to the beginning (in case it's already playing)
          sound.currentTime = 0;
          
          // Play the sound
          sound.play().catch(error => {
              // Handle any errors with playing the sound (e.g., user hasn't interacted with page yet)
              console.error('Error playing notification sound:', error);
          });
      }
          
      // Function to create and animate notifications
      function createNotification(message) {
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
          }, 5000);
          
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
@endsection