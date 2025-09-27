@extends('inc.home.app')
@section('content')
    <!-- Main Content -->
    <main class="p-4 lg:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-4">
                <!-- Main Feed -->
                <div class="space-y-6 xl:col-span-3">
                    @include('session-message.session-message')
                    <!-- Mobile Search -->
                    <div class="p-6 sm:hidden forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="search" class="w-5 h-5 text-primary"></i>
                            <h2 class="text-lg font-semibold font-display">Search Community</h2>
                        </div>
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute w-4 h-4 transform -translate-y-1/2 left-3 top-1/2 text-muted-foreground"></i>
                            <input type="text" id="mobile-search-input"
                                placeholder="Search discussions, members, events..."
                                class="w-full py-3 pl-10 pr-4 text-sm border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent">
                        </div>
                        <!-- Live Search Results -->
                        <div id="mobile-search-results" class="hidden mt-4 overflow-y-auto max-h-60">
                            <div class="mb-3 text-sm text-muted-foreground">Search Results</div>
                            <div class="space-y-2">
                                <div
                                    class="p-3 transition-colors border rounded-lg cursor-pointer search-result-item hover:bg-muted border-border">
                                    <div class="flex items-center space-x-3">
                                        <i data-lucide="message-circle" class="w-4 h-4 text-primary"></i>
                                        <div>
                                            <div class="text-sm font-medium">React Best Practices</div>
                                            <div class="text-xs text-muted-foreground">in Discussions</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Announcements -->
                    <div class="p-6 forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="megaphone" class="w-5 h-5 text-primary"></i>
                            <h2 class="text-lg font-semibold font-display">Latest Announcements</h2>
                        </div>
                        <div class="p-4 transition-colors border rounded-lg cursor-pointer bg-primary/10 border-primary/20 hover:bg-primary/15"
                            id="announcement-card">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-primary">
                                    <i data-lucide="star" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="mb-1 font-medium truncate">New Feature Launch: Community Marketplace</h3>
                                    <p class="mb-2 text-sm text-muted-foreground truncate-lines-2">We're excited to announce
                                        our new marketplace feature where members can buy and sell services, showcase their
                                        skills, and connect with potential clients directly within our community platform...
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-medium text-primary">2h ago</span>
                                        <button class="text-xs font-medium text-primary hover:underline">Read More</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banner Ad -->
                    <div class="overflow-hidden forum-card rounded-xl">
                        <div class="relative p-6 text-center text-white bg-gradient-primary lg:p-8">
                            <h3 id="skills-title" class="mb-2 text-xl font-bold opacity-0 font-display lg:text-2xl">Boost
                                Your Skills</h3>
                            <p id="skills-desc" class="mb-4 text-sm opacity-0 lg:text-base truncate-lines-2">Join our
                                premium courses and accelerate your career with expert-led training</p>
                            <div class="relative inline-block">
                                <button id="skills-btn"
                                    class="bg-white text-primary px-6 py-2.5 rounded-lg font-medium hover:bg-white/95 transition-all transform hover:scale-105 opacity-0">
                                    Learn More
                                </button>
                                <div id="pointer-hand"
                                    class="absolute text-2xl transform -translate-y-1/2 opacity-0 -right-8 top-1/2 animate-bounce">
                                    👉
                                </div>
                            </div>
                        </div>
                        <div class="p-3 text-center">
                            <p class="text-xs text-muted-foreground">Sponsored Content</p>
                        </div>
                    </div>
                    <!-- Recent Discussions -->
                 <!-- Mind-Blowing Recent Discussions Section -->
