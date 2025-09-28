@extends('inc.home.app')
@section('title', $event->title . ' - ' . config('app.name'))
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
                        
                        <a href="{{ route('events.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            Events
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            <span class="font-medium truncate max-w-[200px]">{{ $event->title }}</span>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span>Event center</span>
                    </div>
                </nav>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Header -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Event Image -->
                    <div class="relative h-64 sm:h-80 bg-gradient-to-br from-blue-500 to-purple-600">
                        @if($event->image_url)
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            @if($event->category === 'wedding')
                                <i data-lucide="heart" class="w-16 h-16 text-white/80"></i>
                            @elseif($event->category === 'birthday')
                                <i data-lucide="cake" class="w-16 h-16 text-white/80"></i>
                            @elseif($event->category === 'seminar' || $event->category === 'conference')
                                <i data-lucide="presentation" class="w-16 h-16 text-white/80"></i>
                            @elseif($event->category === 'party')
                                <i data-lucide="music" class="w-16 h-16 text-white/80"></i>
                            @elseif($event->category === 'sports')
                                <i data-lucide="trophy" class="w-16 h-16 text-white/80"></i>
                            @else
                                <i data-lucide="calendar" class="w-16 h-16 text-white/80"></i>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-800 text-sm font-medium rounded-full">
                                {{ App\Models\Events::getCategories()[$event->category] ?? ucfirst($event->category) }}
                            </span>
                            
                            @if($event->isToday())
                            <span class="px-3 py-1 bg-red-500 text-white text-sm font-medium rounded-full">Today</span>
                            @elseif($event->isTomorrow())
                            <span class="px-3 py-1 bg-orange-500 text-white text-sm font-medium rounded-full">Tomorrow</span>
                            @endif
                        </div>
                        
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-{{ $event->is_free ? 'green' : 'blue' }}-500 text-white text-sm font-medium rounded-full">
                                {{ $event->is_free ? 'Free' : '$' . $event->price }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Event Details -->
                    <div class="p-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $event->title }}
                        </h1>
                        
                        @if($event->description)
                        <p class="text-gray-600 dark:text-gray-300 mb-4 leading-relaxed">
                            {{ $event->description }}
                        </p>
                        @endif
                        
                        <!-- Event Tags -->
                        @if($event->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($event->tags as $tag)
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 text-sm rounded-full">
                                #{{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Event Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <!-- Date & Time -->
                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <i data-lucide="calendar" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Date & Time</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $event->formatted_date_time }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Duration: {{ $event->duration }}</div>
                                </div>
                            </div>
                            
                            <!-- Location -->
                            @if($event->location)
                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <i data-lucide="map-pin" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Location</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $event->location }}</div>
                                    @if($event->venue)
                                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $event->venue }}</div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <!-- Organizer -->
                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <i data-lucide="user" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Organizer</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $event->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Event Creator</div>
                                </div>
                            </div>
                            
                            <!-- Visibility -->
                            <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <i data-lucide="eye" class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">Visibility</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ App\Models\Events::getVisibilityOptions()[$event->visibility] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">Who can see this event</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="calendar-plus" class="w-5 h-5 inline mr-2"></i>
                                {{ $event->is_free ? 'Join Event' : 'Buy Ticket' }}
                            </button>
                            <button class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i data-lucide="share-2" class="w-5 h-5 inline mr-2"></i>
                                Share
                            </button>
                            <button class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i data-lucide="bookmark" class="w-5 h-5 inline"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery (if exists) -->
                @if($event->gallery && count($event->gallery) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Event Gallery</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($event->gallery as $image)
                        <div class="aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <img src="{{ $image }}" alt="Event image" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Attendance Info -->
                @if($event->max_attendees)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attendance</h3>
                    
                    <div class="text-center mb-4">
                        <div class="text-3xl font-bold text-blue-600">{{ $event->current_attendees }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">of {{ $event->max_attendees }} attendees</div>
                    </div>
                    
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4">
                        <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ $event->max_attendees > 0 ? ($event->current_attendees / $event->max_attendees) * 100 : 0 }}%"></div>
                    </div>
                    
                    @if($event->available_spots)
                    <div class="text-center">
                        <span class="text-sm text-green-600 dark:text-green-400">{{ $event->available_spots }} spots left</span>
                    </div>
                    @else
                    <div class="text-center">
                        <span class="text-sm text-red-600 dark:text-red-400">Event is full</span>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Event Status -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Event Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                            <span class="px-2 py-1 text-xs font-medium {{ $event->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} rounded-full">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Timezone</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $event->timezone }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $event->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i data-lucide="calendar-plus" class="w-4 h-4 inline mr-2"></i>
                            Add to Calendar
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <i data-lucide="map-pin" class="w-4 h-4 inline mr-2"></i>
                            Get Directions
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <i data-lucide="flag" class="w-4 h-4 inline mr-2"></i>
                            Report Event
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Gallery image modal functionality
    document.querySelectorAll('[src*="gallery"] img').forEach(img => {
        img.addEventListener('click', function() {
            // Could implement modal/lightbox here
            console.log('Gallery image clicked');
        });
    });
});
</script>

@endsection