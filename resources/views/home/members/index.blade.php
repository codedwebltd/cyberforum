@extends('inc.home.app')
@section('title', 'Members - ' . config('app.name'))
<!-- Members blade file -->
@section('content')

<main class="p-3 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 text-sm overflow-x-auto whitespace-nowrap">
                        <a href="/home" 
                           class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Home
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span class="font-medium">Members</span>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span>Community</span>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Header Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 text-center">
                <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ number_format($totalMembers) }}</div>
                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Total</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 text-center">
                <div class="text-lg sm:text-2xl font-bold text-green-600">{{ number_format($onlineMembers) }}</div>
                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Online</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 text-center col-span-2 sm:col-span-1">
                <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ number_format($verifiedMembers) }}</div>
                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Verified</div>
            </div>
        </div>

        <!-- Search & Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Live Search -->
                <div class="flex-1 relative">
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input 
                            type="text" 
                            id="member-search"
                            placeholder="Search members..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    
                    <!-- Live Search Results -->
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg mt-1 max-h-60 overflow-y-auto z-20 hidden shadow-lg">
                        <!-- Results will be populated here -->
                    </div>
                </div>
                
                <!-- View Toggle -->
                <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                    <button id="list-view" class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors view-toggle active">
                        <i data-lucide="list" class="w-4 h-4 mr-1.5"></i>
                        <span class="hidden sm:inline">List</span>
                    </button>
                    <button id="grid-view" class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors view-toggle">
                        <i data-lucide="grid-3x3" class="w-4 h-4 mr-1.5"></i>
                        <span class="hidden sm:inline">Grid</span>
                    </button>
                </div>
                
                <!-- Sort -->
                <select id="sort-select" class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="recent">Recently Active</option>
                    <option value="points">Most Points</option>
                    <option value="followers">Most Followers</option>
                    <option value="alphabetical">A-Z</option>
                </select>
            </div>
        </div>

        <!-- Members Container -->
        <div id="members-container" class="space-y-4">
            <!-- List View (Default) -->
            <div id="members-list">
                @foreach($members as $member)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-all duration-200 member-card cursor-pointer" data-member-id="{{ $member->id }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                    @if($member->avatar_url)
                                    <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                    <span class="text-sm sm:text-lg font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                
                                @if($member->last_active_at && $member->last_active_at->diffInMinutes(now()) <= 15)
                                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                @endif
                            </div>
                            
                            <!-- Member Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white truncate">{{ $member->name }}</h3>
                                    @if($member->is_verified)
                                    <i data-lucide="badge-check" class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600 flex-shrink-0"></i>
                                    @endif
                                </div>
                                
                                <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    @if($member->username)
                                    <span class="truncate">{{ '@' . $member->username }}</span>
                                    @endif
                                    
                                    @if($member->location)
                                    <span class="hidden sm:flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                        {{ $member->location }}
                                    </span>
                                    @endif
                                </div>
                                
                                @if($member->bio)
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-1 hidden sm:block">{{ $member->bio }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Stats & Actions -->
                        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                            <!-- Stats (Hidden on mobile) -->
                            <div class="hidden md:flex items-center gap-4 text-sm">
                                <div class="text-center">
                                    <div class="font-semibold text-blue-600">{{ number_format($member->points) }}</div>
                                    <div class="text-xs text-gray-500">Points</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-green-600">{{ number_format($member->followers_count) }}</div>
                                    <div class="text-xs text-gray-500">Followers</div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-1 sm:gap-2">
                                <button class="px-2 sm:px-4 py-1.5 sm:py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <span class="hidden sm:inline">Follow</span>
                                    <i data-lucide="user-plus" class="w-3 h-3 sm:hidden"></i>
                                </button>
                                <button onclick="window.location.href='/members/chat/{{ $member->id }}'; event.stopPropagation();" class="p-1.5 sm:p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <i data-lucide="message-circle" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Grid View (Hidden by default) -->
            <div id="members-grid" class="hidden grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
                @foreach($members as $member)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 sm:p-4 text-center hover:shadow-lg transition-all duration-200 member-card cursor-pointer" data-member-id="{{ $member->id }}">
                    <div class="relative inline-block mb-3">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center mx-auto">
                            @if($member->avatar_url)
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-lg sm:text-xl font-bold text-white">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        
                        @if($member->last_active_at && $member->last_active_at->diffInMinutes(now()) <= 15)
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        @endif
                        
                        @if($member->is_verified)
                        <div class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-blue-600 rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-2 h-2 sm:w-3 sm:h-3 text-white"></i>
                        </div>
                        @endif
                    </div>
                    
                    <h3 class="font-semibold text-sm sm:text-base text-gray-900 dark:text-white mb-1 truncate">{{ $member->name }}</h3>
                    @if($member->username)
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2 sm:mb-3 truncate">{{ '@' . $member->username }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3 sm:mb-4 text-xs sm:text-sm">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-1.5 sm:p-2">
                            <div class="font-semibold text-blue-600 text-xs sm:text-sm">{{ number_format($member->points) }}</div>
                            <div class="text-xs text-gray-500">Points</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-1.5 sm:p-2">
                            <div class="font-semibold text-green-600 text-xs sm:text-sm">{{ number_format($member->followers_count) }}</div>
                            <div class="text-xs text-gray-500">Followers</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-1 sm:gap-2">
                        <button class="flex-1 px-2 sm:px-3 py-1.5 sm:py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <span class="hidden sm:inline">Follow</span>
                            <i data-lucide="user-plus" class="w-3 h-3 sm:hidden"></i>
                        </button>
                        <button onclick="window.location.href='/members/chat/{{ $member->id }}'; event.stopPropagation();" class="px-2 sm:px-3 py-1.5 sm:py-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors">
                            <i data-lucide="message-circle" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mt-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} members
                </div>
                
                <div class="flex items-center space-x-1">
                    @if (!$members->onFirstPage())
                    <a href="{{ $members->previousPageUrl() }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                    @endif

                    @foreach ($members->getUrlRange(1, $members->lastPage()) as $page => $url)
                        @if ($page == $members->currentPage())
                        <span class="px-3 py-2 bg-blue-600 text-white rounded-lg">{{ $page }}</span>
                        @else
                        <a href="{{ $url }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($members->hasMorePages())
                    <a href="{{ $members->nextPageUrl() }}" class="px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Member Modal -->
    <div id="member-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div id="modal-content">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
