<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<div class="relative" x-data="{ 
    open: false, 
    notifications: [], 
    unreadCount: 0,
    formatTimeAgo(datetime) {
        return moment(datetime).fromNow();
    },
    fetchNotifications() {
        fetch('{{ route('magazinier.notifications.pending-orders') }}')
            .then(response => response.json())
            .then(data => {
                this.notifications = data.pendingOrders;
                this.unreadCount = data.pendingCount;
            });
    }
}" 
@click.away="open = false"
x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)"
>
    <!-- Notification Bell Button -->
    <button 
        @click="open = !open; if (open) fetchNotifications();" 
        class="relative p-2 rounded-full hover:bg-gray-100 transition-colors duration-200"
        :class="{ 'animate-notification-bell': unreadCount > 0 }"
    >
        <i class="fas fa-bell text-xl" style="color: #FDB813;"></i>
        <span 
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full transform scale-90"
        ></span>
    </button>

    <!-- Notifications Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute right-0 mt-3 w-96 origin-top-right bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Commandes en attente</h3>
                <span 
                    x-show="unreadCount > 0"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                    x-text="unreadCount + ' nouvelle' + (unreadCount > 1 ? 's' : '')"
                ></span>
            </div>
        </div>

        <!-- Notification List -->
        <div class="max-h-[28rem] overflow-y-auto overscroll-contain">
            <template x-if="notifications.length > 0">
                <div class="divide-y divide-gray-100">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div class="group relative hover:bg-blue-50 transition-all duration-200">
                            <!-- Notification Content -->
                            <div class="px-4 py-4">
                                <div class="flex items-start gap-4">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center bg-blue-100">
                                        <i class="fas fa-shopping-cart text-blue-600"></i>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            Nouvelle commande #<span x-text="notification.order_number"></span>
                                        </p>
                                        <p class="text-sm text-gray-600 mt-0.5" x-text="notification.details"></p>
                                        
                                        <div class="flex items-center gap-4 mt-2">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <i class="fas fa-clock mr-1.5"></i>
                                                <span x-text="formatTimeAgo(notification.created_at)"></span>
                                            </div>
                                            
                                            <a 
                                                :href="'/magazinier/orders/' + notification.id"
                                                class="text-xs font-medium text-blue-600 hover:text-blue-900 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                            >
                                                Voir les détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-check text-gray-600 text-xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Tout est à jour !</p>
                    <p class="text-sm text-gray-500 mt-1">Aucune commande en attente.</p>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 rounded-b-xl">
            <a 
                href="{{ route('magazinier.orders') }}"
                class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200"
            >
                Voir toutes les commandes
            </a>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar */
.max-h-\[28rem\] {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 transparent;
}

.max-h-\[28rem\]::-webkit-scrollbar {
    width: 6px;
}

.max-h-\[28rem\]::-webkit-scrollbar-track {
    background: transparent;
}

.max-h-\[28rem\]::-webkit-scrollbar-thumb {
    background-color: #CBD5E1;
    border-radius: 3px;
}

/* Smooth Transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Bell Animation */
@keyframes notification-bell {
    0%, 50%, 100% {
        transform: rotate(0deg);
    }
    5%, 15%, 25%, 35%, 45% {
        transform: rotate(8deg);
    }
    10%, 20%, 30%, 40% {
        transform: rotate(-8deg);
    }
}

.animate-notification-bell {
    animation: notification-bell 2.5s ease-in-out infinite;
    transform-origin: top center;
}
</style> 