<div class="p-6 forum-card rounded-xl">
    <!-- Stunning Header Section -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center gap-3 mb-4">
            <div class="relative">
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-xl">
                    <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
                </div>
                <div class="absolute -inset-2 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 opacity-30 animate-ping"></div>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 dark:from-white dark:via-blue-200 dark:to-purple-200 bg-clip-text text-transparent">
                Recent Discussions
            </h2>
        </div>
        
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-base sm:text-lg mb-6">
            Dive into the latest conversations shaping our community
        </p>
        
        <!-- Live Stats Bar -->
        <div class="flex flex-wrap justify-center items-center gap-3 sm:gap-6 text-sm">
            <div class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-full border border-white/20 dark:border-gray-700/20">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">Live Activity</span>
            </div>
            <div class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-full border border-white/20 dark:border-gray-700/20">
                <span class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm">{{ $discussions->total() }} Discussions</span>
            </div>
        </div>
    </div>

    <!-- Discussions List -->
    <div class="space-y-4 sm:space-y-6" id="discussions-container">
        @forelse($discussions as $discussion)
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
                                <div class="flex items-center gap-2 px-3 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium">
                                    <i data-lucide="message-slash" class="w-4 h-4"></i>
                                    <span class="hidden sm:inline">Comments Disabled</span>
                                    <span class="sm:hidden">Disabled</span>
                                </div>
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
        @empty
            <div class="text-center py-12 sm:py-16">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-3xl flex items-center justify-center shadow-xl">
                    <i data-lucide="message-square" class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">No discussions yet</h3>
                <p class="text-gray-600 dark:text-gray-400 text-base sm:text-lg">Be the first to spark a conversation!</p>
            </div>
        @endforelse
    </div>

    <!-- Load More Button -->
    @if($discussions->hasMorePages())
    <div class="text-center mt-8 sm:mt-12">
        <button id="load-more-discussions" onclick="loadMoreDiscussions()" 
                class="relative overflow-hidden px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-semibold text-base sm:text-lg rounded-2xl shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 group">
            <span class="button-text flex items-center justify-center gap-3">
                Load More Discussions
                <i data-lucide="chevron-down" class="w-5 h-5 group-hover:translate-y-1 transition-transform duration-300"></i>
            </span>
            <span class="loading-text hidden items-center justify-center gap-3">
                <div class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                Loading...
            </span>
        </button>
    </div>
    @endif
</div>

<style>
/* Line Clamp Utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom shadow for enhanced depth */
.shadow-3xl {
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}
</style>

                </div>

                <!-- Comments Modal -->
                <div id="comments-modal"
                    class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black/50">
                    <div class="bg-card rounded-xl border border-border w-full max-w-2xl max-h-[80vh] flex flex-col">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between p-4 border-b border-border">
                            <h3 class="text-lg font-semibold">Comments & Replies</h3>
                            <button id="close-comments" class="p-2 transition-colors rounded-lg hover:bg-muted">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <!-- Modal Content -->
                     
<div class="flex-1 p-4 overflow-y-auto" id="modal-scroll-container">
    <div id="comments-container" class="space-y-4">
        <!-- Comments will be loaded here -->
    </div>
    
    <!-- Load More Comments Button -->
    <div id="load-more-comments-container" class="mt-4 text-center hidden">
        <button id="load-more-comments" 
                onclick="loadMoreModalComments()" 
                class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors">
            <span class="button-text">Load More Comments</span>
            <span class="loading-text hidden flex items-center justify-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Loading...
            </span>
        </button>
    </div>
    
    <div id="comments-end" class="hidden text-center py-4 text-muted-foreground text-sm">
        No more comments to load
    </div>
</div>

                        <!-- Comment Input -->
                        <!-- Comment Input -->
<div class="p-4 border-t border-border">
    <div class="flex space-x-3">
        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-primary">
            @if(auth()->user() && auth()->user()->avatar_url)
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
            @else
                <span class="text-xs font-bold text-white">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}</span>
            @endif
        </div>
        <div class="flex-1">
            <textarea id="main-comment-textarea" placeholder="Write a comment..."
                class="w-full p-3 border rounded-lg resize-none bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50"
                rows="2"></textarea>
            <div class="flex items-center justify-between mt-2">
                <div class="flex space-x-2">
                    <button class="p-1 transition-colors rounded hover:bg-muted" title="Add image (coming soon)">
                        <i data-lucide="image" class="w-4 h-4 text-muted-foreground"></i>
                    </button>
                    <button class="p-1 transition-colors rounded hover:bg-muted" title="Add emoji (coming soon)">
                        <i data-lucide="smile" class="w-4 h-4 text-muted-foreground"></i>
                    </button>
                </div>
                <button id="main-comment-btn" onclick="submitMainComment()"
                    class="px-4 py-2 text-sm font-medium transition-colors rounded-lg bg-primary text-primary-foreground hover:bg-primary/90">
                    Post Comment
                </button>
            </div>
        </div>
    </div>
