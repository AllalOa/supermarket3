@extends('layouts.appp')

@section('title', 'Supervisor Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    {{-- 📌 Total Cashiers --}}
    <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer" 
         onclick="openModal('cashiers')">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[#6c757d]">Total Cashiers</p>
                <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalCashiers }}</p>
            </div>
            <i class="fas fa-user-tie text-2xl text-[#4361ee]"></i>
        </div>
    </div>

    {{-- 📌 Total Magaziniers --}}
    <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer" 
         onclick="openModal('magaziniers')">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[#6c757d]">Total Magaziniers</p>
                <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalMagaziniers }}</p>
            </div>
            <i class="fas fa-user-cog text-2xl text-[#4361ee]"></i>
        </div>
    </div>

    {{-- 📌 Total Products (Redirects to Inventory) --}}
    <a href="{{ route('supervisor.inventory') }}" class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[#6c757d]">Total Products</p>
                <p class="text-3xl font-bold text-[#2b2d42]">{{ $totalProducts }}</p>
            </div>
            <i class="fas fa-boxes text-2xl text-[#4361ee]"></i>
        </div>
    </a>

    {{-- 📌 Low Stock Items --}}
    <div class="bg-white p-6 rounded-xl shadow-sm transition-transform transform hover:scale-105 hover:shadow-md cursor-pointer" 
         onclick="openLowStockModal()">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[#6c757d]">Low Stock Items</p>
                <p class="text-3xl font-bold text-[#e63946]">{{ $lowStockItems }}</p>
            </div>
            <i class="fas fa-exclamation-triangle text-2xl text-[#ff4d4d]"></i>
        </div>
    </div>
</div>

{{-- 🔹 General Modal for Cashiers & Magaziniers --}}
<div id="generalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 id="generalModalTitle" class="text-xl font-semibold text-gray-800"></h2>
            <button onclick="closeGeneralModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
        </div>
        <div id="generalModalContent" class="max-h-96 overflow-y-auto">
            <p class="text-center text-gray-500">Loading...</p>
        </div>
    </div>
</div>

{{-- 🔹 Dedicated Modal for Low Stock Items --}}
<div id="lowStockModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-2/3 max-w-xl shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Low Stock Products</h2>
            <button onclick="closeLowStockModal()" class="text-gray-600 hover:text-gray-800 text-lg">✖</button>
        </div>
        <div id="lowStockModalContent" class="max-h-96 overflow-y-auto">
            <p class="text-center text-gray-500">Loading...</p>
        </div>
    </div>
</div>

{{-- ✅ JavaScript --}}
<script>

// General Modal (For Cashiers & Magaziniers)
function openModal(type) {
    let url, title;
    
    if (type === 'cashiers') {
        url = "{{ route('supervisor.cashiers') }}";
        title = "List of Cashiers";
    } else if (type === 'magaziniers') {
        url = "{{ route('supervisor.magaziniers') }}";
        title = "List of Magaziniers";
    }

    document.getElementById('generalModalTitle').innerText = title;
    document.getElementById('generalModal').classList.remove('hidden');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let content = `<table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-100">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Phone Number</th>
                    </tr>
                </thead>
                <tbody>`;

            data.forEach(item => {
                content += `
                    <tr class="border-b">
                        <td class="p-2">${item.id}</td>
                        <td class="p-2">${item.name}</td>
                        <td class="p-2">${item.phone}</td>
                    </tr>`;
            });

            content += `</tbody></table>`;
            document.getElementById('generalModalContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('generalModalContent').innerHTML = `<p class="text-red-500">Error loading data</p>`;
        });
}


function closeGeneralModal() {
    document.getElementById('generalModal').classList.add('hidden');
}

// Dedicated Modal for Low Stock Items
function openLowStockModal() {
    let url = "{{ route('supervisor.lowStockItems') }}";

    document.getElementById('lowStockModal').classList.remove('hidden');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let content = `<table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-100">
                        <th class="p-2">ID</th>
                        <th class="p-2">Product Name</th>
                        <th class="p-2">Quantity</th>
                    </tr>
                </thead>
                <tbody>`;

            data.forEach(item => {
                content += `
                    <tr class="border-b">
                        <td class="p-2">${item.id}</td>
                        <td class="p-2">${item.name}</td>
                        <td class="p-2 text-red-500">${item.quantity}</td>
                    </tr>`;
            });

            content += `</tbody></table>`;
            document.getElementById('lowStockModalContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('lowStockModalContent').innerHTML = `<p class="text-red-500">Error loading data</p>`;
        });
}

function closeLowStockModal() {
    document.getElementById('lowStockModal').classList.add('hidden');
}

</script>

@endsection
