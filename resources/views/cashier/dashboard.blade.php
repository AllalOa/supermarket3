@extends('layouts.app-cashier')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Cashier Dashboard - Supermarket Pro</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Tailwind CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            /* Custom Styles */
            :root {
                --primary: #3b82f6;
                /* Bleu vif */
                --primary-light: #60a5fa;
                --primary-dark: #2563eb;
                --secondary: #10b981;
                /* Vert (pour les accents) */
                --danger: #ef4444;
                /* Rouge */
                --success: #10b981;
                /* Vert */
                --warning: #f59e0b;
                /* Orange */
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --white: #ffffff;
            }

            .card {
                background-color: var(--white);
                border-radius: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .card-header {
                background-color: var(--primary);
                padding: 1rem 1.5rem;
                color: var(--white);
                font-size: 1.25rem;
                font-weight: 600;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .quick-action-btn {
                padding: 1rem;
                background-color: var(--gray-100);
                border-radius: 0.5rem;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .quick-action-btn:hover {
                background-color: var(--gray-200);
                transform: translateY(-2px);
            }

            .quick-action-btn i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
                color: var(--primary);
            }

            .transaction-item {
                display: flex;
                justify-content: space-between;
                padding: 1rem;
                border-bottom: 1px solid var(--gray-200);
                transition: background-color 0.2s ease;
            }

            .transaction-item:hover {
                background-color: var(--gray-100);
            }

            .transaction-item:last-child {
                border-bottom: none;
            }
        </style>
    </head>

    <body class="bg-gray-100">
        <div class="min-h-screen flex flex-col">


            <!-- Main Content -->
            <main class="flex-1 container mx-auto p-6">
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="quick-action-btn">
                        <i class="fas fa-cash-register"></i>
                        <a href="{{ route('new.sale') }}"> Make a New Sale</a>
                    </div>
                    <div class="quick-action-btn" onclick="viewAllTransactions()">
                        <i class="fas fa-history"></i>
                        <a href="">
                            <p>View All</p>
                        </a>
                    </div>
                    <div class="quick-action-btn" onclick="scanProduct()">
                        <i class="fas fa-barcode"></i>
                        <a href="">scan produit</a>
                    </div>
                    <div class="quick-action-btn" onclick="priceCheck()">
                        <i class="fas fa-tag"></i>
                        <p>Price Check</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Today's Sales -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Today's Sales </h2>
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="p-6">
                            @if (isset($somme))
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($somme, 2, ',', ' ') }} DA</p>
                            @else
                                <p class="text-red-500">Aucune transaction trouvée.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Items Sold -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Items Sold</h2>
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="p-6">
                            <p class="text-3xl font-bold text-gray-800">357</p>

                        </div>
                    </div>

                    <!-- Customers -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Customers</h2>
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="p-6">
                            <p class="text-3xl font-bold text-gray-800">124</p>

                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="card">
                    <div class="card-header">
                        <h2>Recent Transactions</h2>
                        <a href="#" class="text-sm text-white hover:text-gray-200">View All</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Transaction Item -->
                            @foreach ($transactions as $transaction)
                                <div class="transaction-item">
                                    <div>
                                        <p class="font-medium text-gray-800"> {{ $transaction->bill->id ?? 'Non défini' }}
                                        </p>
                                        <p class="text-sm text-gray-500"> {{ $transaction->created_at }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-primary">Total:
                                            {{ number_format($transaction->total, 2, ',', ' ') }}</p>
                                        <p class="text-sm text-gray-500">Cash</p>
                                    </div>
                                </div>
                            @endforeach



                        </div>
                    </div>
                </div>
            </main>
        </div>







    </body>

    </html>
@endsection
