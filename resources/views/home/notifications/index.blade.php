@extends('inc.home.app')
@section('title', 'Notifications - ' . config('app.name'))
@section('content')

<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-5xl">
        @include('session-message.session-message')
        
        <!-- Header Card -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600">
                            <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                        </div>
                        @if($unreadCount > 0)
                        <div class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white rounded-full bg-red-500">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Stay updated with your community activity</p>
                    </div>
                </div>
                
                @if($unreadCount > 0)
                <div class="hidden sm:flex gap-3">
                    <button id="mark-all-read" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <i data-lucide="check-check" class="w-4 h-4 mr-2"></i>
                        Mark All Read
                    </button>
                    <button id="clear-all" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Clear All
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Stats Overview Card -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
            <div class="grid grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $notifications->total() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $unreadCount }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unread</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $notifications->total() - $unreadCount }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Read</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $notifications->where('created_at', '>=', now()->startOfDay())->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Today</div>
                </div>
            </div>
        </div>

  <!-- Filter Tabs Card -->
<div class="p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
    <div class="flex items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Notifications</h2>
    </div>
    <div class="flex space-x-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1 overflow-x-auto scrollbar-hide">
        <a href="{{ route('notifications.index') }}" 
           class="filter-tab {{ !request()->hasAny(['type', 'status']) ? 'active' : '' }}">
            <i data-lucide="layers" class="w-4 h-4 sm:mr-2"></i>
            <span class="hidden sm:inline">All</span>
        </a>
        <a href="{{ route('notifications.index', ['status' => 'unread']) }}" 
           class="filter-tab {{ request('status') === 'unread' ? 'active' : '' }}">
            <i data-lucide="mail" class="w-4 h-4 sm:mr-2"></i>
            <span class="hidden sm:inline">Unread</span>
            @if($unreadCount > 0)
            <span class="ml-1 sm:ml-2 px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $unreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('notifications.index', ['type' => 'like']) }}" 
           class="filter-tab {{ request('type') === 'like' ? 'active' : '' }}">
            <i data-lucide="heart" class="w-4 h-4 sm:mr-2"></i>
            <span class="hidden sm:inline">Likes</span>
        </a>
        <a href="{{ route('notifications.index', ['type' => 'comment']) }}" 
           class="filter-tab {{ request('type') === 'comment' ? 'active' : '' }}">
            <i data-lucide="message-circle" class="w-4 h-4 sm:mr-2"></i>
            <span class="hidden sm:inline">Comments</span>
        </a>
        <a href="{{ route('notifications.index', ['type' => 'follow']) }}" 
           class="filter-tab {{ request('type') === 'follow' ? 'active' : '' }}">
            <i data-lucide="user-plus" class="w-4 h-4 sm:mr-2"></i>
            <span class="hidden sm:inline">Follows</span>
        </a>
    </div>
