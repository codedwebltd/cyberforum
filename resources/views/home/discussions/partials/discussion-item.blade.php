{{-- home.discussions.partials.discussion-item.blade.php this partials loads more discussion.--}}
<div class="group bg-gradient-to-r from-white via-gray-50 to-white dark:from-gray-800 dark:via-gray-700 dark:to-gray-800 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden">
    
    <!-- Status Indicators -->
    <div class="absolute top-4 right-4 flex flex-col gap-2 z-10">
        @if($discussion->is_pinned)
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <i data-lucide="pin" class="w-4 h-4 text-white"></i>
            </div>
        @endif
        
        @if($discussion->is_featured)
            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                <i data-lucide="star" class="w-4 h-4 text-white"></i>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- User Header -->
        <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="relative flex-shrink-0">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 p-0.5 shadow-lg">
                    <div class="w-full h-full rounded-2xl bg-white dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                        @if ($discussion->user && $discussion->user->avatar_url)
                            <img src="{{ $discussion->user->avatar_url }}" alt="{{ $discussion->user->name }}" class="w-full h-full object-cover rounded-2xl">
                        @else
                            <span class="text-base sm:text-lg font-bold bg-gradient-to-br from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                {{ strtoupper(substr($discussion->user->name ?? 'U', 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-gray-900 dark:text-white text-base sm:text-lg truncate">
                    {{ $discussion->user->name ?? 'Anonymous' }}
                </h4>
                <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <span class="px-2 py-1 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-600 dark:text-blue-400 rounded-full font-medium">
                        {{ ucfirst($discussion->type) }}
                    </span>
                    @php
                        $diff = now()->diffInSeconds($discussion->created_at);
                        if ($diff < 60) $timeStr = $diff . 's ago';
                        elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm ago';
                        elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h ago';
                        elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd ago';
                        elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo ago';
                        else $timeStr = floor($diff / 31536000) . 'y ago';
                    @endphp
                    <span class="whitespace-nowrap">{{ $timeStr }}</span>
                </div>
            </div>
        </div>

        <!-- Discussion Title -->
        <a href="{{ route('discussion.show', $discussion->slug) }}" class="block mb-4">
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white leading-tight group-hover:bg-gradient-to-r group-hover:from-blue-600 group-hover:to-purple-600 group-hover:bg-clip-text group-hover:text-transparent transition-all duration-500 line-clamp-2">
                {{ $discussion->title }}
            </h3>
        </a>

<!-- Featured Image -->
        {{-- @if($discussion->featured_image_url)
        <a href="{{ route('discussion.show', $discussion->slug) }}" class="block mb-4 sm:mb-6">
            <img src="{{ $discussion->featured_image_url }}" 
                 alt="{{ $discussion->title }}"
                 class="w-full h-40 sm:h-56 lg:h-64 object-cover rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
        </a>
        @endif --}}

        <!-- Content Preview -->
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4 sm:mb-6 text-sm sm:text-base line-clamp-3">
            {{ $discussion->excerpt ?? Str::limit($discussion->content, 180) }}
        </p>

        <!-- Engagement Stats -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 sm:gap-6">
                <!-- Likes -->
                <button onclick="likeDiscussion('{{ $discussion->slug }}')" 
                        class="flex items-center gap-2 px-3 py-2 rounded-xl transition-all duration-300 hover:scale-105 hover:-translate-y-1 {{ isset($discussion->is_liked_by_user) && $discussion->is_liked_by_user ? 'bg-red-50 text-red-500 dark:bg-red-900/20 dark:text-red-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-400' }}">
                    <i data-lucide="heart" class="w-4 h-4 {{ isset($discussion->is_liked_by_user) && $discussion->is_liked_by_user ? 'fill-current' : '' }}"></i>
                    <span class="font-semibold text-sm" id="likes-{{ $discussion->id }}">{{ number_format($discussion->likes_count) }}</span>
                </button>

                <!-- Comments -->
                @if($discussion->allow_comments)
                    <button onclick="openCommentsModal('{{ $discussion->id }}', '{{ addslashes($discussion->title) }}')"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/20 dark:hover:text-green-400 transition-all duration-300 hover:scale-105 hover:-translate-y-1 replies-btn" 
                            data-post-id="{{ $discussion->id }}">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <span class="font-semibold text-sm">{{ number_format($discussion->comments_count) }}</span>
                    </button>
                @else
                    <button onclick="showCommentsDisabledToast()" 
                            class="flex items-center gap-2 px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium hover:bg-red-100 dark:hover:bg-red-900/30 transition-all duration-300 cursor-pointer">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        <i data-lucide="slash" class="w-3 h-3 -ml-2"></i>
                        <span class="hidden sm:inline">Comments Disabled</span>
                        <span class="sm:hidden">Disabled</span>
                    </button>
                @endif

                <!-- Views -->
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    <span class="font-semibold">{{ number_format($discussion->views_count) }}</span>
                </div>
            </div>

            <!-- Read More Arrow -->
            <a href="{{ route('discussion.show', $discussion->slug) }}" 
               class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 hover:rotate-3 transition-all duration-300 group-hover:translate-x-2">
                <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
            </a>
        </div>
    </div>

    <!-- Hover Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-pink-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none rounded-2xl"></div>
</div>
