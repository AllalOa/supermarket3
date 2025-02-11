<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supermarket Pro</title>

<link href="{{ asset('build/assets/app.css') }}" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <a href="{{ route('cashier.dashboard')}}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('cashier.dashboard') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white transition-all' }}">
            <i class="fas fa-cash-register w-5 text-center"></i>
            POS System
        </a>
    </li>
    <li>
        <a href="{{ route('MakeAnOrder')}}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('MakeAnOrder') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white transition-all' }}">
            <i class="fas fa-shopping-cart w-5 text-center"></i>
            Make an Order
        </a>
    </li>
    <li>
        <a href="{{ route('cashier.orders') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg 
           {{ request()->routeIs('cashier.orders') ? 'bg-[#4361ee] text-white shadow-md pointer-events-none' : 'text-white/80 hover:bg-white/10 hover:text-white transition-all' }}">
            <i class="fas fa-history w-5 text-center"></i>
            My Orders History
        </a>
    </li>
    <li>
        <a href="#" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition-all">
            <i class="fas fa-cog w-5 text-center"></i>
            Settings
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
        <h1 class="text-2xl font-semibold text-[#2b2d42]">Cashier Dashboard</h1>
        <p class="text-[#6c757d]">Ready to process sales</p>
      </div>
      <div class="flex items-center gap-3">
        @if(Auth::check()) 
    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('default-avatar.png') }}" 
         alt="Profile Picture" 
         class="w-14 h-14 rounded-full object-cover">
@endif

   
        <div>
          <h6 class="font-medium mb-0">{{ Auth::user()->name }}</h6>
          <small class="text-[#6c757d]">Cashier</small>
        </div>
      </div>
    </div>

    <!-- Content Section -->
    @yield('content')
    
   
  </div>
  @stack('styles')
  @stack('scripts')
</body>
</html>
