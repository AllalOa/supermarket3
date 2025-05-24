@extends('layouts.appp')

@section('title', 'Analytics')

@section('content')

    <!-- Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
    </style>

    <div class="p-6">
        <!-- Page Header -->


        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
     
     

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, Alex! Here's what's happening today.</p>
            </div>
            <div class="mt-4 md:mt-0 space-y-2 md:space-y-0 md:flex md:space-x-2">
                <button
                    class="w-full md:w-auto bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Add Widget
                </button>
                <button
                    class="w-full md:w-auto border border-gray-300 bg-white text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-download mr-2"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">







            <div class="card bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Sells (24h)</p>
                        <h2 class="text-3xl font-bold text-gray-900">
                            {{ number_format($orderstotal, 2) }} DA</h2>
                        <div class="flex items-center mt-2 text-sm">
                            <span class="text-green-500 font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>168%
                            </span>
                            <span class="text-gray-500 ml-2">vs last period</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="card bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Total Orders (Today)</p>
                        <h2 class="text-3xl font-bold text-gray-900">{{ $totalOrdersToday }}</h2>
                        <div class="flex items-center mt-2 text-sm">
                            <span class="text-green-500 font-medium flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>22%
                            </span>
                            <span class="text-gray-500 ml-2">vs last period</span>
                        </div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <i class="fas fa-shopping-cart text-xl text-green-500"></i>
                    </div>
                </div>
            </div>

            <div class="card bg-white rounded-lg shadow-sm p-6">


                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium mb-1">Avg Revenue (This month)</p>
                        <h2 class="text-3xl font-bold text-gray-900">45.200.10 DA</h2>
                        <div class="flex items-center mt-2 text-sm">
                            <span class="text-red-500 font-medium flex items-center">
                                <i class=""></i>
                            </span>
                            <span class="text-gray-500 ml-2"></span>
                        </div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <i class="fas fa-dollar-sign text-xl text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Animate progress bars on scroll
                const progressBars = document.querySelectorAll('[data-percent]');

                const animateProgress = (entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const progressBar = entry.target;
                            const percent = progressBar.getAttribute('data-percent');
                            progressBar.style.width = percent + '%';
                            observer.unobserve(entry.target);
                        }
                    });
                };

                const observer = new IntersectionObserver(animateProgress, {
                    threshold: 0.5
                });

                progressBars.forEach(bar => {
                    bar.style.width = '0%'; // Reset width before animation
                    observer.observe(bar);
                });
            });
        </script>

        <style>
            .card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            [data-percent] {
                transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .scale-percent {
                display: inline-block;
                min-width: 40px;
                text-align: right;
                font-weight: 600;
            }
        </style>


        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Cashier Performance</h3>
                        <p class="text-gray-500 text-sm">Sales metrics by cashier</p>
                    </div>
                    <div class="flex items-center space-x-2 mt-4 md:mt-0">
                        <form class="flex flex-col md:flex-row gap-2">
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit"
                                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition duration-300">
                                Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 400px">
                    <canvas id="cashierChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Left Half - Order Status Overview -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Order Status Overview</h3>
                            <p class="text-gray-500 text-sm">Order status distribution by cashier</p>
                        </div>
                        <select
                            class="border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option>Last 30 days</option>
                            <option>Last 7 days</option>
                            <option>Last 90 days</option>
                            <option>This year</option>
                        </select>
                    </div>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="height: 300px">
                        <canvas id="orderStatsChart"></canvas>
                    </div>
                    <div class="flex justify-end mt-4 space-x-3">
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-700 transition duration-300">
                            <i class="fas fa-file-alt mr-1"></i> Full Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Half - Weekly Performance -->

            <div x-data="orderModal()"> <!-- Move x-data here -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Recent Activities</h3>
                            <button @click="openAllActivitiesModal()"
                                class="text-blue-500 hover:text-blue-700 text-sm font-medium transition duration-300">
                                View All
                            </button>

                        </div>
                    </div>
                    <div class="p-5">
                        <div class="divide-y divide-gray-100">

                            @foreach ($activities as $activity)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center space-x-4">
                                        <!-- Profile Picture -->
                                        <img src="{{ $activity->user && $activity->user->profile_picture ? asset('storage/' . $activity->user->profile_picture) : asset('default-avatar.png') }}"
                                            alt="User"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 shadow-md">

                                        <!-- Activity Details -->
                                        <div class="flex-1">
                                            <p class="text-gray-800 text-sm font-medium">
                                                {{ $activity->user->name ?? 'System' }}
                                                <span class="font-normal text-gray-600"> {{ $activity->action }} </span>
                                            </p>
                                            <div class="flex items-center mt-1 text-gray-500 text-xs">
                                                <p>{{ $activity->created_at->diffForHumans() }}</p>

                                                <!-- Show "View Details" only if the activity type is "order" -->
                                                @if ($activity->model_type === 'App\Models\Order')
                                                    <span class="inline-block w-1 h-1 rounded-full bg-gray-300 mx-2"></span>
                                                    <button @click="openModal('{{ $activity->model_id }}')"
                                                        class="text-blue-500 font-medium hover:underline cursor-pointer">
                                                        View Details
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- All Activities Modal -->
                <div x-show="showAllActivitiesModal"
                    class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50"
                    x-transition.opacity>
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl" x-show="showAllActivitiesModal"
                        x-transition>
                        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">All Activities</h3>
                            <button @click="closeAllActivitiesModal()" class="text-gray-500 hover:text-gray-700">
                                ✖
                            </button>
                        </div>

                        <!-- Activity List -->
                        <div class="p-6 max-h-96 overflow-y-auto">
                            <template x-for="activity in allActivities" :key="activity.id">
                                <div class="py-4 border-b">
                                    <div class="flex items-center space-x-4">
                                        <img :src="activity.user?.profile_picture ? '/storage/' + activity.user.profile_picture :
                                            '/default-avatar.png'"
                                            alt="User"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 shadow-md">


                                        <div>
                                            <p class="text-gray-800 text-sm font-medium">
                                                <span x-text="activity.user?.name || 'System'"></span>
                                                <span class="font-normal text-gray-600" x-text="activity.action"></span>
                                            </p>
                                            <p class="text-gray-500 text-xs"
                                                x-text="new Date(activity.created_at).toLocaleString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>


                        <!-- Close Button -->
                        <div class="p-4 bg-gray-50 text-right">
                            <button @click="closeAllActivitiesModal()"
                                class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-300">
                                Close
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal (Hidden by Default) -->
                <!-- Modal -->
                <div x-show="showModal"
                    class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50"
                    x-transition.opacity>
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl" x-show="showModal" x-transition>
                        <!-- Invoice Header with Brand -->
                        <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <!-- Logo and Brand -->
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                                            F
                                        </div>
                                        <div class="ml-3">
                                            <h2 class="text-2xl font-bold text-gray-800">Foyer Name</h2>
                                            <p class="text-gray-500 text-sm">Service de restauration</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-2"
                                        x-text="'Tél: ' + (order?.cashier?.phone || '0550 123 456')"></p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg mb-3">
                                        <span class="font-bold">FACTURE</span>
                                    </div>
                                    <p class="text-gray-600 text-sm">N°: <span class="font-semibold"
                                            x-text="order?.id || '-'"></span></p>
                                    <p class="text-gray-600 text-sm">Date: <span class="font-semibold"
                                            x-text="new Date().toLocaleDateString('fr-DZ', {year: 'numeric', month: 'long', day: 'numeric'})"></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Client and Order Info -->
                        <div class="p-6 border-b border-gray-200 bg-gray-50">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="font-semibold text-gray-800 border-b pb-2 mb-2">Information Commande</h3>
                                    <p class="text-gray-600">Référence: <span class="font-medium"
                                            x-text="`CMD-${order?.id || '000'}`"></span></p>
                                    <p class="text-gray-600">Statut: <span
                                            class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium mt-1"
                                            x-text="order?.status || 'Complété'"></span></p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h3 class="font-semibold text-gray-800 border-b pb-2 mb-2">Information Caissier</h3>
                                    <p class="text-gray-600">Nom: <span class="font-medium"
                                            x-text="order?.cashier?.name || '-'"></span></p>
                                    <p class="text-gray-600">Email: <span class="font-medium"
                                            x-text="order?.cashier?.email || '-'"></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th
                                                class="text-left py-3 px-4 font-semibold text-gray-700 uppercase text-sm rounded-tl-lg">
                                                Article
                                            </th>
                                            <th
                                                class="text-center py-3 px-4 font-semibold text-gray-700 uppercase text-sm">
                                                Quantité
                                            </th>
                                            <th class="text-right py-3 px-4 font-semibold text-gray-700 uppercase text-sm">
                                                Prix
                                            </th>
                                            <th
                                                class="text-right py-3 px-4 font-semibold text-gray-700 uppercase text-sm rounded-tr-lg">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(detail, index) in order_details" :key="detail.id">
                                            <tr :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                                <td class="py-4 px-4 text-gray-800 font-medium"
                                                    x-text="detail.product.name"></td>
                                                <td class="py-4 px-4 text-center text-gray-600" x-text="detail.quantity">
                                                </td>

                                                <td class="py-4 px-4 text-right text-gray-600"
                                                    x-text="detail.product.price"></td>
                                                <td class="py-4 px-4 text-right font-medium text-gray-800"
                                                    x-text="`${(detail.quantity * detail.product.price).toFixed(2)} DA`">
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Section -->
                            <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex justify-end">
                                    <div class="w-64">
                                        <div class="flex justify-between py-2">
                                            <span class="font-semibold text-gray-700">Prix total:</span>
                                            <span class="font-semibold text-gray-800" x-text="`${total_price} DA`"></span>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-gray-50 rounded-b-lg border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    <p>Merci pour votre confiance!</p>
                                    <p>Récépissé N°: 000123</p>
                                </div>
                                <div class="flex space-x-3">
                                    <button
                                        class="flex items-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Imprimer
                                    </button>
                                    <button @click="closeModal()"
                                        class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-300">
                                        Fermer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('orderModal', () => ({
                            showModal: false,
                            showAllActivitiesModal: false,
                            order: null,
                            order_details: [],
                            total_price: 0,
                            allActivities: [],

                            // Open Order Modal
                            async openModal(orderId) {
                                try {
                                    console.log("Fetching order ID:", orderId);
                                    const url = `/orders/${orderId}`;
                                    console.log("Request URL:", url);

                                    // Prevent body scrolling
                                    document.body.style.overflow = 'hidden';

                                    const response = await fetch(url);
                                    console.log("Response status:", response.status);

                                    if (!response.ok) {
                                        const errorText = await response.text();
                                        console.error("Error response:", errorText);
                                        throw new Error(`Server error: ${errorText}`);
                                    }

                                    const rawData = await response.json();
                                    console.log("Data received:", JSON.stringify(rawData, null, 2));

                                    // Transform data
                                    const transformedData = {
                                        order: {
                                            id: rawData.id,
                                            status: rawData.status,
                                            created_at: rawData.created_at,
                                            cashier: {
                                                id: rawData.user?.id,
                                                name: rawData.user?.name || 'Unknown',
                                                email: rawData.user?.email || 'Unknown'
                                            }
                                        },
                                        order_details: [],
                                        total_price: 0
                                    };

                                    console.log("Transformed data:", transformedData);

                                    this.order = transformedData.order;
                                    this.order_details = [];
                                    this.total_price = "0.00";
                                    this.showModal = true;

                                    // Fetch order details
                                    try {
                                        const detailsResponse = await fetch(`/order-details/${orderId}`);
                                        console.log("Details response status:", detailsResponse.status);

                                        if (detailsResponse.ok) {
                                            const detailsData = await detailsResponse.json();
                                            console.log("Details data:", detailsData);

                                            this.order_details = detailsData;

                                            // Calculate total price
                                            this.total_price = detailsData.reduce((sum, item) => {
                                                return sum + ((item.product?.price || 0) * (item
                                                    .quantity || 0));
                                            }, 0).toFixed(2);

                                            console.log("Updated order details and total price:", {
                                                details: this.order_details,
                                                total: this.total_price
                                            });
                                        }
                                    } catch (detailsError) {
                                        console.error("Error fetching details:", detailsError);
                                    }

                                } catch (error) {
                                    console.error("Error:", error);
                                    alert('Error loading order details: ' + error.message);
                                }
                            },

                            // Close Order Modal
                            closeModal() {
                                this.showModal = false;
                                document.body.style.overflow = '';
                                this.order = null;
                                this.order_details = [];
                                this.total_price = 0;
                            },

                            // Open All Activities Modal
                            async openAllActivitiesModal() {
                                try {
                                    const response = await fetch('/all-activities');
                                    if (!response.ok) throw new Error('Failed to fetch activities');

                                    this.allActivities = await response.json();
                                    this.showAllActivitiesModal = true;
                                } catch (error) {
                                    console.error("Error fetching activities:", error);
                                }
                            },

                            // Close All Activities Modal
                            closeAllActivitiesModal() {
                                this.showAllActivitiesModal = false;
                            }
                        }));
                    });
                </script>


            </div>












            <!-- Top Products -->




            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <!-- Chart.js CDN -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

                // Chart.js Configuration Options
                const chartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Inter'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            boxPadding: 6,
                            usePointStyle: true,
                            boxWidth: 8,
                            font: {
                                family: 'Inter'
                            },
                            callbacks: {
                                labelPointStyle: function(context) {
                                    return {
                                        pointStyle: 'circle',
                                        rotation: 0
                                    };
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter'
                                }
                            }
                        },
                        y: {
                            grid: {
                                borderDash: [2, 4],
                                color: '#e5e7eb'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter'
                                }
                            }
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.3
                        },
                        point: {
                            radius: 2,
                            hoverRadius: 6
                        }
                    }
                };

                // Sales Chart
                const salesCtx = document.getElementById('salesChart').getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                                label: 'Revenue 2025',
                                data: [18500, 21000, 24000, 22000, 26500, 28000, 30000, 32000, 31000, 34000, 36000,
                                    38000
                                ],
                                borderColor: '#3b82f6',
                                backgroundColor: '#3b82f619',
                                borderWidth: 2,
                                fill: true
                            },
                            {
                                label: 'Revenue 2024',
                                data: [15000, 18000, 16500, 17500, 20000, 22000, 24000, 25000, 23000, 26000, 27000,
                                    29000
                                ],
                                borderColor: '#9ca3af',
                                backgroundColor: '#9ca3af19',
                                borderWidth: 2,
                                fill: true
                            }
                        ]
                    },
                    options: chartOptions
                });

                // Customer Chart
                const customerCtx = document.getElementById('customerChart').getContext('2d');
                new Chart(customerCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                                label: 'New Customers',
                                data: [120, 150, 180, 175, 200, 220, 240, 250, 270, 230, 260, 280],
                                backgroundColor: '#3b82f6',
                                borderRadius: 4
                            },
                            {
                                label: 'Returning Customers',
                                data: [80, 100, 110, 140, 160, 170, 180, 190, 200, 210, 190, 220],
                                backgroundColor: '#93c5fd',
                                borderRadius: 4
                            }
                        ]
                    },
                    options: chartOptions
                });
            </script>
            <!-- Code injected by live-server -->
            <script>
                // <![CDATA[  <-- For SVG support
                if ('WebSocket' in window) {
                    (function() {
                        function refreshCSS() {
                            var sheets = [].slice.call(document.getElementsByTagName("link"));
                            var head = document.getElementsByTagName("head")[0];
                            for (var i = 0; i < sheets.length; ++i) {
                                var elem = sheets[i];
                                var parent = elem.parentElement || head;
                                parent.removeChild(elem);
                                var rel = elem.rel;
                                if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() ==
                                    "stylesheet") {
                                    var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
                                    elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date()
                                        .valueOf());
                                }
                                parent.appendChild(elem);
                            }
                        }
                        var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
                        var address = protocol + window.location.host + window.location.pathname + '/ws';
                        var socket = new WebSocket(address);
                        socket.onmessage = function(msg) {
                            if (msg.data == 'reload') window.location.reload();
                            else if (msg.data == 'refreshcss') refreshCSS();
                        };
                        if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
                            console.log('Live reload enabled.');
                            sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
                        }
                    })();
                } else {
                    console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
                }
                // ]]>



                // Professional Thin Bar Chart
                const cashierCtx = document.getElementById('cashierChart').getContext('2d');
                new Chart(cashierCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($transactions->pluck('cashier_name')),
                        datasets: [{
                            label: 'Total Sales',
                            data: @json($transactions->pluck('total_sales')),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 0,
                            borderRadius: 2,
                            barThickness: 100,
                            categoryPercentage: 0.7,
                            hoverBackgroundColor: 'rgba(59, 130, 246, 1)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.96)',
                                titleColor: '#1f2937',
                                bodyColor: '#4b5563',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 5,
                                cornerRadius: 6,
                                usePointStyle: true,
                                boxWidth: 4,
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    title: () => '',
                                    label: (context) => {
                                        return ` ${context.dataset.label}: ${new Intl.NumberFormat('en-DZ', {
                                style: 'currency',
                                currency: 'DZD'
                            }).format(context.parsed.y)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6',
                                    borderDash: [4],
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6b7280',
                                    font: {
                                        size: 11
                                    },
                                    padding: 8,
                                    callback: function(value) {
                                        return new Intl.NumberFormat('en-DZ', {
                                            style: 'currency',
                                            currency: 'DZD',
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 800,
                            easing: 'easeOutQuart'
                        }
                    }
                });



                // Order Status Chart with thinner bars
                const orderStatsCtx = document.getElementById('orderStatsChart').getContext('2d');
                new Chart(orderStatsCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($orderStats->pluck('cashier_name')),
                        datasets: [{
                                label: 'Pending Orders',
                                data: @json($orderStats->pluck('pending_orders')),
                                backgroundColor: 'rgba(255, 159, 64, 0.9)',
                                borderColor: 'rgba(255, 159, 64, 1)',
                                borderWidth: 0,
                                borderRadius: 2,
                                barThickness: 20,
                                categoryPercentage: 0.6,
                                barPercentage: 0.8
                            },
                            {
                                label: 'Approved Orders',
                                data: @json($orderStats->pluck('approved_orders')),
                                backgroundColor: 'rgba(75, 192, 192, 0.9)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 0,
                                borderRadius: 2,
                                barThickness: 20,
                                categoryPercentage: 0.6,
                                barPercentage: 0.8
                            },
                            {
                                label: 'Rejected Orders',
                                data: @json($orderStats->pluck('rejected_orders')),
                                backgroundColor: 'rgba(255, 99, 132, 0.9)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 0,
                                borderRadius: 2,
                                barThickness: 20,
                                categoryPercentage: 0.6,
                                barPercentage: 0.8
                            }
                        ]
                    },
                    options: {
                        ...chartOptions,
                        indexAxis: 'x',
                        plugins: {
                            ...chartOptions.plugins,
                            tooltip: {
                                ...chartOptions.plugins.tooltip,
                                callbacks: {
                                    label: function(context) {
                                        return ` ${context.dataset.label}: ${context.parsed.y} orders`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: false,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    autoSkip: false,
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    color: '#f3f4f6',
                                    borderDash: [4]
                                }
                            }
                        },
                        elements: {
                            bar: {
                                borderSkipped: 'end',
                                borderRadius: 2
                            }
                        },
                        datasets: {
                            bar: {
                                maxBarThickness: 30,
                                categoryPercentage: 0.7,
                                barPercentage: 0.9
                            }
                        }
                    }
                });



                // Daily Trend Chart
                const dailyCtx = document.getElementById('dailyTrendChart').getContext('2d');
                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: @json($dailyOrders->pluck('order_date')),
                        datasets: [{
                            label: 'Total Daily Orders',
                            data: @json($dailyOrders->pluck('daily_total')),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    title: ([item]) => item.label,
                                    label: (context) => {
                                        return ` Total: ${new Intl.NumberFormat('en-DZ', {
                            style: 'currency',
                            currency: 'DZD'
                        }).format(context.parsed.y)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                },
                                title: {
                                    display: true,
                                    text: 'Total Order Amount'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('en-DZ', {
                                            style: 'currency',
                                            currency: 'DZD',
                                            maximumFractionDigits: 0
                                        }).format(value);
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x'
                        }
                    }
                });



                let chartInstance = null;

                function initChart() {
                    const ctx = document.getElementById('cashierdailyChart').getContext('2d');

                    // Generate colors
                    const colors = [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#EC4899', '#14B8A6', '#84CC16', '#F97316', '#64748B'
                    ];

                    // Prepare datasets
                    const datasets = [];
                    @foreach ($dailyOrders as $cashier => $data)
                        datasets.push({
                            label: '{{ $cashier }}',
                            data: [
                                @foreach ($dates as $date)
                                    @php
                                        $dailyTotal = $data->firstWhere('order_date', $date)->daily_total ?? 0;
                                    @endphp
                                    {{ $dailyTotal }},
                                @endforeach
                            ],
                            borderColor: colors[{{ $loop->index }} % colors.length],
                            backgroundColor: colors[{{ $loop->index }} % colors.length] + '20',
                            tension: 0.3,
                            fill: false,
                            borderWidth: 2
                        });
                    @endforeach

                    // Create or update chart
                    if (chartInstance) chartInstance.destroy();

                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: @json($dates),
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Total Amount (DA)'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return new Intl.NumberFormat('en-DZ', {
                                                style: 'currency',
                                                currency: 'DZD',
                                                maximumFractionDigits: 0
                                            }).format(value);
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${new Intl.NumberFormat('en-DZ', {
                                            style: 'currency',
                                            currency: 'DZD',
                                            maximumFractionDigits: 0
                                        }).format(context.parsed.y)}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                function updateChart() {
                    const week = document.getElementById('weekSelector').value;
                    const [year, month] = document.querySelector('input[type="month"]').value.split('-');

                    window.location.search = new URLSearchParams({
                        week: week,
                        year: year,
                        month: month
                    }).toString();
                }

                function updateMonth(value) {
                    const [year, month] = value.split('-');
                    window.location.search = new URLSearchParams({
                        week: 1,
                        year: year,
                        month: month
                    }).toString();
                }

                // Initialize chart
                document.addEventListener('DOMContentLoaded', initChart);
            </script>



            <div class="card bg-white w-full">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Top Products</h3>
                            <p class="text-gray-500 text-sm">Best performing products by revenue</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        @foreach ($productSales as $product)
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mr-4">
                                    @if ($product->product_picture)
                                        <img src="{{ asset('storage/' . $product->product_picture) }}"
                                            alt="{{ $product->product_name }}" class="w-12 h-12 rounded-xl">
                                    @else
                                        <i class="fas fa-box text-blue-600"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium">{{ $product->product_name }}</h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span class="dot-indicator bg-green-500"></span>
                                        <span>Sold: {{ $product->total_sales }} units</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold">{{ number_format($product->total_revenue, 2) }} DA</p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
        </div>
        
        
        @endsection
