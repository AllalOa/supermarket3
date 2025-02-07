@extends('layouts.app-cashier')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h2 class="text-2xl font-semibold text-gray-800 flex items-center mb-5">
        📦 Mes Commandes
    </h2>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div class="mt-4 mb-6 flex items-center justify-between bg-green-50 border border-green-400 text-green-800 p-4 rounded-lg shadow-sm">
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove();" class="text-green-800 hover:text-green-900 font-bold">
                ✖
            </button>
        </div>
    @endif

    {{-- ❌ Error Message --}}
    @if(session('error'))
        <div class="mt-4 mb-6 flex items-center justify-between bg-red-50 border border-red-400 text-red-800 p-4 rounded-lg shadow-sm">
            <span class="font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove();" class="text-red-800 hover:text-red-900 font-bold">
                ✖
            </button>
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                <tr>
                    <th class="p-4">Commande ID</th>
                    <th class="p-4">Produits</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-gray-800 text-sm">
                @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-semibold text-gray-900">#{{ $order->id }}</td>
                        <td class="p-4">
                            <ul class="list-none space-y-1">
                                @foreach($order->details as $detail)
                                    <li class="flex items-center">
                                        <span class="font-medium">{{ $detail->product->name }}</span>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded">
                                            x{{ $detail->quantity }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs font-semibold text-white rounded-full 
                                {{ $order->status == 'pending' ? 'bg-yellow-500' : ($order->status == 'approved' ? 'bg-green-600' : 'bg-red-500') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
