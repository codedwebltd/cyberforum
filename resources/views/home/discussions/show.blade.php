@extends('inc.home.app')
@section('title', $discussion->title . ' - ' . config('app.name'))
@section('content')

<main class="p-2 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Breadcrumb -->
        <nav class="mb-4 sm:mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400 overflow-x-auto whitespace-nowrap pb-2">
                <a href="{{ route('discussion.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex-shrink-0">
                    <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                    Discussions
                </a>
                <i data-lucide="chevron-right" class="w-4 h-4 flex-shrink-0"></i>
                <span class="text-gray-900 dark:text-white truncate">{{ Str::limit($discussion->title, 30) }}</span>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Discussion Header -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-4 lg:mb-6">
                    <div class="p-4 sm:p-6">
                        <!-- Meta Info with Scrollable Tags -->
                        <div class="mb-4">
                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                @if($discussion->is_pinned)
                                <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full flex items-center shadow-sm">
                                    <i data-lucide="pin" class="w-3 h-3 mr-1"></i>
                                    Pinned
                                </span>
                                @endif
                                
                                @if($discussion->is_featured)
                                <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-full flex items-center shadow-sm">
                                    <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                    Featured
                                </span>
                                @endif

                                <span class="px-3 py-1.5 text-xs font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full shadow-sm">
                                    {{ ucfirst($discussion->type) }}
                                </span>
                            </div>

                            @if($discussion->tags)
                                @php
                                    $tags = is_string($discussion->tags) ? json_decode($discussion->tags, true) : $discussion->tags;
                                @endphp
                                @if(is_array($tags) && count($tags) > 0)
                                <div class="overflow-x-auto scrollbar-hide">
                                    <div class="flex space-x-2 pb-2" style="min-width: max-content;">
                                        @foreach($tags as $tag)
                                        <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full whitespace-nowrap">
                                            #{{ $tag }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>

                        <!-- Beautiful Title -->
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent mb-6 leading-tight">
                            {{ $discussion->title }}
                        </h1>

                        <!-- Author & Meta -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-lg">
                                        @if($discussion->user && $discussion->user->avatar_url)
                                            <img src="{{ $discussion->user->avatar_url }}" alt="{{ $discussion->user->name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            {{ strtoupper(substr($discussion->user->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-lg text-gray-900 dark:text-white truncate">
                                        {{ $discussion->user->name ?? 'Anonymous' }}
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                        @php
                                            $diff = now()->diffInSeconds($discussion->created_at);
                                            if ($diff < 60) $timeStr = $diff . 's';
                                            elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm';
                                            elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h';
                                            elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd';
                                            elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo';
                                            else $timeStr = floor($diff / 31536000) . 'y';
                                        @endphp
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            {{ $timeStr }} ago
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                            {{ number_format($discussion->views_count) }} views
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                <button onclick="shareDiscussion('{{ $discussion->slug }}')" class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all transform hover:scale-105 shadow-sm" title="Share">
                                    <i data-lucide="share-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="bookmarkDiscussion()" class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition-all transform hover:scale-105 shadow-sm" title="Bookmark">
                                    <i data-lucide="bookmark" class="w-4 h-4"></i>
                                </button>
                                <button onclick="reportDiscussion()" class="p-3 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50 transition-all transform hover:scale-105 shadow-sm" title="Report">
                                    <i data-lucide="flag" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        @if($discussion->featured_image_url)
                        <div class="mb-6">
                            <img src="{{ $discussion->featured_image_url }}" 
                                 alt="{{ $discussion->title }}"
                                 class="w-full h-64 sm:h-80 object-cover rounded-xl shadow-lg">
                        </div>
                        @endif

                        <!-- Content with Auto Scroll -->
                        <div class="content-container max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800 pr-2">
                            <div class="prose prose-lg dark:prose-invert max-w-none leading-relaxed text-gray-800 dark:text-gray-200">
                                {!! nl2br(e($discussion->content)) !!}
                            </div>
                        </div>

                        <!-- Attachments -->
                        @if($discussion->attachments)
                            @php
                                $attachments = is_string($discussion->attachments) ? json_decode($discussion->attachments, true) : $discussion->attachments;
                            @endphp
                            @if(is_array($attachments) && count($attachments) > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                    <i data-lucide="paperclip" class="w-5 h-5"></i>
                                    Attachments
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($attachments as $attachment)
                                    <a href="{{ $attachment['url'] ?? '#' }}" 
                                       class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg hover:shadow-md transition-all transform hover:scale-102"
                                       target="_blank">
                                        <i data-lucide="file" class="w-5 h-5 text-blue-500 flex-shrink-0"></i>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $attachment['name'] ?? 'Attachment' }}
                                        </span>
                                        @if(isset($attachment['size']))
                                        <span class="text-xs text-gray-500 ml-auto flex-shrink-0 bg-white dark:bg-gray-800 px-2 py-1 rounded">
                                            {{ number_format($attachment['size'] / 1024, 1) }}KB
                                        </span>
                                        @endif
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>

                    <!-- Enhanced Engagement Bar -->
      <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-600/50 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl mb-6">
    <div class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center gap-3 sm:gap-6">
            <button onclick="likeDiscussion('{{ $discussion->slug }}')" 
                    class="like-discussion-btn flex items-center gap-2 px-3 py-2 rounded-full transition-all transform hover:scale-105 {{ isset($discussion->is_liked_by_user) && $discussion->is_liked_by_user ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} dark:bg-gray-700 dark:text-gray-300 shadow-sm text-sm">
                <i data-lucide="heart" class="w-4 h-4"></i>
                <span class="font-semibold" id="likes-count">{{ number_format($discussion->likes_count) }}</span>
            </button>
            
            <div class="flex items-center gap-2 px-3 py-2 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm text-sm">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span class="font-semibold" data-comments-count>{{ number_format($discussion->comments_count) }}</span>
            </div>
            
            <div class="flex items-center gap-2 px-3 py-2 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 shadow-sm text-sm">
                <i data-lucide="eye" class="w-4 h-4"></i>
                <span class="font-semibold">{{ number_format($discussion->views_count) }}</span>
            </div>

            <div class="flex items-center gap-2 px-3 py-2 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 shadow-sm text-sm">
                <i data-lucide="share-2" class="w-4 h-4"></i>
                <span class="font-semibold" id="shares-count">{{ number_format($discussion->shares_count) }}</span>
            </div>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-3 py-2 rounded-full shadow-sm w-fit">
            @php
    $lastActivityDiff = now()->diffInSeconds($discussion->last_activity_at);
    if ($lastActivityDiff < 60) $lastActivityTimeStr = $lastActivityDiff . 's';
    elseif ($lastActivityDiff < 3600) $lastActivityTimeStr = floor($lastActivityDiff / 60) . 'm';
    elseif ($lastActivityDiff < 86400) $lastActivityTimeStr = floor($lastActivityDiff / 3600) . 'h';
    elseif ($lastActivityDiff < 2592000) $lastActivityTimeStr = floor($lastActivityDiff / 86400) . 'd';
    elseif ($lastActivityDiff < 31536000) $lastActivityTimeStr = floor($lastActivityDiff / 2592000) . 'mo';
    else $lastActivityTimeStr = floor($lastActivityDiff / 31536000) . 'y';
