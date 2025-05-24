@extends('layouts.appp')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Unread Stock Notifications</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                {{ $notifications->total() }} unread notifications
            </span>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="flex items-start p-4 bg-blue-50 rounded-lg border border-blue-200" data-notification-id="{{ $notification->id }}">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-box text-blue-500"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm text-gray-800">{{ $notification->message }}</p>
                        <div class="mt-1 flex items-center text-xs text-gray-500">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            <button 
                                onclick="markAsRead('{{ $notification->id }}')" 
                                class="ml-3 text-blue-600 hover:text-blue-800"
                            >
                                Mark as read
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-green-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No unread notifications</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the notification from the list
            const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notification) {
                notification.remove();
            }
            // Refresh the page to update the count and list
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
@endsection 