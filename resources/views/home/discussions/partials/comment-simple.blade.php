{{-- home.discussions.partials.comment-simple.blade.php --}}
<div class="comment-thread mb-4">
    <div class="flex space-x-3">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
            @if($comment->user && $comment->user->avatar_url)
                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full rounded-full object-cover">
            @else
                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl px-4 py-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">
                        {{ $comment->user->name ?? 'Anonymous' }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        @php
                            $diff = now()->diffInSeconds($comment->created_at);
                            if ($diff < 60) $timeStr = $diff . 's';
                            elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm';
                            elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h';
                            elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd';
                            elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo';
                            else $timeStr = floor($diff / 31536000) . 'y';
                        @endphp
                        {{ $timeStr }}
                    </span>
                </div>
                <p class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed">
                    {{ $comment->content }}
                </p>
            </div>
            <div class="flex items-center gap-4 mt-2 text-sm">
                <button class="flex items-center gap-1 text-gray-500 hover:text-blue-600 transition-colors">
                    <i data-lucide="heart" class="w-3 h-3"></i>
                    <span>{{ $comment->likes_count ?: '' }}</span>
                </button>
            </div>
        </div>
    </div>
    
    @if($comment->replies->count() > 0)
        <div class="ml-8 mt-3 space-y-3">
            @foreach($comment->replies as $reply)
                <div class="flex space-x-3">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                        @if($reply->user && $reply->user->avatar_url)
                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-2xl px-3 py-2">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white text-xs">
                                    {{ $reply->user->name ?? 'Anonymous' }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    @php
                                        $diff = now()->diffInSeconds($reply->created_at);
                                        if ($diff < 60) $timeStr = $diff . 's';
                                        elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm';
                                        elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h';
                                        elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd';
                                        elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo';
                                        else $timeStr = floor($diff / 31536000) . 'y';
                                    @endphp
                                    {{ $timeStr }}
                                </span>
                            </div>
                            <p class="text-gray-800 dark:text-gray-200 text-xs leading-relaxed">
                                {{ $reply->content }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

