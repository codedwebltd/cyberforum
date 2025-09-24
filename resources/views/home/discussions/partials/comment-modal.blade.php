<!-- discussions/partials/comment-modal.blade.php -->
{{-- takes crae of te comment section that pops up inside my modal. --}}
<div class="comment-thread mb-4" data-comment-id="{{ $comment->id }}">
    <div class="flex space-x-3">
        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
            @if($comment->user && $comment->user->avatar_url)
                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full rounded-full object-cover">
            @else
                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="bg-muted rounded-2xl px-4 py-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-sm">{{ $comment->user->name ?? 'Anonymous' }}</span>
                    <span class="text-xs text-muted-foreground">
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
                <p class="text-sm leading-relaxed">{{ $comment->content }}</p>
            </div>
            
            <div class="flex items-center gap-4 mt-2 text-sm">
    <button class="like-comment-btn flex items-center gap-1 {{ isset($comment->is_liked_by_user) && $comment->is_liked_by_user ? 'text-red-500 hover:text-red-600' : 'text-muted-foreground hover:text-primary' }} transition-colors" 
        data-comment-id="{{ $comment->id }}">
        <i data-lucide="heart" class="w-3 h-3"></i>
        <span class="likes-count">{{ $comment->likes_count ?: '' }}</span>
    </button>
    <button onclick="toggleReplyForm('{{ $comment->id }}')" 
            class="text-muted-foreground hover:text-primary transition-colors text-xs">Reply</button>
    @if(auth()->check() && auth()->id() === $comment->user_id)
    <button onclick="deleteComment('{{ $comment->id }}')" 
            class="text-muted-foreground hover:text-red-600 transition-colors text-xs">Delete</button>
    @endif
</div>

<!-- Reply Form -->
@auth
<div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
    <div class="flex space-x-2">
        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
            @if(auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
            @else
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            @endif
        </div>
        <div class="flex-1">
            <textarea id="reply-textarea-{{ $comment->id }}" rows="2" 
                      class="w-full px-3 py-2 text-sm border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50"
                      placeholder="Write a reply..."></textarea>
            <div class="flex justify-end gap-2 mt-2">
                <button onclick="toggleReplyForm('{{ $comment->id }}')" 
                        class="px-3 py-1 text-xs text-muted-foreground hover:text-foreground">Cancel</button>
                <button onclick="submitReply('{{ $comment->id }}', document.getElementById('reply-textarea-{{ $comment->id }}'), this)" 
                        class="px-3 py-1 text-xs bg-primary text-primary-foreground rounded hover:bg-primary/90">Reply</button>
            </div>
        </div>
    </div>
</div>
@endauth
        </div>
    </div>
    
    <!-- Initial Replies (first 3) -->
   <!-- Replies Container (always present) -->
<div class="ml-8 mt-3 space-y-3" id="replies-container-{{ $comment->id }}" 
     @if($comment->initial_replies->count() == 0)style="display: none;"@endif>
    @foreach($comment->initial_replies as $reply)
        <div class="flex space-x-3">
            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                @if($reply->user && $reply->user->avatar_url)
                    <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-full h-full rounded-full object-cover">
                @else
                    {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="bg-muted/50 rounded-2xl px-3 py-2">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-xs">{{ $reply->user->name ?? 'Anonymous' }}</span>
                        <span class="text-xs text-muted-foreground">
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
                    <p class="text-xs leading-relaxed">{{ $reply->content }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

    <!-- Load More Replies Button -->
    @if($comment->has_more_replies)
        <div class="ml-8 mt-2">
          <button class="load-more-replies-btn text-sm text-primary hover:text-primary/80 transition-colors" 
                data-comment-id="{{ $comment->id }}"
                data-next-page="2">
            Load {{ $comment->total_replies - 3 }} more replies
        </button>
        </div>
    @endif
</div>