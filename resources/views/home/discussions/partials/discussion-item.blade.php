{{-- home.discussions.partials.discussion-item.blade.php --}}
<div class="p-4 transition-all border rounded-lg discussion-item border-border hover:border-primary/30 hover:shadow-sm">
    <div class="flex items-start space-x-4">
        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-gradient-primary">
            @if($discussion->user && $discussion->user->avatar_url)
                <img src="{{ $discussion->user->avatar_url }}" alt="{{ $discussion->user->name }}" class="w-full h-full rounded-full object-cover">
            @else
                <span class="text-sm font-bold text-white">{{ strtoupper(substr($discussion->user->name ?? 'U', 0, 1)) }}</span>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex flex-col gap-2 mb-2 sm:flex-row sm:items-center">
                <a href="{{ route('discussion.show', $discussion->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <h3 class="font-medium truncate">{{ $discussion->title }}</h3>
                </a>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-accent text-accent-foreground shrink-0">
                    {{ ucfirst($discussion->type) }}
                </span>
            </div>
            <p class="mb-3 text-sm text-muted-foreground truncate-lines-2">
                {{ $discussion->excerpt ?? Str::limit($discussion->content, 100) }}
            </p>
            <div class="flex flex-wrap items-center gap-3 text-sm sm:gap-6">
                <button onclick="likeDiscussion('{{ $discussion->slug }}')" 
                        class="flex items-center space-x-1 transition-colors cursor-pointer text-muted-foreground hover:text-primary">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span id="likes-{{ $discussion->id }}">{{ $discussion->likes_count }}</span>
                </button>
                <button onclick="openCommentsModal('{{ $discussion->id }}', '{{ addslashes($discussion->title) }}')"
                        class="flex items-center space-x-1 transition-colors cursor-pointer replies-btn text-muted-foreground hover:text-primary"
                        data-post-id="{{ $discussion->id }}">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>{{ $discussion->comments_count }}</span>
                </button>
                @php
                    $diff = now()->diffInSeconds($discussion->created_at);
                    if ($diff < 60) $timeStr = $diff . 's';
                    elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm';
                    elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h';
                    elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd';
                    elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo';
                    else $timeStr = floor($diff / 31536000) . 'y';
                @endphp
                <span class="text-xs text-muted-foreground">{{ $timeStr }}</span>
            </div>
        </div>
    </div>
</div>

