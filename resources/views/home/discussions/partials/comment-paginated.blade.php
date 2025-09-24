{{-- home.discussions.partials.comment-paginated.blade.php --}}
@php
    $maxDepth = 3;
    $canReply = true;
@endphp

<div class="comment-thread relative mb-6" data-comment-id="{{ $comment->id }}">
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
            @if($comment->initial_replies->count() > 0 || $comment->has_more_replies)
                <div class="absolute left-4 top-8 w-0.5 bg-gray-300 dark:bg-gray-600 h-full"></div>
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
                <button class="like-comment-btn flex items-center gap-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" 
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
                            <button type="submit" 
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

    <!-- Initial Replies -->
    @if($comment->initial_replies->count() > 0)
        <div class="ml-8 mt-3 space-y-3 relative" id="replies-container-{{ $comment->id }}">
            @foreach($comment->initial_replies as $index => $reply)
                <!-- Horizontal connector line for each reply -->
                <div class="absolute -left-4 bg-gray-300 dark:bg-gray-600 w-4 h-0.5" 
                     style="top: {{ ($index * 6) + 2 }}rem;"></div>
                
                <div class="flex space-x-3 reply-item">
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
                        <div class="flex items-center gap-3 mt-1 text-xs">
                            <button class="like-comment-btn flex items-center gap-1 text-gray-500 hover:text-blue-600 transition-colors" 
                                data-comment-id="{{ $reply->id }}">
                            <i data-lucide="heart" class="w-3 h-3"></i>
                            <span class="likes-count">{{ $reply->likes_count ?: '' }}</span>
                        </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Load More Replies Button -->
    @if($comment->has_more_replies)
        <div class="ml-8 mt-3">
            <button class="load-more-replies-btn flex items-center gap-2 text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors" 
                    data-comment-id="{{ $comment->id }}"
                    data-next-page="2">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Load {{ $comment->total_replies - 3 }} more replies</span>
            </button>
            <div id="loading-replies-{{ $comment->id }}" class="hidden ml-6 mt-2">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                    Loading replies...
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// let repliesPage = {};

// function loadMoreReplies(commentId) {
//     const button = document.getElementById(`load-more-replies-${commentId}`);
//     const loading = document.getElementById(`loading-replies-${commentId}`);
//     const container = document.getElementById(`replies-container-${commentId}`);
    
//     button.style.display = 'none';
//     loading.classList.remove('hidden');
    
//     const page = repliesPage[commentId] || 2; // Start from page 2 since we already loaded 3 initial
    
//     fetch(`/comment/${commentId}/replies/load-more?page=${page}`)
//         .then(response => response.json())
//         .then(data => {
//             loading.classList.add('hidden');
            
//             if (data.success) {
//                 container.insertAdjacentHTML('beforeend', data.html);
//                 lucide.createIcons();
                
//                 repliesPage[commentId] = data.next_page;
                
//                 if (data.has_more) {
//                     button.style.display = 'flex';
//                     const remaining = data.total_count - data.loaded_count;
//                     button.querySelector('span').textContent = `Load ${remaining} more replies`;
//                 }
//             } else {
//                 button.style.display = 'flex';
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             loading.classList.add('hidden');
//             button.style.display = 'flex';
//         });
// }
</script>

