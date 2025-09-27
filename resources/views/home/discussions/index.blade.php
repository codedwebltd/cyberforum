@extends('inc.home.app')
@section('title', 'Discussions - ' . config('app.name'))
@section('content')

<main class="p-2 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
        @include('session-message.session-message')
        
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 text-sm overflow-x-auto whitespace-nowrap">
                        <a href="{{ route('home') }}" 
                           class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Home
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span class="font-medium">Discussions</span>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span>Community Hub</span>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Page Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Discussions
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Join the conversation and share your thoughts</p>
                    </div>
                    <a href="{{ route('discussion.create') }}" 
                       class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg flex items-center gap-2 w-fit">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Start Discussion
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 shadow-lg">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" id="search-discussions" 
                               placeholder="Search discussions..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <select class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>All Types</option>
                        <option value="discussion">Discussion</option>
                        <option value="question">Question</option>
                        <option value="announcement">Announcement</option>
                    </select>
                    
                    <select class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option>Latest</option>
                        <option value="trending">Trending</option>
                        <option value="hot">Hot</option>
                        <option value="most_liked">Most Liked</option>
                        <option value="most_commented">Most Commented</option>
                    </select>
                </div>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button class="px-3 py-1.5 text-sm bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                    All
                </button>
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Pinned
                </button>
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Featured
                </button>
                <button class="px-3 py-1.5 text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Unanswered
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Discussions List -->
                <div class="space-y-4">
                    @forelse($discussions as $discussion)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl group">
                        <div class="p-4 sm:p-6">
                            <!-- Discussion Header -->
                            <div class="flex items-start gap-4">
                                <!-- Author Avatar -->
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold shadow-lg">
                                        @if($discussion->user && $discussion->user->avatar_url)
                                            <img src="{{ $discussion->user->avatar_url }}" alt="{{ $discussion->user->name }}" class="w-full h-full rounded-full object-cover">
                                        @else
                                            {{ strtoupper(substr($discussion->user->name ?? 'U', 0, 1)) }}
                                        @endif
                                    </div>
                                    @if($discussion->user && $discussion->user->is_online)
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                    @endif
                                </div>
                                
                                <!-- Discussion Content -->
                                <div class="flex-1 min-w-0">
                                    <!-- Meta Info -->
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        @if($discussion->is_pinned)
                                        <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full flex items-center">
                                            <i data-lucide="pin" class="w-3 h-3 mr-1"></i>
                                            Pinned
                                        </span>
                                        @endif
                                        
                                        @if($discussion->is_featured)
                                        <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-full flex items-center">
                                            <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                            Featured
                                        </span>
                                        @endif

                                        <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-full">
                                            {{ ucfirst($discussion->type) }}
                                        </span>
                                        
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            by {{ $discussion->user->name ?? 'Anonymous' }}
                                        </span>
                                        
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            @php
                                                $diff = now()->diffInSeconds($discussion->created_at);
                                                if ($diff < 60) $timeStr = $diff . 's ago';
                                                elseif ($diff < 3600) $timeStr = floor($diff / 60) . 'm ago';
                                                elseif ($diff < 86400) $timeStr = floor($diff / 3600) . 'h ago';
                                                elseif ($diff < 2592000) $timeStr = floor($diff / 86400) . 'd ago';
                                                elseif ($diff < 31536000) $timeStr = floor($diff / 2592000) . 'mo ago';
                                                else $timeStr = floor($diff / 31536000) . 'y ago';
                                            @endphp
                                            {{ $timeStr }}
                                        </span>
                                    </div>
                                    
                                    <!-- Title -->
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                                        <a href="{{ route('discussion.show', $discussion->slug) }}" class="hover:underline">
                                            {{ $discussion->title }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Content Preview -->
                                    @if($discussion->excerpt)
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $discussion->excerpt }}
                                    </p>
                                    @endif
                                    
                                    <!-- Tags -->
                                    @if($discussion->tags && $discussion->tags->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($discussion->tags->take(4) as $tag)
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                            #{{ $tag->name }}
                                        </span>
                                        @endforeach
                                        @if($discussion->tags->count() > 4)
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                                            +{{ $discussion->tags->count() - 4 }}
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <!-- Engagement Stats -->
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="heart" class="w-4 h-4 text-red-500"></i>
                                            <span>{{ number_format($discussion->likes_count) }}</span>
                                        </div>
                                        
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="message-circle" class="w-4 h-4 text-blue-500"></i>
                                            <span>{{ number_format($discussion->comments_count) }}</span>
                                        </div>
                                        
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="eye" class="w-4 h-4 text-green-500"></i>
                                            <span>{{ number_format($discussion->views_count) }}</span>
                                        </div>
                                        
                                        @if($discussion->last_activity_at)
                                        <div class="flex items-center gap-1 ml-auto">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                            <span class="text-xs">
                                                @php
                                                    $lastDiff = now()->diffInSeconds($discussion->last_activity_at);
                                                    if ($lastDiff < 60) $lastTimeStr = 'now';
                                                    elseif ($lastDiff < 3600) $lastTimeStr = floor($lastDiff / 60) . 'm';
                                                    elseif ($lastDiff < 86400) $lastTimeStr = floor($lastDiff / 3600) . 'h';
                                                    elseif ($lastDiff < 2592000) $lastTimeStr = floor($lastDiff / 86400) . 'd';
                                                    else $lastTimeStr = $discussion->last_activity_at->format('M j');
                                                @endphp
                                                Last activity {{ $lastTimeStr }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Empty State -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-lg">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                            <i data-lucide="message-circle" class="w-12 h-12 text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No discussions yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                            Be the first to start a meaningful conversation with the community.
                        </p>
                        <a href="{{ route('discussion.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Start First Discussion
                        </a>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($discussions->hasPages())
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg p-6 mt-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $discussions->firstItem() ?? 0 }} to {{ $discussions->lastItem() ?? 0 }} of {{ $discussions->total() }} discussions
                        </div>
                        <div class="flex items-center gap-2">
                            {{ $discussions->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <!-- Community Stats -->
                {{-- <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                        Community Stats
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i data-lucide="message-square" class="w-4 h-4 text-blue-600"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Discussions</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($discussions->total() ?? 0) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i data-lucide="users" class="w-4 h-4 text-green-600"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Members</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">1.2k</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-purple-600"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Today</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">24</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Popular Tags -->
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="tag" class="w-5 h-5 text-purple-600"></i>
                        Popular Tags
                    </h3>
                    @if(isset($popularTags) && $popularTags->count() > 0)
                        <div class="max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800">
                            <div class="flex flex-wrap gap-2 pr-2">
                                @foreach($popularTags as $tag)
                                <button onclick="filterByTag('{{ $tag->slug ?? $tag->name }}')" 
                                        class="px-3 py-1.5 text-sm bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/30 dark:hover:text-blue-300 transition-colors cursor-pointer flex items-center gap-1 shadow-sm hover:shadow-md transform hover:scale-105">
                                    #{{ $tag->name }}
                                    @if(isset($tag->posts_count))
                                    <span class="text-xs opacity-75 bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded-full">({{ $tag->posts_count }})</span>
                                    @elseif(isset($tag->usage_count) && $tag->usage_count > 0)
                                    <span class="text-xs opacity-75 bg-blue-100 dark:bg-blue-800/50 px-1.5 py-0.5 rounded-full">({{ $tag->usage_count }})</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3"></i>
                                Click any tag to filter discussions • Scroll for more tags
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full flex items-center justify-center">
                                <i data-lucide="tag" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No popular tags yet</p>
                            <p class="text-xs text-gray-400 mt-1">Tags will appear as discussions are created</p>
                        </div>
                    @endif
                </div>

                <!-- Discussion Guidelines -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600"></i>
                        Community Guidelines
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Be respectful and constructive
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Search before posting duplicates
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Use clear and descriptive titles
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Tag your posts appropriately
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }
    
    // Search functionality placeholder
    const searchInput = document.getElementById('search-discussions');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            // Implement search functionality here
            console.log('Searching for:', e.target.value);
        });
    }
});

// Tag filtering functionality
function filterByTag(tagSlug) {
    // Build the filter URL
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('tag', tagSlug);
    currentUrl.searchParams.delete('page'); // Reset pagination
    
    // Navigate to filtered results
    window.location.href = currentUrl.toString();
    
    // Optional: Show loading state
    const button = event.target;
    const originalText = button.textContent;
    button.textContent = 'Loading...';
    button.disabled = true;
}
</script>

@endsection