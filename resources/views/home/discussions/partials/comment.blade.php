{{-- home.discussions.partials.comment.blade.php --}}
{{-- this takes care of te comment that loads inside the discussion page. --}}
@php
    $maxDepth = 2;
    $canReply = $depth < $maxDepth;
@endphp
<style>
    .comment-thread {
    @apply break-words;
}

.comment-content {
    @apply max-w-full overflow-hidden;
    word-wrap: break-word;
    overflow-wrap: break-word;
}
</style>
<div class="comment-thread relative" data-comment-id="{{ $comment->id }}">
    <div class="flex space-x-3">
        <!-- Avatar with Connection Line -->
        <div class="flex-shrink-0 relative">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm relative z-10">
                @if($comment->user && $comment->user->avatar_url)
                    <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full rounded-full object-cover">
                @else
                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            
            <!-- Single Vertical Line for Replies -->
            @if(($comment->initial_replies ?? collect())->count() > 0 || ($comment->has_more_replies ?? false))
                <div class="absolute left-4 top-8 w-0.5 bg-gray-300 dark:bg-gray-600" 
                     style="height: calc(100% - 2rem + {{ (($comment->initial_replies ?? collect())->count() - 1) * 1.5 }}rem);"></div>
            @endif
        </div>

        <!-- Comment Content -->
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

            <!-- Comment Actions -->
            <div class="flex items-center gap-4 mt-2 text-sm">
                <button class="like-comment-btn flex items-center gap-1 {{ isset($comment->is_liked_by_user) && $comment->is_liked_by_user ? 'text-red-500 hover:text-red-600' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }} transition-colors" 
                        data-comment-id="{{ $comment->id }}">
                    <i data-lucide="heart" class="w-3 h-3"></i>
                    <span class="likes-count">{{ $comment->likes_count ?: '' }}</span>
                    <span class="text-xs">Like</span>
                </button>

                @if($canReply)
                <button onclick="toggleReplyForm('{{ $comment->id }}')" 
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors text-xs font-medium">
                    Reply
                </button>
                @endif

                @if(auth()->check() && auth()->id() === $comment->user_id)
                <button onclick="deleteComment('{{ $comment->id }}')" 
                        class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors text-xs">
                    Delete
                </button>
                @endif
            </div>

            <!-- Reply Form -->
            @auth
            <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                <form action="{{ route('discussion.comment.store', $discussion->slug) }}" method="POST" class="flex space-x-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                        @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <textarea name="content" 
                                  rows="2" 
                                  class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  placeholder="Write a reply..." required></textarea>
                        <div class="flex justify-end gap-2 mt-2">
                            <button type="button" 
                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                    class="px-3 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                Cancel
                            </button>
                        <button onclick="submitShowReply('{{ $comment->id }}', '{{ $discussion->slug }}', this.form)" 
                                class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                            Reply
                        </button>
                        </div>
                    </div>
                </form>
            </div>
            @endauth
        </div>
    </div>

    <!-- Replies Container (always present, matches modal pattern) -->
    <div class="ml-8 mt-3 space-y-3 relative" id="replies-container-{{ $comment->id }}" 
         @if(($comment->initial_replies ?? collect())->count() == 0)style="display: none;"@endif>
        @foreach(($comment->initial_replies ?? collect()) as $index => $reply)
            <!-- Horizontal connector line for each reply -->
            <div class="absolute -left-4 bg-gray-300 dark:bg-gray-600 w-4 h-0.5" 
                 style="top: {{ ($index * 6) + 2 }}rem;"></div>
            @include('home.discussions.partials.comment', ['comment' => $reply, 'depth' => $depth + 1, 'discussion' => $discussion])
        @endforeach
    </div>

    <!-- Load More Replies Button (matches modal pattern) -->
    @if(($comment->has_more_replies ?? false))
        <div class="ml-8 mt-3">
            <button class="load-more-replies-btn text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors" 
                    data-comment-id="{{ $comment->id }}"
                    data-next-page="2">
                Load {{ ($comment->total_replies ?? 0) - 3 }} more replies
            </button>
        </div>
    @endif
</div>

<script>
    function submitShowReply(commentId, slug, form) {
    const textarea = form.querySelector('textarea');
    const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('button:last-child');
    
    if (submitBtn.disabled) return false;
    
    const content = textarea.value.trim();
    if (!content) return false;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white inline-block mr-1"></div>Posting...';
    
    fetch(`/discussion/${slug}/reply-show`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            content: content,
            parent_id: commentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            textarea.value = '';
            toggleReplyForm(commentId);
            
            // Add reply to replies container (matches modal pattern)
            const repliesContainer = document.getElementById(`replies-container-${commentId}`);
            if (repliesContainer) {
                repliesContainer.style.display = 'block';
                repliesContainer.insertAdjacentHTML('beforeend', data.reply_html);
                lucide.createIcons();
            }
            
            showToast('Reply posted successfully!', 'success');
        }
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Reply';
    });
    
    return false;
}
</script>