</main>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.view-toggle {
    color: #6b7280;
    background: transparent;
}

.view-toggle.active {
    color: #1f2937;
    background: white;
}

.dark .view-toggle {
    color: #9ca3af;
}

.dark .view-toggle.active {
    color: #f9fafb;
    background: #374151;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // View Toggle
    const listViewBtn = document.getElementById('list-view');
    const gridViewBtn = document.getElementById('grid-view');
    const membersList = document.getElementById('members-list');
    const membersGrid = document.getElementById('members-grid');
    
    listViewBtn.addEventListener('click', function() {
        this.classList.add('active');
        gridViewBtn.classList.remove('active');
        membersList.classList.remove('hidden');
        membersGrid.classList.add('hidden');
    });
    
    gridViewBtn.addEventListener('click', function() {
        this.classList.add('active');
        listViewBtn.classList.remove('active');
        membersGrid.classList.remove('hidden');
        membersList.classList.add('hidden');
    });
    
    // Live Search
    const searchInput = document.getElementById('member-search');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`/members/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        searchResults.innerHTML = data.map(member => `
                            <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0" data-member-id="${member.id}">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                        ${member.avatar_url ? `<img src="${member.avatar_url}" class="w-full h-full object-cover">` : `<span class="text-sm font-bold text-white">${member.name.charAt(0).toUpperCase()}</span>`}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">${member.name}</div>
                                        ${member.username ? `<div class="text-sm text-gray-600 dark:text-gray-400">@${member.username}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        `).join('');
                        searchResults.classList.remove('hidden');
                    } else {
                        searchResults.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.classList.add('hidden');
                });
        }, 300);
    });
    
    // Search result click
    searchResults.addEventListener('click', function(e) {
        const memberItem = e.target.closest('[data-member-id]');
        if (memberItem) {
            const memberId = memberItem.dataset.memberId;
            window.location.href = `/members?user=${memberId}`;
        }
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
    
    // Member card click for modal
    document.addEventListener('click', function(e) {
        const memberCard = e.target.closest('.member-card');
        if (memberCard && !e.target.closest('button')) {
            const memberId = memberCard.dataset.memberId;
            openMemberModal(memberId);
        }
    });
    
// Open member modal
    function openMemberModal(memberId) {
        // Show modal with loading spinner immediately
        document.getElementById('modal-content').innerHTML = `
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Loading...</h3>
                    <button onclick="closeMemberModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="relative">
                        <div class="w-12 h-12 border-4 border-gray-200 dark:border-gray-600 border-t-blue-600 rounded-full animate-spin"></div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mt-4 text-sm">Loading member details...</p>
                </div>
            </div>
        `;
        
        // Show the modal
        document.getElementById('member-modal').classList.remove('hidden');
        document.getElementById('member-modal').classList.add('flex');
        lucide.createIcons();
        
        // Fetch member data
        fetch(`/members/${memberId}/details`)
            .then(response => response.json())
            .then(member => {
                document.getElementById('modal-content').innerHTML = `
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Member Details</h3>
                            <button onclick="closeMemberModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center mx-auto mb-4">
                                ${member.avatar_url ? `<img src="${member.avatar_url}" class="w-full h-full object-cover">` : `<span class="text-2xl font-bold text-white">${member.name.charAt(0).toUpperCase()}</span>`}
                            </div>
                            
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">${member.name}</h4>
                            ${member.username ? `<p class="text-gray-600 dark:text-gray-400">@${member.username}</p>` : ''}
                            ${member.location ? `<p class="text-sm text-gray-500 mt-1">${member.location}</p>` : ''}
                        </div>
                        
                        ${member.bio ? `<div class="mb-6"><h5 class="font-semibold text-gray-900 dark:text-white mb-2">About</h5><p class="text-gray-600 dark:text-gray-300">${member.bio}</p></div>` : ''}
                        
                        <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="font-bold text-blue-600">${member.points.toLocaleString()}</div>
                                <div class="text-xs text-gray-500">Points</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="font-bold text-green-600">${member.followers_count.toLocaleString()}</div>
                                <div class="text-xs text-gray-500">Followers</div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="font-bold text-purple-600">${member.posts_count.toLocaleString()}</div>
                                <div class="text-xs text-gray-500">Posts</div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <button class="flex-1 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Follow
                            </button>
                           <button onclick="window.location.href='/members/chat/${member.id}'; closeMemberModal();" class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Message
                            </button>
                        </div>
                    </div>
                `;
                lucide.createIcons();
            })
            .catch(error => {
                console.error('Error loading member details:', error);
                // Show error state
                document.getElementById('modal-content').innerHTML = `
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Error</h3>
                            <button onclick="closeMemberModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm text-center">
                                Unable to load member details. Please try again.
                            </p>
                            <button onclick="openMemberModal(${memberId})" class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Retry
                            </button>
                        </div>
                    </div>
                `;
                lucide.createIcons();
            });
    }
    
    // Close modal
    window.closeMemberModal = function() {
        document.getElementById('member-modal').classList.add('hidden');
        document.getElementById('member-modal').classList.remove('flex');
    }
    
    // Sort functionality
    document.getElementById('sort-select').addEventListener('change', function() {
        const sort = this.value;
        const url = new URL(window.location);
        url.searchParams.set('sort', sort);
        window.location.href = url.toString();
    });
});
</script>

@endsection