</div>


                    </div>
                </div>


                <!-- Announcement Modal -->
                <div id="announcement-modal"
                    class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black/50">
                    <div class="bg-card rounded-xl border border-border w-full max-w-3xl max-h-[85vh] flex flex-col">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between p-6 border-b border-border">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary">
                                    <i data-lucide="star" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">Latest Announcement</h3>
                                    <p class="text-sm text-muted-foreground">Posted 2 hours ago</p>
                                </div>
                            </div>
                            <button id="close-announcement" class="p-2 transition-colors rounded-lg hover:bg-muted">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div class="flex-1 p-6 overflow-y-auto">
                            <div class="prose-sm prose max-w-none">
                                <h1 class="mb-4 text-2xl font-bold gradient-text">New Feature Launch: Community Marketplace
                                </h1>

                                <div class="p-4 mb-6 border rounded-lg bg-primary/10 border-primary/20">
                                    <div class="flex items-center space-x-2 text-primary">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                        <span class="text-sm font-medium">Important Update</span>
                                    </div>
                                </div>

                                <p class="mb-4 text-base leading-relaxed">
                                    We're excited to announce our new marketplace feature where members can buy and sell
                                    services, showcase their skills, and connect with potential clients directly within our
                                    community platform.
                                </p>

                                <h2 class="mb-3 text-xl font-semibold text-foreground">Key Features</h2>
                                <ul class="pl-6 mb-4 space-y-2 list-disc">
                                    <li>Create professional service listings with detailed descriptions and pricing</li>
                                    <li>Browse and search through hundreds of available services</li>
                                    <li>Direct messaging system for client-freelancer communication</li>
                                    <li>Secure payment processing with escrow protection</li>
                                    <li>Rating and review system for quality assurance</li>
                                    <li>Portfolio showcase to display your best work</li>
                                </ul>

                                <h2 class="mb-3 text-xl font-semibold text-foreground">How to Get Started</h2>
                                <p class="mb-4 text-base leading-relaxed">
                                    Getting started is simple! Navigate to the Marketplace section in your sidebar and click
                                    "Create Listing" to post your first service. Whether you're offering web development,
                                    graphic design, consulting, or any other professional service, our platform makes it
                                    easy to connect with clients.
                                </p>

                                <div class="p-4 mb-4 border rounded-lg bg-accent/10 border-accent/20">
                                    <h3 class="mb-2 font-medium text-accent">Special Launch Offer</h3>
                                    <p class="text-sm text-muted-foreground">
                                        For the first month, we're waiving all marketplace fees! List your services and
                                        start earning without any platform charges.
                                    </p>
                                </div>

                                <h2 class="mb-3 text-xl font-semibold text-foreground">Security & Trust</h2>
                                <p class="mb-4 text-base leading-relaxed">
                                    We've implemented robust security measures including identity verification, secure
                                    payment processing, and dispute resolution mechanisms to ensure a safe and trustworthy
                                    environment for all transactions.
                                </p>

                                <h2 class="mb-3 text-xl font-semibold text-foreground">Coming Soon</h2>
                                <ul class="pl-6 mb-4 space-y-2 list-disc">
                                    <li>Advanced search filters and categories</li>
                                    <li>Subscription-based services</li>
                                    <li>Team collaboration tools</li>
                                    <li>Mobile app integration</li>
                                    <li>Analytics dashboard for service providers</li>
                                </ul>

                                <p class="mb-4 text-base leading-relaxed">
                                    We believe this marketplace will significantly enhance our community by creating new
                                    opportunities for members to monetize their skills and find quality services. Your
                                    feedback is invaluable as we continue to improve and expand these features.
                                </p>

                                <div class="p-4 mt-6 rounded-lg bg-muted">
                                    <p class="text-sm text-muted-foreground">
                                        Questions or feedback? Contact our support team at marketplace@techcommunity.com or
                                        join the discussion in our dedicated marketplace channel.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="p-6 border-t border-border">
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <button
                                    class="flex-1 px-4 py-2 font-medium transition-colors rounded-lg bg-primary text-primary-foreground hover:bg-primary/90">
                                    Visit Marketplace
                                </button>
                                <button
                                    class="flex-1 px-4 py-2 transition-colors rounded-lg bg-muted text-muted-foreground hover:bg-muted/80">
                                    Share Announcement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6 xl:col-span-1">
                    <!-- Recent Connections -->
                    <div class="p-5 forum-card rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="users" class="w-5 h-5 text-accent"></i>
                                <h3 class="font-semibold font-display">Connections</h3>
                            </div>
                            <span class="text-xs font-medium cursor-pointer text-accent hover:text-accent/80">View
                                All</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-primary">
                                    <span class="text-sm font-bold text-white">AC</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">Alex Chen</p>
                                    <p class="text-xs truncate text-muted-foreground">Full Stack Dev</p>
                                </div>
                                <button class="text-xs font-medium text-accent hover:text-accent/80">Connect</button>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-neon-pink">
                                    <span class="text-sm font-bold text-white">SK</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">Sarah Kim</p>
                                    <p class="text-xs truncate text-muted-foreground">UI/UX Designer</p>
                                </div>
                                <button class="text-xs font-medium text-accent hover:text-accent/80">Connect</button>
                            </div>
                        </div>
                    </div>

                    <!-- Birthday Celebrants -->
                    <div class="p-5 forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="cake" class="w-5 h-5 text-neon-pink"></i>
                            <h3 class="font-semibold font-display">Birthday Today</h3>
                        </div>
                        <div class="p-3 border rounded-lg bg-neon-pink/10 border-neon-pink/20">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-neon-pink">
                                    <i data-lucide="gift" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">Mike Rodriguez</p>
                                    <p class="text-xs truncate text-muted-foreground">Celebrating 28 years today!
                                    </p>
                                    <div class="flex items-center mt-1 space-x-1">
                                        <i data-lucide="party-popper" class="w-3 h-3 text-neon-pink"></i>
                                        <span class="text-xs font-medium text-neon-pink">Congratulations!</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trending Hashtags -->
                    <div class="p-5 forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="hash" class="w-5 h-5 text-primary"></i>
                            <h3 class="font-semibold font-display">Trending</h3>
                        </div>

                        <!-- Hashtag Search -->
                        <div class="relative mb-4">
                            <i data-lucide="search"
                                class="absolute w-3 h-3 transform -translate-y-1/2 left-3 top-1/2 text-muted-foreground"></i>
                            <input type="text" id="hashtag-search" placeholder="Search hashtags..."
                                class="w-full py-2 pl-8 pr-3 text-xs border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent">
                        </div>

                        <div id="hashtags-container" class="space-y-2">
                            <div
                                class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hashtag-item hover:bg-muted">
                                <span class="text-sm font-medium text-primary">#React2024</span>
                                <span class="text-xs text-muted-foreground">1.2k posts</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hashtag-item hover:bg-muted">
                                <span class="text-sm font-medium text-primary">#WebDev</span>
                                <span class="text-xs text-muted-foreground">856 posts</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hashtag-item hover:bg-muted">
                                <span class="text-sm font-medium text-primary">#TechCareer</span>
                                <span class="text-xs text-muted-foreground">432 posts</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hashtag-item hover:bg-muted">
                                <span class="text-sm font-medium text-primary">#AI</span>
                                <span class="text-xs text-muted-foreground">389 posts</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-2 transition-colors rounded-lg cursor-pointer hashtag-item hover:bg-muted">
                                <span class="text-sm font-medium text-primary">#Design</span>
                                <span class="text-xs text-muted-foreground">267 posts</span>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="p-5 forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="calendar" class="w-5 h-5 text-accent"></i>
                            <h3 class="font-semibold font-display">Events</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="p-3 transition-colors border rounded-lg border-border hover:border-primary/30">
                                <h4 class="mb-1 text-sm font-medium truncate">React Masterclass</h4>
                                <p class="mb-2 text-xs text-muted-foreground truncate-lines-2">Advanced React
                                    patterns and best practices</p>
                                <div class="flex items-center space-x-2 text-xs text-accent">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>Dec 15, 2024</span>
                                </div>
                            </div>
                            <div class="p-3 transition-colors border rounded-lg border-border hover:border-primary/30">
                                <h4 class="mb-1 text-sm font-medium truncate">Design Systems Workshop</h4>
                                <p class="mb-2 text-xs text-muted-foreground truncate-lines-2">Building scalable
                                    design systems</p>
                                <div class="flex items-center space-x-2 text-xs text-accent">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span>Dec 20, 2024</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Marketplace Preview -->
                    <div class="p-5 forum-card rounded-xl">
                        <div class="flex items-center mb-4 space-x-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-primary"></i>
                            <h3 class="font-semibold font-display">Marketplace</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="p-3 transition-colors border rounded-lg border-border hover:border-primary/30">
                                <h4 class="mb-1 text-sm font-medium truncate">Website Design Service</h4>
                                <p class="mb-2 text-xs truncate text-muted-foreground">Professional web design
                                    starting at $299</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-primary">$299</span>
                                    <span class="text-xs text-accent">View Details</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ad Space -->
                    <div class="p-4 text-center forum-card rounded-xl">
                        <div class="p-6 rounded-lg bg-muted">
                            <i data-lucide="image" class="w-8 h-8 mx-auto mb-2 text-muted-foreground"></i>
                            <p class="text-sm text-muted-foreground">Advertisement Space</p>
                            <p class="mt-1 text-xs text-muted-foreground">300x250</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