@endphp
Last activity {{ $lastActivityTimeStr }} ago
        </div>
    </div>
</div>

                <!-- Comments Section with Scrollable Container -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-t-2xl">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="message-square" class="w-6 h-6 text-blue-600"></i>
                            Discussion ({{ number_format($discussion->comments_count) }})
                        </h2>
                    </div>
                    
                    <!-- Add Comment Form -->
                    @auth
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-lg flex-shrink-0">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="flex-1">
                                <textarea id="show-comment-textarea" placeholder="Share your thoughts on this discussion..."
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none shadow-sm"
                                    rows="3"></textarea>
                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex space-x-2">
                                        <button class="p-2 transition-all rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transform hover:scale-105" title="Add image (coming soon)">
                                            <i data-lucide="image" class="w-4 h-4 text-gray-500"></i>
                                        </button>
                                        <button class="p-2 transition-all rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transform hover:scale-105" title="Add emoji (coming soon)">
                                            <i data-lucide="smile" class="w-4 h-4 text-gray-500"></i>
                                        </button>
                                    </div>
                                    <button id="show-comment-btn" onclick="submitShowComment('{{ $discussion->slug }}')"
                                        class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="text-center py-8 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-xl">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                <i data-lucide="message-circle" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Join the discussion</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Share your thoughts and connect with the community</p>
                            <a href="{{ route('login') }}" 
                               class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                                Sign In to Comment
                            </a>
                        </div>
                    </div>
                    @endauth

                    <!-- Scrollable Comments List -->
                    <div class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800">
                        <div class="p-4 sm:p-6" id="show-comments-list">
                            @forelse($comments as $comment)
                                @include('home.discussions.partials.comment', ['comment' => $comment, 'depth' => 0, 'discussion' => $discussion])
                            @empty
                            <div class="text-center py-12">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i data-lucide="message-circle" class="w-10 h-10 text-gray-400"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No comments yet</h3>
                                <p class="text-gray-600 dark:text-gray-400">Be the first to share your thoughts and start the conversation!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Load More Comments Button -->
                    @if($comments->hasMorePages())
                    <div class="p-4 text-center border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 rounded-b-2xl">
                        <button id="load-more-show-comments" 
                                onclick="loadMoreShowComments({{ $comments->currentPage() + 1 }})"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                            <span class="button-text">Load More Comments</span>
                            <span class="loading-text hidden flex items-center justify-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Loading...
                            </span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Sidebar -->
            <div class="lg:col-span-1">
                <!-- Author Card -->
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                        About Author
                    </h3>
                    <div class="text-center">
                        <div class="relative inline-block">
                            <div class="w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                @if($discussion->user && $discussion->user->avatar_url)
                                    <img src="{{ $discussion->user->avatar_url }}" alt="{{ $discussion->user->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ strtoupper(substr($discussion->user->name ?? 'U', 0, 1)) }}
                                @endif
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-3 border-white rounded-full"></div>
                        </div>
                        <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ $discussion->user->name ?? 'Anonymous' }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Member since {{ $discussion->user->created_at->format('M Y') }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="text-center p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">0</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">Posts</div>
                            </div>
                            <div class="text-center p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">0</div>
                                <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">Following</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Discussions -->
                @if($relatedDiscussions->count() > 0)
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="layers" class="w-5 h-5 text-purple-600"></i>
                        Related Discussions
                    </h3>
                    <div class="space-y-3">
                        @foreach($relatedDiscussions as $related)
                        <a href="{{ route('discussion.show', $related->slug) }}" 
                           class="block p-4 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all transform hover:scale-102 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight mb-2 line-clamp-2">
                                {{ $related->title }}
                            </h4>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1 bg-red-100 text-red-600 px-2 py-1 rounded-full">
                                    <i data-lucide="heart" class="w-3 h-3"></i>
                                    {{ $related->likes_count }}
                                </span>
                                <span class="flex items-center gap-1 bg-blue-100 text-blue-600 px-2 py-1 rounded-full">
                                    <i data-lucide="message-circle" class="w-3 h-3"></i>
                                    {{ $related->comments_count }}
                                </span>
                                @php
                                    $diff = now()->diffInSeconds($related->created_at);
                                    if ($diff < 60) $timeStr = $diff . 's';
                                    elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm';
                                    elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h';
                                    elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd';
                                    elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo';
                                    else $timeStr = floor($diff / 31536000) . 'y';
                                @endphp
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $timeStr }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
<script>
// Store discussion slug for API calls
const currentDiscussionSlug = '{{ $discussion->slug }}';
let showCommentsPage = {{ $comments->currentPage() }};
let hasMoreShowComments = {{ $comments->hasMorePages() ? 'true' : 'false' }};

