@php
    use App\Models\Notification;

    $role = session('user_type');
    $userId = session('user_id');
    $notifications = collect();
    $unreadCount = 0;
    $rolePrefix = '';

    if ($role === 'buyer') {
        $notifications = Notification::where('buyerID', $userId)->orderBy('dateSent', 'desc')->take(10)->get();
        $unreadCount = Notification::where('buyerID', $userId)->where('isRead', false)->count();
        $rolePrefix = 'buyer';
    } elseif ($role === 'artist') {
        $notifications = Notification::where('artistID', $userId)->orderBy('dateSent', 'desc')->take(10)->get();
        $unreadCount = Notification::where('artistID', $userId)->where('isRead', false)->count();
        $rolePrefix = 'artist';
    } elseif ($role === 'admin') {
        $notifications = Notification::where('adminID', $userId)->orderBy('dateSent', 'desc')->take(10)->get();
        $unreadCount = Notification::where('adminID', $userId)->where('isRead', false)->count();
        $rolePrefix = 'admin';
    }
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <!-- Bell Button -->
    <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center font-bold animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden" style="display: none;">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Notifications</h3>
            @if($unreadCount > 0)
                <form action="{{ route($rolePrefix . '.notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-forest hover:text-forest-dark font-medium">Mark all read</button>
                </form>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notif)
                <div class="flex items-start gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ !$notif->isRead ? 'bg-blue-50/50' : '' }}">
                    <!-- Type Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                        @if($notif->notifType === 'order')
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                        @elseif($notif->notifType === 'donation')
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </div>
                        @elseif($notif->notifType === 'product')
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        @else
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-snug {{ !$notif->isRead ? 'font-medium' : '' }}">{{ $notif->notifMess }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->dateSent ? $notif->dateSent->diffForHumans() : '' }}</p>
                    </div>

                    <!-- Unread dot -->
                    @if(!$notif->isRead)
                        <form action="{{ route($rolePrefix . '.notifications.read', $notif->notificationID) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="w-2.5 h-2.5 bg-blue-500 rounded-full hover:bg-blue-600 transition-colors mt-1.5" title="Mark as read"></button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <p class="text-sm text-gray-400">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