</div>

        <!-- Notifications List Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $notifications->count() }} notifications</span>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($notifications as $notification)
                @php
                    $type = strtolower(class_basename($notification->type));
                    $iconConfig = [
                        'like' => ['icon' => 'heart', 'color' => 'text-pink-600', 'bg' => 'bg-pink-100 dark:bg-pink-900/20'],
                        'comment' => ['icon' => 'message-circle', 'color' => 'text-blue-600', 'bg' => 'bg-blue-100 dark:bg-blue-900/20'],
                        'follow' => ['icon' => 'user-plus', 'color' => 'text-green-600', 'bg' => 'bg-green-100 dark:bg-green-900/20'],
                        'mention' => ['icon' => 'at-sign', 'color' => 'text-purple-600', 'bg' => 'bg-purple-100 dark:bg-purple-900/20'],
                        'reply' => ['icon' => 'reply', 'color' => 'text-indigo-600', 'bg' => 'bg-indigo-100 dark:bg-indigo-900/20'],
                        'system' => ['icon' => 'bell', 'color' => 'text-gray-600', 'bg' => 'bg-gray-100 dark:bg-gray-700']
                    ];
                    $config = $iconConfig[$type] ?? $iconConfig['system'];
                @endphp
                
                <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}" data-id="{{ $notification->id }}">
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg {{ $config['bg'] }} flex items-center justify-center">
                                    <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 {{ $config['color'] }}"></i>
                                </div>
                                @if(!$notification->read_at)
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                            {{ $notification->data['title'] ?? 'New Notification' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">
                                            {{ $notification->data['message'] ?? $notification->data['body'] ?? 'You have a new notification' }}
                                        </p>
                                        
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            @if(!$notification->read_at)
                                            <span class="text-xs font-medium text-red-600 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">
                                                New
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-1 ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if(!$notification->read_at)
                                        <button class="mark-read-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 flex items-center justify-center transition-colors" 
                                                data-id="{{ $notification->id }}" title="Mark as read">
                                            <i data-lucide="check" class="w-4 h-4 text-gray-600 dark:text-gray-300"></i>
                                        </button>
                                        @endif
                                        <button class="delete-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-600 hover:bg-red-100 dark:hover:bg-red-900/20 flex items-center justify-center transition-colors" 
                                                data-id="{{ $notification->id }}" title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-gray-600 dark:text-gray-300 hover:text-red-600"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Link -->
                                @if(isset($notification->data['action_url']))
                                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-600">
                                    <a href="{{ $notification->data['action_url'] }}" 
                                       class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                        View Details
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <i data-lucide="bell-off" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">All caught up!</h3>
                    <p class="text-gray-600 dark:text-gray-400">No notifications right now. We'll let you know when something interesting happens.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination Card -->
        @if($notifications->hasPages())
        <div class="mt-6 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            {{ $notifications->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile FAB -->
    @if($unreadCount > 0)
    <div class="fixed bottom-6 right-6 sm:hidden flex flex-col gap-3">
        <button id="mobile-clear-all" 
                class="w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
            <i data-lucide="trash-2" class="w-5 h-5"></i>
        </button>
        <button id="mobile-mark-all" 
                class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110">
            <i data-lucide="check-check" class="w-5 h-5"></i>
        </button>
    </div>
    @endif


    <!-- Notification Details Modal -->
<div id="notification-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 max-w-lg w-full mx-4 max-h-[80vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div id="modal-icon" class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <i data-lucide="bell" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="font-semibold text-gray-900 dark:text-white">Notification Details</h3>
                        <p id="modal-time" class="text-sm text-gray-500 dark:text-gray-400"></p>
                    </div>
                </div>
                <button id="close-modal" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6">
            <p id="modal-message" class="text-gray-700 dark:text-gray-300 mb-4"></p>
            
            <!-- Action Button -->
            <div id="modal-action" class="hidden">
                <a id="modal-action-link" href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    View Details
                    <i data-lucide="external-link" class="w-3 h-3"></i>
                </a>
            </div>
        </div>
        
        <!-- Modal Actions -->
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 rounded-b-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="flex gap-2">
        <button id="modal-mark-read" class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition-all duration-200 min-w-0 flex-1 sm:flex-initial">
            <i data-lucide="check" class="w-4 h-4 flex-shrink-0"></i>
            <span class="truncate">Mark Read</span>
        </button>
        <button id="modal-delete" class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all duration-200 min-w-0 flex-1 sm:flex-initial">
            <i data-lucide="trash-2" class="w-4 h-4 flex-shrink-0"></i>
            <span class="truncate">Delete</span>
        </button>
    </div>
    <button id="modal-close" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 active:bg-gray-100 dark:active:bg-gray-600 transition-all duration-200">
        Close
    </button>
</div>
        </div>
    </div>
</div>
</main>

<style>
/* Filter Tabs - Clean like your homepage Latest/Hot/Trending tabs */
.filter-tab {
    @apply flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-md transition-all duration-200;
    @apply text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white;
    min-width: fit-content;
    white-space: nowrap;
}

.filter-tab.active {
    @apply bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Notification Items */
.notification-item.unread {
    position: relative;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #3b82f6;
    border-radius: 0 4px 4px 0;
}

.notification-item.read {
    opacity: 0.8;
}

/* Line Clamp */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animations */
@keyframes slideOut {
    from { 
        transform: translateX(0); 
        opacity: 1; 
        height: auto;
    }
    to { 
        transform: translateX(100%); 
        opacity: 0; 
        height: 0;
    }
}

.slide-out {
    animation: slideOut 0.3s ease-in-out forwards;
}

/* Scrollbar Hide */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Mobile responsive */
@media (max-width: 640px) {
    .filter-tab {
        padding: 10px 12px;
    }
}

@media (min-width: 641px) {
    .filter-tab {
        padding: 10px 16px;
    }
}


/* Modal styles */
#notification-modal .bg-black {
    backdrop-filter: blur(4px);
}

#notification-modal > div {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.notification-item {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Mark as read functionality
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const id = this.dataset.id;
            const notificationItem = document.querySelector(`[data-id="${id}"]`);
            
            try {
                const response = await fetch(`/home/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    notificationItem.classList.remove('unread');
                    notificationItem.classList.add('read');
                    
                    // Remove unread indicator
                    const unreadDot = notificationItem.querySelector('.bg-red-500.rounded-full');
                    if (unreadDot) unreadDot.remove();
                    
                    // Remove "New" badge
                    const newBadge = notificationItem.querySelector('.bg-red-50');
                    if (newBadge) newBadge.remove();
                    
                    this.remove();
                    updateNotificationCounts();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        });
    });

    // Delete notification functionality
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Are you sure you want to delete this notification?')) return;
            
            const id = this.dataset.id;
            const notificationItem = document.querySelector(`[data-id="${id}"]`);
            
            try {
                const response = await fetch(`/home/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    notificationItem.classList.add('slide-out');
                    setTimeout(() => {
                        notificationItem.remove();
                        updateNotificationCounts();
                    }, 300);
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
            }
        });
    });