// Toggle reply form
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.toggle('hidden');
        if (!replyForm.classList.contains('hidden')) {
            const textarea = replyForm.querySelector('textarea');
            if (textarea) textarea.focus();
        }
    }
}

// Like discussion with optimistic updates
function likeDiscussion(slug) {
    const button = document.querySelector('.like-discussion-btn');
    const likesElement = document.getElementById('likes-count');
    const currentLikes = parseInt(likesElement.textContent.replace(/,/g, '')) || 0;
    const wasLiked = button.classList.contains('bg-red-100');
    
    button.disabled = true;
    
    // Optimistic update
    if (wasLiked) {
        button.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
        button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        likesElement.textContent = (currentLikes - 1).toLocaleString();
    } else {
        button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        button.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
        likesElement.textContent = (currentLikes + 1).toLocaleString();
    }
    
    fetch(`/discussion/${slug}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            likesElement.textContent = data.likes_count.toLocaleString();
            if (data.liked) {
                button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                button.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
            } else {
                button.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            }
        } else {
            // Revert optimistic update
            revertLikeState(button, likesElement, wasLiked, currentLikes);
        }
    })
    .catch(error => {
        console.error('Like error:', error);
        revertLikeState(button, likesElement, wasLiked, currentLikes);
        showToast('Error liking discussion. Please try again.', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}

function revertLikeState(button, likesElement, wasLiked, currentLikes) {
    if (wasLiked) {
        button.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
        button.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    } else {
        button.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        button.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
    }
    likesElement.textContent = currentLikes.toLocaleString();
}

// Like comment with optimistic updates
function likeComment(commentId) {
    const button = document.querySelector(`button[onclick="likeComment('${commentId}')"]`);
    const likesElement = button.querySelector('span');
    const currentLikes = parseInt(likesElement.textContent) || 0;
    const wasLiked = button.classList.contains('text-red-500');
    
    if (button.disabled) return;
    button.disabled = true;
    
    // Optimistic update
    if (wasLiked) {
        button.classList.remove('text-red-500', 'hover:text-red-600');
        button.classList.add('text-gray-500', 'hover:text-blue-600');
        likesElement.textContent = currentLikes - 1;
    } else {
        button.classList.remove('text-gray-500', 'hover:text-blue-600');
        button.classList.add('text-red-500', 'hover:text-red-600');
        likesElement.textContent = currentLikes + 1;
    }
    
    fetch(`/discussion/comment/${commentId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            likesElement.textContent = data.likes_count || '';
            if (data.liked) {
                button.classList.add('text-red-500', 'hover:text-red-600');
                button.classList.remove('text-gray-500', 'hover:text-blue-600');
            } else {
                button.classList.remove('text-red-500', 'hover:text-red-600');
                button.classList.add('text-gray-500', 'hover:text-blue-600');
            }
        } else {
            revertCommentLikeState(button, likesElement, wasLiked, currentLikes);
        }
    })
    .catch(error => {
        console.error('Comment like error:', error);
        revertCommentLikeState(button, likesElement, wasLiked, currentLikes);
        showToast('Error liking comment. Please try again.', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}

function revertCommentLikeState(button, likesElement, wasLiked, currentLikes) {
    if (wasLiked) {
        button.classList.add('text-red-500', 'hover:text-red-600');
        button.classList.remove('text-gray-500', 'hover:text-blue-600');
    } else {
        button.classList.remove('text-red-500', 'hover:text-red-600');
        button.classList.add('text-gray-500', 'hover:text-blue-600');
    }
    likesElement.textContent = currentLikes;
}

// Delete comment
function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) return;
    
    fetch(`/discussion/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (commentElement) {
                commentElement.remove();
                showToast('Comment deleted successfully', 'success');
            }
        } else {
            showToast('Error deleting comment', 'error');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showToast('Error deleting comment', 'error');
    });
}

// Submit main comment for show page
function submitShowComment(slug) {
    const textarea = document.getElementById('show-comment-textarea');
    const submitBtn = document.getElementById('show-comment-btn');
    
    if (submitBtn.disabled) return;
    
    const content = textarea.value.trim();
    if (!content) {
        showToast('Please enter a comment', 'error');
        return;
    }
    
    // Show spinner
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white inline-block mr-2"></div>Posting...';
    
    fetch(`/discussion/${slug}/comment-show`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            textarea.value = '';
            showToast('Comment posted successfully!', 'success');
            
            // Add new comment to the list
            if (data.comment_html) {
                const container = document.getElementById('show-comments-list');
                container.insertAdjacentHTML('afterbegin', data.comment_html);
                lucide.createIcons();
            }
            
            // Update comments count
            const countsElements = document.querySelectorAll('[data-comments-count]');
            countsElements.forEach(el => {
                el.textContent = data.comments_count.toLocaleString();
            });
        } else {
            showToast('Error posting comment: ' + (data.message || 'Please try again'), 'error');
        }
    })
    .catch(error => {
        console.error('Comment error:', error);
        showToast('Error posting comment. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

// Load more comments for show page
function loadMoreShowComments(page) {
    if (!hasMoreShowComments) return;
    
    const button = document.getElementById('load-more-show-comments');
    const buttonText = button.querySelector('.button-text');
    const loadingText = button.querySelector('.loading-text');
    
    buttonText.classList.add('hidden');
    loadingText.classList.remove('hidden');
    button.disabled = true;
    
    fetch(`/discussion/${currentDiscussionSlug}/comments/load-more?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('show-comments-list').insertAdjacentHTML('beforeend', data.html);
                hasMoreShowComments = data.has_more;
                showCommentsPage = data.next_page;
                
                if (!hasMoreShowComments) {
                    button.remove();
                }
                
                lucide.createIcons();
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            showToast('Error loading comments', 'error');
        })
        .finally(() => {
            buttonText.classList.remove('hidden');
            loadingText.classList.add('hidden');
            button.disabled = false;
        });
}

