<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Supermarket Pro')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#f4f6f9] font-[Poppins] min-h-screen flex">

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
  <a href="{{ route('supervisor.dashboard') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('supervisor.dashboard') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-home"></i> Dashboard
  </a>
</li>

<li>
  <a href="{{ route('add.cashier') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('add.cashier') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-user-plus"></i> Add Cashier
  </a>
</li>

<li>
  <a href="{{ route('add.magazinier') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('add.magazinier') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-boxes"></i> Add Magazinier
  </a>
</li>

<li>
  <a href="{{ route('inventory') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('inventory') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-tasks"></i> Inventory
  </a>
</li>

<li>
  <a href="{{ route('analytics') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('analytics') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-chart-line"></i> Analytics
  </a>
</li>

<li>
  <a href="{{ route('settings') }}" 
     class="flex items-center gap-3 px-4 py-3 rounded-lg text-white hover:bg-white/10 
     {{ Request::routeIs('settings') ? 'bg-[#4361ee]' : '' }}">
    <i class="fas fa-cog"></i> Settings
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
        <h1 class="text-2xl font-semibold text-[#2b2d42]">Welcome, Admin</h1>
        <p class="text-[#6c757d]">Last login: Today at 09:42 AM</p>
      </div>
      <div class="flex items-center gap-3">
        <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="User">
        <div>
          <h6 class="font-medium mb-0">John Doe</h6>
          <small class="text-[#6c757d]">Cashier</small>
        </div>
      </div>
    </div>

    <!-- Dynamic Content -->
    @yield('content')

  </div>

</body>
</html>