// Modal functionality
let currentNotificationId = null;
const modal = document.getElementById('notification-modal');
const modalIcon = document.getElementById('modal-icon');
const modalTitle = document.getElementById('modal-title');
const modalTime = document.getElementById('modal-time');
const modalMessage = document.getElementById('modal-message');
const modalAction = document.getElementById('modal-action');
const modalActionLink = document.getElementById('modal-action-link');

// Click on notification to open modal
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('click', function(e) {
        // Don't open modal if clicking on action buttons
        if (e.target.closest('.mark-read-btn, .delete-btn')) return;
        
        const notificationId = this.dataset.id;
        currentNotificationId = notificationId;
        
        // Get notification data from the DOM
        const icon = this.querySelector('[data-lucide]');
        const title = this.querySelector('h3').textContent;
        const message = this.querySelector('p').textContent;
        const time = this.querySelector('.text-xs').textContent;
        const actionUrl = this.querySelector('a[href]')?.href;
        const isUnread = this.classList.contains('unread');
        
        // Update modal content
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalTime.textContent = time;
        
        // Update icon
        const iconName = icon.getAttribute('data-lucide');
        const iconClasses = icon.className;
        // const iconBg = this.querySelector('.w-10.h-10').className;
        const iconBg = this.querySelector('.w-10').className;

        modalIcon.className = iconBg;
        modalIcon.innerHTML = `<i data-lucide="${iconName}" class="${iconClasses}"></i>`;
        
        // Show/hide action button
        if (actionUrl) {
            modalActionLink.href = actionUrl;
            modalAction.classList.remove('hidden');
        } else {
            modalAction.classList.add('hidden');
        }
        
        // Show/hide mark as read button
        const markReadBtn = document.getElementById('modal-mark-read');
        if (isUnread) {
            markReadBtn.classList.remove('hidden');
        } else {
            markReadBtn.classList.add('hidden');
        }
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        lucide.createIcons();
    });
});

// Close modal functionality
function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentNotificationId = null;
}

document.getElementById('close-modal').addEventListener('click', closeModal);
document.getElementById('modal-close').addEventListener('click', closeModal);

// Close modal when clicking outside
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Modal mark as read
document.getElementById('modal-mark-read').addEventListener('click', async function() {
    if (!currentNotificationId) return;
    
    try {
        const response = await fetch(`/home/notifications/${currentNotificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const notificationItem = document.querySelector(`[data-id="${currentNotificationId}"]`);
            notificationItem.classList.remove('unread');
            notificationItem.classList.add('read');
            
            // Remove unread indicators
            const unreadDot = notificationItem.querySelector('.bg-red-500.rounded-full');
            if (unreadDot) unreadDot.remove();
            
            const newBadge = notificationItem.querySelector('.bg-red-50');
            if (newBadge) newBadge.remove();
            
            const markReadBtnInList = notificationItem.querySelector('.mark-read-btn');
            if (markReadBtnInList) markReadBtnInList.remove();
            
            // Hide modal button
            this.classList.add('hidden');
            
            updateNotificationCounts();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
});

// Modal delete
document.getElementById('modal-delete').addEventListener('click', async function() {
    if (!currentNotificationId) return;
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    try {
        const response = await fetch(`/home/notifications/${currentNotificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            const notificationItem = document.querySelector(`[data-id="${currentNotificationId}"]`);
            notificationItem.classList.add('slide-out');
            
            setTimeout(() => {
                notificationItem.remove();
                updateNotificationCounts();
            }, 300);
            
            closeModal();
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
});


    // Bulk actions
    function setupBulkAction(selector, endpoint, confirmMessage = null) {
        document.querySelectorAll(selector).forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (confirmMessage && !confirm(confirmMessage)) return;
                
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error with bulk action:', error);
                }
            });
        });
    }

    setupBulkAction('#mark-all-read, #mobile-mark-all', '/home/notifications/mark-all-read');
    setupBulkAction('#clear-all, #mobile-clear-all', '/home/notifications/clear', 'Are you sure you want to delete all notifications?');

    // Update notification counts
    function updateNotificationCounts() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        
        document.querySelectorAll('.bg-red-500.text-white.rounded-full').forEach(badge => {
            if (unreadCount === 0) {
                badge.style.display = 'none';
            } else {
                badge.textContent = unreadCount;
            }
        });
    }
});
</script>

@endsection