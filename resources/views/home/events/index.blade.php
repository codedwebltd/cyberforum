@extends('inc.home.app')
@section('title', 'Events - ' . config('app.name'))
@section('content')

<main class="p-3 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
        @include('session-message.session-message')
        
 <!-- Breadcrumb -->
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 text-sm overflow-x-auto whitespace-nowrap">
                        <a href="/" 
                           class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Home
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <a href="{{ route('events.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            Events
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <a href="{{ route('events.create') }}">
                        <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span class="font-medium">Create Events</span>
                        </div>
                    </a>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span>Event center</span>
                    </div>
                </nav>
            </div>
        </div>
        
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-4 sm:mb-6 p-4 sm:p-6">
            <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-1">Discover Events</h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Find amazing events happening around you</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="flex items-center gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                            {{ $upcomingEvents }} upcoming
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                            {{ $todayEvents }} today
                        </span>
                    </div>
                    <a  href="{{ route('events.create') }}" class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1 sm:mr-2"></i>
                        Create Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-4 sm:mb-6 p-4 sm:p-6">
            <form method="GET" class="space-y-3 sm:space-y-4">
                <div class="flex flex-col gap-3 sm:gap-4">
                    <!-- Search -->
                    <div class="w-full">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search events, locations, or keywords..."
                                class="w-full pl-10 pr-4 py-2.5 sm:py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                            >
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Category Filter -->
                        <div class="flex-1">
                            <select 
                                name="category" 
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                onchange="this.form.submit()"
                            >
                                <option value="all">All Categories</option>
                                @foreach($categories as $key => $category)
                                <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- View Toggle -->
                        <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                            <button type="button" id="grid-view" class="flex items-center px-3 py-1.5 rounded-md text-sm font-medium transition-colors view-toggle active">
                                <i data-lucide="grid-3x3" class="w-4 h-4 mr-1.5"></i>
                                <span class="hidden sm:inline">Grid</span>
                            </button>
                            <button type="button" id="list-view" class="flex items-center px-3 py-1.5 rounded-md text-sm font-medium transition-colors view-toggle">
                                <i data-lucide="list" class="w-4 h-4 mr-1.5"></i>
                                <span class="hidden sm:inline">List</span>
                            </button>
                        </div>
                        
                        <!-- Search Button -->
                        <button type="submit" class="px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                            <span class="hidden sm:inline">Search</span>
                            <i data-lucide="search" class="w-4 h-4 sm:hidden"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Featured Events -->
        @if($featuredEvents->count() > 0)
        <div class="mb-6 sm:mb-8">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-3 sm:mb-4">Featured Events</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($featuredEvents as $event)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer" onclick="window.location.href='{{ route('events.show', $event->slug) }}'">
                    <div class="relative h-40 sm:h-48 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                        @if($event->image_url)
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="calendar" class="w-10 h-10 sm:w-12 sm:h-12 text-white/80"></i>
                        </div>
                        @endif
                        
                        <div class="absolute top-2 sm:top-3 left-2 sm:left-3">
                            <span class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-medium rounded-full">
                                {{ $categories[$event->category] ?? ucfirst($event->category) }}
                            </span>
                        </div>
                        
                        <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                            <span class="px-2 py-1 bg-{{ $event->is_free ? 'green' : 'blue' }}-500 text-white text-xs font-medium rounded-full">
                                {{ $event->is_free ? 'Free' : '$' . $event->price }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4 sm:p-6">
                        <h3 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        
                        <div class="space-y-1.5 sm:space-y-2 mb-3 sm:mb-4">
                            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="calendar" class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0"></i>
                                <span class="truncate">{{ $event->formatted_date_time }}</span>
                            </div>
                            
                            @if($event->location)
                            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="map-pin" class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0"></i>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <i data-lucide="user" class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0"></i>
                                <span class="truncate">by {{ $event->user->name }}</span>
                            </div>
                        </div>
                        
                        @if($event->description)
                        <p class="text-gray-600 dark:text-gray-300 text-xs sm:text-sm mb-3 sm:mb-4 line-clamp-2">
                            {{ $event->description }}
                        </p>
                        @endif
                        
                        @if($event->max_attendees)
                        <div class="flex items-center justify-between text-xs sm:text-sm mb-3 sm:mb-4">
                            <span class="text-gray-600 dark:text-gray-400">
                                {{ $event->current_attendees }}/{{ $event->max_attendees }} attending
                            </span>
                            <div class="w-16 sm:w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 sm:h-2">
                                <div class="bg-blue-600 h-1.5 sm:h-2 rounded-full" style="width: {{ $event->max_attendees > 0 ? ($event->current_attendees / $event->max_attendees) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endif
                        
                        <button class="w-full px-3 sm:px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            View Details
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- All Events -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">
                    @if(request('category') && request('category') !== 'all')
                        {{ $categories[request('category')] }} Events
                    @elseif(request('search'))
                        Search Results
                    @else
                        All Events
                    @endif
                </h2>
                <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    {{ $events->total() }} event{{ $events->total() !== 1 ? 's' : '' }} found
                </span>
            </div>

            @if($events->count() > 0)
            <!-- Grid View -->
            <div id="events-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-6">
                @foreach($events as $event)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer" onclick="window.location.href='{{ route('events.show', $event->slug) }}'">
                    <div class="relative h-40 sm:h-44 bg-gradient-to-br from-{{ ['blue', 'green', 'purple', 'pink', 'indigo', 'orange'][array_rand(['blue', 'green', 'purple', 'pink', 'indigo', 'orange'])] }}-500 to-{{ ['blue', 'green', 'purple', 'pink', 'indigo', 'orange'][array_rand(['blue', 'green', 'purple', 'pink', 'indigo', 'orange'])] }}-600 overflow-hidden">
                        @if($event->image_url)
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            @if($event->category === 'wedding')
                                <i data-lucide="heart" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @elseif($event->category === 'birthday')
                                <i data-lucide="cake" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @elseif($event->category === 'seminar' || $event->category === 'conference')
                                <i data-lucide="presentation" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @elseif($event->category === 'party')
                                <i data-lucide="music" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @elseif($event->category === 'sports')
                                <i data-lucide="trophy" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @else
                                <i data-lucide="calendar" class="w-8 h-8 sm:w-10 sm:h-10 text-white/80"></i>
                            @endif
                        </div>
                        @endif
                        
                        <div class="absolute top-3 left-3 right-3 flex justify-between items-start">
                            <div>
                               @php
                                $isToday = $event->start_datetime->isToday();
                                $isTomorrow = $event->start_datetime->isTomorrow();
                                @endphp
                                
                                @if($isToday)
                                <span class="px-2 py-1 bg-red-500 text-white text-xs font-medium rounded-full shadow-lg">
                                    Today
                                </span>
                                @elseif($isTomorrow)
                                <span class="px-2 py-1 bg-orange-500 text-white text-xs font-medium rounded-full shadow-lg">
                                    Tomorrow
                                </span>
                                @else
                                <span class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-medium rounded-full shadow-lg">
                                    {{ $event->formatted_date }}
                                </span>
                                @endif
                            </div>
                            
                            <span class="px-2 py-1 bg-{{ $event->is_free ? 'green' : 'blue' }}-500 text-white text-xs font-medium rounded-full shadow-lg">
                                {{ $event->is_free ? 'Free' : '$' . $event->price }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="mb-3">
                            <span class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full mb-2">
                                {{ $categories[$event->category] ?? ucfirst($event->category) }}
                            </span>
                            <h3 class="font-bold text-base text-gray-900 dark:text-white line-clamp-2 leading-tight">
                                {{ $event->title }}
                            </h3>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <i data-lucide="clock" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span class="truncate">{{ $event->formatted_time }}</span>
                            </div>
                            
                            @if($event->location)
                            <div class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5"></i>
                                <span class="line-clamp-2 leading-relaxed">{{ $event->location }}</span>
                            </div>
                            @endif
                            
                            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <i data-lucide="user" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span class="truncate">{{ $event->user->name }}</span>
                            </div>
                            
                            @if($event->max_attendees)
                            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <i data-lucide="users" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                <span>{{ $event->current_attendees }}/{{ $event->max_attendees }}</span>
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 ml-1">
                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all" style="width: {{ $event->max_attendees > 0 ? ($event->current_attendees / $event->max_attendees) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <button class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            View Details
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- List View -->
            <div id="events-list" class="space-y-4 mb-6 hidden">
                @foreach($events as $event)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('events.show', $event->slug) }}'">
                    <div class="flex flex-col sm:flex-row">
                        <div class="relative w-full sm:w-56 md:w-64 h-40 sm:h-auto bg-gradient-to-br from-{{ ['blue', 'green', 'purple', 'pink', 'indigo', 'orange'][array_rand(['blue', 'green', 'purple', 'pink', 'indigo', 'orange'])] }}-500 to-{{ ['blue', 'green', 'purple', 'pink', 'indigo', 'orange'][array_rand(['blue', 'green', 'purple', 'pink', 'indigo', 'orange'])] }}-600 overflow-hidden flex-shrink-0">
                            @if($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                @if($event->category === 'wedding')
                                    <i data-lucide="heart" class="w-12 h-12 text-white/80"></i>
                                @elseif($event->category === 'birthday')
                                    <i data-lucide="cake" class="w-12 h-12 text-white/80"></i>
                                @elseif($event->category === 'seminar' || $event->category === 'conference')
                                    <i data-lucide="presentation" class="w-12 h-12 text-white/80"></i>
                                @elseif($event->category === 'party')
                                    <i data-lucide="music" class="w-12 h-12 text-white/80"></i>
                                @elseif($event->category === 'sports')
                                    <i data-lucide="trophy" class="w-12 h-12 text-white/80"></i>
                                @else
                                    <i data-lucide="calendar" class="w-12 h-12 text-white/80"></i>
                                @endif
                            </div>
                            @endif
                            
                            <div class="absolute top-3 left-3 right-3 flex justify-between items-start">
                                <div class="flex flex-col gap-2">
                                    @php
                                    $isToday = $event->start_datetime->isToday();
                                    $isTomorrow = $event->start_datetime->isTomorrow();
                                    @endphp
                                    
                                    @if($isToday)
                                    <span class="px-2 py-1 bg-red-500 text-white text-xs font-medium rounded-full shadow-lg">Today</span>
                                    @elseif($isTomorrow)
                                    <span class="px-2 py-1 bg-orange-500 text-white text-xs font-medium rounded-full shadow-lg">Tomorrow</span>
                                    @endif
                                    
                                    <span class="px-2 py-1 bg-{{ $event->is_free ? 'green' : 'blue' }}-500 text-white text-xs font-medium rounded-full shadow-lg">
                                        {{ $event->is_free ? 'Free' : '$' . $event->price }}
                                    </span>
                                </div>
                                
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full shadow-lg">
                                    {{ $categories[$event->category] ?? ucfirst($event->category) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex-1 p-4 sm:p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg sm:text-xl text-gray-900 dark:text-white mb-3 line-clamp-2 leading-tight">
                                        {{ $event->title }}
                                    </h3>
                                    
                                    @if($event->description)
                                    <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base mb-4 line-clamp-3 leading-relaxed">
                                        {{ $event->description }}
                                    </p>
                                    @endif
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i data-lucide="calendar" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="truncate">{{ $event->formatted_date_time }}</span>
                                        </div>
                                        
                                        @if($event->location)
                                        <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                            <span class="line-clamp-2">{{ $event->location }}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i data-lucide="user" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="truncate">{{ $event->user->name }}</span>
                                        </div>
                                        
                                        @if($event->max_attendees)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i data-lucide="users" class="w-4 h-4 flex-shrink-0"></i>
                                            <span>{{ $event->current_attendees }}/{{ $event->max_attendees }} attending</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($event->max_attendees)
                                    <div class="mb-4">
                                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                            <span>Attendance</span>
                                            <span>{{ round(($event->current_attendees / $event->max_attendees) * 100) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $event->max_attendees > 0 ? ($event->current_attendees / $event->max_attendees) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="mt-auto">
                                    <button class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination Card -->
            @if($events->hasPages())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-left">
                        Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
                    </div>
                    
                    <div class="flex justify-center">
                        <nav class="flex items-center space-x-1">
      @if ($events->onFirstPage())
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </span>
                            @else
                                <a href="{{ $events->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                </a>
                            @endif

                            @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                @if ($page == $events->currentPage())
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-lg">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($events->hasMorePages())
                                <a href="{{ $events->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 dark:text-gray-600 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-not-allowed">
                                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
            @endif

            @else
            <div class="text-center py-12 sm:py-16">
                <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <i data-lucide="calendar-x" class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">No events found</h3>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-4 sm:mb-6 px-4">
                    @if(request('search') || request('category'))
                        Try adjusting your search criteria or browse all events.
                    @else
                        Be the first to create an amazing event for the community!
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center px-4">
                    @if(request('search') || request('category'))
                    <a href="{{ route('events.index') }}" class="px-4 sm:px-6 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors text-sm sm:text-base">
                        View All Events
                    </a>
                    @endif
                    <a href="{{ route('events.create') }}" class="px-4 sm:px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
                        Create Event
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>

<style>
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

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.event-card {
    animation: fadeInUp 0.6s ease-out;
}

.event-card:nth-child(even) {
    animation-delay: 0.1s;
}

.event-card:nth-child(3n) {
    animation-delay: 0.2s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

nav a, nav span {
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

nav a:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

button:focus, input:focus, select:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    .group:hover .group-hover\:scale-105 {
        transform: none;
    }
    
    .event-card {
        animation: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // View toggle functionality
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const eventsGrid = document.getElementById('events-grid');
    const eventsList = document.getElementById('events-list');

    if (gridView && listView && eventsGrid && eventsList) {
        gridView.addEventListener('click', function() {
            this.classList.add('active');
            listView.classList.remove('active');
            eventsGrid.classList.remove('hidden');
            eventsList.classList.add('hidden');
            localStorage.setItem('events-view', 'grid');
        });

        listView.addEventListener('click', function() {
            this.classList.add('active');
            gridView.classList.remove('active');
            eventsList.classList.remove('hidden');
            eventsGrid.classList.add('hidden');
            localStorage.setItem('events-view', 'list');
        });

        // Restore saved view preference
        const savedView = localStorage.getItem('events-view');
        if (savedView === 'list') {
            listView.click();
        }
    }

    // Search input enhancements
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        const clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors';
        clearBtn.innerHTML = '<i data-lucide="x" class="w-4 h-4"></i>';
        clearBtn.style.display = searchInput.value ? 'block' : 'none';
        
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            searchInput.focus();
        });
        
        searchInput.addEventListener('input', function() {
            clearBtn.style.display = this.value ? 'block' : 'none';
        });
        
        searchInput.parentElement.appendChild(clearBtn);
        lucide.createIcons();
    }

    // Event card click handlers
    document.querySelectorAll('[onclick*="window.location.href"]').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                e.stopPropagation();
                return;
            }
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.closest('[onclick*="window.location.href"]')) {
            e.target.closest('[onclick*="window.location.href"]').click();
        }
    });

    // Category select handler
    const categorySelect = document.querySelector('select[name="category"]');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            this.disabled = true;
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    }
});
</script>

@endsection