// Event delegation for load more replies (matches modal pattern)
document.addEventListener('click', function(e) {
    // Handle load more replies buttons (matching modal pattern)
    if (e.target.closest('.load-more-replies-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.load-more-replies-btn');
        const commentId = btn.getAttribute('data-comment-id');
        const nextPage = btn.getAttribute('data-next-page') || 2;
        
        // Show loading state
        btn.disabled = true;
        const originalText = btn.textContent;
        btn.textContent = 'Loading...';
        
        fetch(`/discussion/comment/${commentId}/replies/load-more?page=${nextPage}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const repliesContainer = document.getElementById(`replies-container-${commentId}`);
                    repliesContainer.insertAdjacentHTML('beforeend', data.html);
                    
                    if (!data.has_more) {
                        btn.remove();
                    } else {
                        // Update button for next page
                        const remaining = data.total_count - data.loaded_count;
                        btn.textContent = `Load ${remaining} more replies`;
                        btn.setAttribute('data-next-page', data.next_page);
                    }
                    
                    lucide.createIcons();
                } else {
                    btn.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error loading replies:', error);
                btn.textContent = originalText;
                showToast('Error loading replies', 'error');
            })
            .finally(() => {
                btn.disabled = false;
            });
    }

    // Handle comment likes (matching modal pattern)
    if (e.target.closest('.like-comment-btn')) {
        e.preventDefault();
        const btn = e.target.closest('.like-comment-btn');
        const commentId = btn.getAttribute('data-comment-id');
        
        if (btn.disabled) return;
        
        const likesSpan = btn.querySelector('.likes-count');
        const currentLikes = parseInt(likesSpan.textContent) || 0;
        const wasLiked = btn.classList.contains('text-red-500');
        
        btn.disabled = true;
        
        // Optimistic update
        if (wasLiked) {
            btn.classList.remove('text-red-500', 'hover:text-red-600');
            btn.classList.add('text-gray-500', 'hover:text-blue-600');
            likesSpan.textContent = currentLikes - 1 || '';
        } else {
            btn.classList.remove('text-gray-500', 'hover:text-blue-600');
            btn.classList.add('text-red-500', 'hover:text-red-600');
            likesSpan.textContent = currentLikes + 1;
        }
        
        fetch(`/discussion/comment/${commentId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likesSpan.textContent = data.likes_count || '';
                if (data.liked) {
                    btn.classList.add('text-red-500', 'hover:text-red-600');
                    btn.classList.remove('text-gray-500', 'hover:text-blue-600');
                } else {
                    btn.classList.remove('text-red-500', 'hover:text-red-600');
                    btn.classList.add('text-gray-500', 'hover:text-blue-600');
                }
            } else {
                // Revert optimistic update
                if (wasLiked) {
                    btn.classList.add('text-red-500', 'hover:text-red-600');
                    btn.classList.remove('text-gray-500', 'hover:text-blue-600');
                    likesSpan.textContent = currentLikes;
                } else {
                    btn.classList.remove('text-red-500', 'hover:text-red-600');
                    btn.classList.add('text-gray-500', 'hover:text-blue-600');
                    likesSpan.textContent = currentLikes;
                }
            }
        })
        .finally(() => {
            btn.disabled = false;
        });
    }
});

// Share discussion
function shareDiscussion(slug) {
    // Track share
    fetch(`/discussion/${slug}/share`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update shares count
            document.getElementById('shares-count').textContent = data.shares_count.toLocaleString();
            
            // Handle sharing
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    text: 'Check out this discussion',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Link copied to clipboard!', 'success');
                });
            }
        }
    })
    .catch(error => {
        console.error('Share error:', error);
        showToast('Error sharing discussion', 'error');
    });
}


// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[9999] px-4 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Placeholder functions
function bookmarkDiscussion() {
    showToast('Bookmark feature coming soon!', 'success');
}

function reportDiscussion() {
    showToast('Report feature coming soon!', 'success');
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Event delegation for dynamically added like buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.like-comment-btn')) {
            e.preventDefault();
            const button = e.target.closest('.like-comment-btn');
            const commentId = button.getAttribute('data-comment-id');
            if (commentId) {
                likeComment(commentId);
            }
        }
    });
});
</script>

@endsection