<script>
        // ============================================================================
        // GLOBAL VARIABLES
        // ============================================================================
        let currentPage = 1;
        let isLoading = false;
        let hasMorePages = {{ $discussions->hasMorePages() ? 'true' : 'false' }};
        let currentFilter = 'latest';

        //
        let currentCommentPage = 1;
        let currentPostId = null;
        let hasMoreComments = false;
        let isLoadingComments = false;

        // DOM Elements
        const discussionTabs = document.querySelectorAll('.discussion-tab');
        const discussionsContainer = document.getElementById('discussions-container');
        const commentsModal = document.getElementById('comments-modal');
        const closeCommentsBtn = document.getElementById('close-comments');
        const announcementCard = document.getElementById('announcement-card');
        const announcementModal = document.getElementById('announcement-modal');
        const closeAnnouncementBtn = document.getElementById('close-announcement');

        // ============================================================================
        // REPLY FORM TOGGLE FUNCTION - STEP 1
        // ============================================================================
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                if (replyForm.classList.contains('hidden')) {
                    replyForm.classList.remove('hidden');
                } else {
                    replyForm.classList.add('hidden');
                }
            }
        }

        // ============================================================================
        // DELETE COMMENT FUNCTION - STEP 2
        // ============================================================================
        function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            
            fetch(`/discussion/comment/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                    if (commentElement) {
                        commentElement.remove();
                    }
                } else {
                    alert('Error deleting comment');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                alert('Error deleting comment');
            });
        }

        // ============================================================================
        // COMMENT LIKE FUNCTION - STEP 3 (OPTIMISTIC)
        // ============================================================================
        function likeComment(commentId, button) {
            if (button.disabled) return;
            
            button.disabled = true;
            const likesSpan = button.querySelector('.likes-count');
            const currentLikes = parseInt(likesSpan.textContent) || 0;
            const wasLiked = button.classList.contains('text-red-500');
            
            // Optimistic update - same logic as discussion likes
            if (wasLiked) {
                // Unlike
                button.classList.remove('text-red-500', 'hover:text-red-600');
                button.classList.add('text-muted-foreground', 'hover:text-primary');
                likesSpan.textContent = currentLikes - 1;
            } else {
                // Like
                button.classList.add('text-red-500', 'hover:text-red-600');
                button.classList.remove('text-muted-foreground', 'hover:text-primary');
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
                    // Update with actual server response
                    likesSpan.textContent = data.likes_count;
                    
                    if (data.liked) {
                        button.classList.add('text-red-500', 'hover:text-red-600');
                        button.classList.remove('text-muted-foreground', 'hover:text-primary');
                    } else {
                        button.classList.remove('text-red-500', 'hover:text-red-600');
                        button.classList.add('text-muted-foreground', 'hover:text-primary');
                    }
                } else {
                    // Revert optimistic update on failure
                    revertCommentLikeState(button, likesSpan, wasLiked, currentLikes);
                }
            })
            .catch(error => {
                console.error('Comment like error:', error);
                // Revert optimistic update on error
                revertCommentLikeState(button, likesSpan, wasLiked, currentLikes);
            })
            .finally(() => {
                button.disabled = false;
            });
        }

        function revertCommentLikeState(button, likesSpan, wasLiked, currentLikes) {
            if (wasLiked) {
                button.classList.add('text-red-500', 'hover:text-red-600');
                button.classList.remove('text-muted-foreground', 'hover:text-primary');
            } else {
                button.classList.remove('text-red-500', 'hover:text-red-600');
                button.classList.add('text-muted-foreground', 'hover:text-primary');
            }
            likesSpan.textContent = currentLikes;
        }

        // ============================================================================
        // REPLY SUBMISSION FUNCTION - STEP 4 (AJAX WITH SPINNER) - SIMPLIFIED
        // ============================================================================
        function submitReply(commentId, textarea, replyButton) {
            if (replyButton.disabled) return;
            
            const content = textarea.value.trim();
            if (!content) {
                showToast('Please enter a reply', 'error');
                return;
            }
            
            // Show spinner and disable
            replyButton.disabled = true;
            const originalText = replyButton.textContent;
            replyButton.innerHTML = '<div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white inline-block mr-1"></div>Posting...';
            
            fetch(`/discussion/comment/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    parent_id: commentId,
                    post_id: currentPostId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear textarea and hide form
                    textarea.value = '';
                    toggleReplyForm(commentId);
                    
                    showToast('Reply posted successfully!', 'success');
                    
                    // Add the new reply to the DOM immediately
                    if (data.reply_html) {
                        let repliesContainer = document.getElementById(`replies-container-${commentId}`);
                        if (repliesContainer) {
                            // Show the container if it was hidden
                            repliesContainer.style.display = 'block';
                            repliesContainer.insertAdjacentHTML('beforeend', data.reply_html);
                            lucide.createIcons();
                        }
                    }
                } else {
                    showToast('Error posting reply: ' + (data.message || 'Please try again'), 'error');
                }
            })
            .catch(error => {
                console.error('Reply error:', error);
                showToast('Error posting reply. Please try again.', 'error');
            })
            .finally(() => {
                replyButton.disabled = false;
                replyButton.textContent = originalText;
            });
        }

        // ============================================================================
        // MAIN COMMENT SUBMISSION FUNCTION - STEP 5
        // ============================================================================
        function submitMainComment() {
            const textarea = document.getElementById('main-comment-textarea');
            const submitBtn = document.getElementById('main-comment-btn');
            
            if (submitBtn.disabled) return;
            
            const content = textarea.value.trim();
            if (!content) {
                showToast('Please enter a comment', 'error');
                return;
            }
            
            // Show spinner
            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white inline-block mr-1"></div>Posting...';
            
            fetch(`/discussion/comment/main`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    content: content,
                    post_id: currentPostId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    textarea.value = '';
                    showToast('Comment posted successfully!', 'success');
                    
                    // Add new comment to the top of the comments list
                    if (data.comment_html) {
                        const container = document.getElementById('comments-container');
                        container.insertAdjacentHTML('afterbegin', data.comment_html);
                        lucide.createIcons();
                    }
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

        // ============================================================================
        // LOAD MORE REPLIES FUNCTION - STEP 6
        // ============================================================================
        function loadMoreReplies(commentId, button) {
            if (button.disabled) return;
            
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary inline-block mr-2"></div>Loading...';
            
            const page = button.getAttribute('data-next-page') || 2;
            
            fetch(`/discussion/comment/${commentId}/replies/load-more?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const repliesContainer = document.getElementById(`replies-container-${commentId}`);
                        if (repliesContainer) {
                            repliesContainer.insertAdjacentHTML('beforeend', data.html);
                            lucide.createIcons();
                        }
                        
                        if (data.has_more) {
                            button.setAttribute('data-next-page', data.next_page);
                            const remaining = data.total_count - data.loaded_count;
                            button.innerHTML = `Load ${remaining} more replies`;
                            button.disabled = false;
                        } else {
                            button.remove();
                        }
                    } else {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error loading replies:', error);
                    button.innerHTML = 'Error loading replies';
                });
        }

        // ============================================================================
        // TOAST NOTIFICATION FUNCTION
        // ============================================================================
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-[9999] px-4 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // ============================================================================
        // DISCUSSION FILTERING FUNCTIONS
        // ============================================================================
        function loadDiscussions(filter = 'latest') {
            discussionsContainer.style.opacity = '0.5';

            fetch(`/discussions/filter?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        discussionsContainer.innerHTML = data.html;
                        lucide.createIcons();

                        // Reset pagination state
                        currentPage = 1;
                        hasMorePages = true;
                        toggleLoadMoreButton();
                    }
                })
                .catch(error => {
                    console.error('Filter error:', error);
                })
                .finally(() => {
                    discussionsContainer.style.opacity = '1';
                });
        }

        // ============================================================================
        // PAGINATION FUNCTIONS
        // ============================================================================
        function loadMoreDiscussions() {
            if (isLoading || !hasMorePages) return;

            isLoading = true;
            const loadMoreBtn = document.getElementById('load-more-discussions');
            const loadingText = loadMoreBtn.querySelector('.loading-text');
            const buttonText = loadMoreBtn.querySelector('.button-text');

            // Show loading state
            buttonText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            loadMoreBtn.disabled = true;

            fetch(`/discussions/load-more?page=${currentPage + 1}&filter=${currentFilter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        discussionsContainer.insertAdjacentHTML('beforeend', data.html);
                        currentPage = data.next_page - 1;
                        hasMorePages = data.has_more;

                        // Re-initialize lucide icons for new content
                        lucide.createIcons();
                        toggleLoadMoreButton();
                    }
                })
                .catch(error => {
                    console.error('Error loading more discussions:', error);
                })
                .finally(() => {
                    isLoading = false;
                    buttonText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    loadMoreBtn.disabled = false;
                });
        }

        function toggleLoadMoreButton() {
            const loadMoreBtn = document.getElementById('load-more-discussions');
            if (loadMoreBtn) {
                if (hasMorePages) {
                    loadMoreBtn.classList.remove('hidden');
                } else {
                    loadMoreBtn.classList.add('hidden');
                }
            }
        }

        // ============================================================================
        // LIKE FUNCTIONS (OPTIMISTIC UPDATES)
        // ============================================================================
        function likeDiscussion(slug) {
            const button = document.querySelector(`button[onclick="likeDiscussion('${slug}')"]`);
            const likesElement = button.querySelector('span');
            const currentLikes = parseInt(likesElement.textContent) || 0;

            // Optimistic update - show immediately
            const wasLiked = button.classList.contains('text-red-500');

            if (wasLiked) {
                // Optimistically unlike
                button.classList.remove('text-red-500', 'hover:text-red-600');
                button.classList.add('text-muted-foreground', 'hover:text-primary');
                likesElement.textContent = currentLikes - 1;
            } else {
                // Optimistically like
                button.classList.add('text-red-500', 'hover:text-red-600');
                button.classList.remove('text-muted-foreground', 'hover:text-primary');
                likesElement.textContent = currentLikes + 1;
            }

            // Disable button to prevent double clicks
            button.disabled = true;

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
                        // Update with actual server response
                        likesElement.textContent = data.likes_count;

                        if (data.liked) {
                            button.classList.add('text-red-500', 'hover:text-red-600');
                            button.classList.remove('text-muted-foreground', 'hover:text-primary');
                        } else {
                            button.classList.remove('text-red-500', 'hover:text-red-600');
                            button.classList.add('text-muted-foreground', 'hover:text-primary');
                        }
                    } else {
                        // Revert optimistic update on failure
                        revertLikeState(button, likesElement, wasLiked, currentLikes);
                    }
                })
                .catch(error => {
                    // Revert optimistic update on error
                    revertLikeState(button, likesElement, wasLiked, currentLikes);
                    console.error('Like error:', error);
                })
                .finally(() => {
                    button.disabled = false;
                });
        }

        function revertLikeState(button, likesElement, wasLiked, currentLikes) {
            if (wasLiked) {
                button.classList.add('text-red-500', 'hover:text-red-600');
                button.classList.remove('text-muted-foreground', 'hover:text-primary');
            } else {
                button.classList.remove('text-red-500', 'hover:text-red-600');
                button.classList.add('text-muted-foreground', 'hover:text-primary');
            }
            likesElement.textContent = currentLikes;
        }

        // ============================================================================
        // COMMENT MODAL FUNCTIONS
        // ============================================================================
        function openCommentsModal(postId, title, slug) {
            // Store the slug for reply submissions
            window.currentDiscussionSlug = slug;
            
            // Update modal title
            document.querySelector('#comments-modal h3').textContent = `Comments - ${title}`;

            // Load comments for this specific post
            loadCommentsForPost(postId);

            // Show modal
            commentsModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function loadCommentsForPost(postId) {
            currentPostId = postId;
            currentCommentPage = 1;
            hasMoreComments = false;
            isLoadingComments = false;
            
            const container = document.getElementById('comments-container');
            const loadMoreContainer = document.getElementById('load-more-comments-container');
            const endIndicator = document.getElementById('comments-end');
            
            container.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div></div>';
            loadMoreContainer.classList.add('hidden');
            endIndicator.classList.add('hidden');
            
            fetch(`/discussion/comments/${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        container.innerHTML = data.html;
                        hasMoreComments = data.has_more;
                        currentCommentPage = data.current_page;
                        
                        if (hasMoreComments) {
                            loadMoreContainer.classList.remove('hidden');
                        } else if (data.total === 0) {
                            container.innerHTML = '<div class="text-center py-4 text-muted-foreground">No comments yet</div>';
                        } else {
                            endIndicator.classList.remove('hidden');
                        }
                        
                        lucide.createIcons();
                    } else {
                        container.innerHTML = '<div class="text-center py-4 text-muted-foreground">No comments yet</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading comments:', error);
                    container.innerHTML = '<div class="text-center py-4 text-red-500">Error loading comments</div>';
                });
        }

        function closeCommentsModal() {
            commentsModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function loadMoreModalComments() {
            if (isLoadingComments || !hasMoreComments || !currentPostId) return;
            
            isLoadingComments = true;
            const loadMoreBtn = document.getElementById('load-more-comments');
            const buttonText = loadMoreBtn.querySelector('.button-text');
            const loadingText = loadMoreBtn.querySelector('.loading-text');
            
            buttonText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            loadMoreBtn.disabled = true;
            
            fetch(`/discussion/comments/${currentPostId}/load-more?page=${currentCommentPage + 1}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('comments-container');
                        container.insertAdjacentHTML('beforeend', data.html);
                        
                        currentCommentPage = data.current_page;
                        hasMoreComments = data.has_more;
                        
                        if (!hasMoreComments) {
                            document.getElementById('load-more-comments-container').classList.add('hidden');
                            document.getElementById('comments-end').classList.remove('hidden');
                        }
                        
                        lucide.createIcons();
                    }
                })
                .catch(error => {
                    console.error('Error loading more comments:', error);
                })
                .finally(() => {
                    isLoadingComments = false;
                    buttonText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                    loadMoreBtn.disabled = false;
                });
        }

        // ============================================================================
        // ANNOUNCEMENT MODAL FUNCTIONS
        // ============================================================================
        function openAnnouncementModal() {
            announcementModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAnnouncementModal() {
            announcementModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ============================================================================
        // HASHTAG SEARCH FUNCTION
        // ============================================================================
        function initHashtagSearch() {
            const hashtagSearch = document.getElementById('hashtag-search');
            const hashtagItems = document.querySelectorAll('.hashtag-item');

            if (hashtagSearch) {
                hashtagSearch.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase();

                    hashtagItems.forEach(item => {
                        const hashtag = item.querySelector('span').textContent.toLowerCase();
                        if (hashtag.includes(query)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        }

        // ============================================================================
        // ANIMATED BANNER FUNCTIONS
        // ============================================================================
        function animateBanner() {
            const title = document.getElementById('skills-title');
            const desc = document.getElementById('skills-desc');
            const btn = document.getElementById('skills-btn');
            const pointer = document.getElementById('pointer-hand');

            if (!title || !desc || !btn || !pointer) return;

            // Title animation
            setTimeout(() => {
                title.style.opacity = '1';
            }, 500);

            // Description animation
            setTimeout(() => {
                desc.style.opacity = '1';
                desc.style.animation = 'fadeInUp 0.8s ease-out';
            }, 1000);

            // Button and pointer animation
            setTimeout(() => {
                btn.style.opacity = '1';
                btn.style.animation = 'fadeInUp 0.6s ease-out';
                setTimeout(() => {
                    pointer.style.opacity = '1';
                    // Continuous pointer pulse
                    setInterval(() => {
                        pointer.style.transform = 'translateY(-50%) scale(1.2)';
                        setTimeout(() => {
                            pointer.style.transform = 'translateY(-50%) scale(1)';
                        }, 300);
                    }, 2000);
                }, 600);
            }, 2000);
        }

        // ============================================================================
        // EVENT LISTENERS SETUP
        // ============================================================================
        function initEventListeners() {
            // Discussion tab filtering
            discussionTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const filter = tab.id.replace('tab-', '');
                    currentFilter = filter;

                    // Update UI
                    discussionTabs.forEach(t => {
                        t.classList.remove('bg-primary', 'text-primary-foreground', 'font-medium');
                        t.classList.add('bg-muted', 'text-muted-foreground');
                    });
                    tab.classList.add('bg-primary', 'text-primary-foreground', 'font-medium');

                    // Load filtered discussions
                    loadDiscussions(filter);
                });
            });

            // Comments modal events
            if (closeCommentsBtn) {
                closeCommentsBtn.addEventListener('click', closeCommentsModal);
            }

            if (commentsModal) {
                commentsModal.addEventListener('click', (e) => {
                    if (e.target === commentsModal) {
                        closeCommentsModal();
                    }
                });
            }

            // Announcement modal events
            if (announcementCard) {
                announcementCard.addEventListener('click', openAnnouncementModal);
            }

            if (closeAnnouncementBtn) {
                closeAnnouncementBtn.addEventListener('click', closeAnnouncementModal);
            }

            if (announcementModal) {
                announcementModal.addEventListener('click', (e) => {
                    if (e.target === announcementModal) {
                        closeAnnouncementModal();
                    }
                });
            }

            // Keyboard events
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (!commentsModal.classList.contains('hidden')) {
                        closeCommentsModal();
                    }
                    if (!announcementModal.classList.contains('hidden')) {
                        closeAnnouncementModal();
                    }
                }
            });

            // Dynamic event delegation for reply buttons and comment likes
            document.addEventListener('click', function(e) {
                if (e.target.closest('.replies-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.replies-btn');
                    const postId = btn.getAttribute('data-post-id');
                    const title = btn.closest('.discussion-item').querySelector('h3').textContent;
                    openCommentsModal(postId, title);
                }

                // Handle comment/reply likes
                if (e.target.closest('.like-comment-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.like-comment-btn');
                    const commentId = btn.getAttribute('data-comment-id');
                    likeComment(commentId, btn);
                }

                // Handle load more replies buttons
                if (e.target.closest('.load-more-replies-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.load-more-replies-btn');
                    const commentId = btn.getAttribute('data-comment-id');
                    loadMoreReplies(commentId, btn);
                }
            });
        }

        // Show toast for disabled comments
function showCommentsDisabledToast() {
    showToast('Comments are not allowed for this post', 'error');
}

        // ============================================================================
        // INITIALIZATION
        // ============================================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide Icons
            lucide.createIcons();

            // Setup all event listeners
            initEventListeners();

            // Initialize hashtag search
            initHashtagSearch();

            // Start banner animation
            setTimeout(animateBanner, 1000);

            // Initialize load more button state
            toggleLoadMoreButton();

            // Smooth page load animation
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.3s ease';
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
@endsection
