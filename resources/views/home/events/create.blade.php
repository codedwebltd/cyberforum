@extends('inc.home.app')
@section('title', 'Create Event - ' . config('app.name'))
@section('content')

<main class="p-3 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
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
                            <i data-lucide="message-square" class="w-4 h-4"></i>
                            <span class="font-medium">Create Events</span>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span>Event center</span>
                    </div>
                </nav>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="edit-3" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Event Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="What's your event about?" required>
                                @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Event Type</label>
                                    <select name="category" id="category" 
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select type</option>
                                        @foreach(App\Models\Events::getCategories() as $key => $category)
                                        <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Visibility -->
                                <div>
                                    <label for="visibility" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visibility</label>
                                    <select name="visibility" id="visibility" 
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        @foreach(App\Models\Events::getVisibilityOptions() as $key => $visibility)
                                        <option value="{{ $key }}" {{ old('visibility', 'public') == $key ? 'selected' : '' }}>{{ $visibility }}</option>
                                        @endforeach
                                    </select>
                                    @error('visibility')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Tags -->
                            <div>
                                <label for="tags-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tags <span class="text-xs text-gray-500">(Max 10 tags)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="tags-input" placeholder="Type tags and press Enter or comma..." 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    
                                    <!-- Suggestions dropdown -->
                                    <div id="tags-suggestions" class="absolute top-full left-0 right-0 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg mt-1 max-h-40 overflow-y-auto z-10 hidden">
                                        <!-- Dynamic suggestions will be inserted here -->
                                    </div>
                                </div>
                                
                                <!-- Selected tags container -->
                                <div id="selected-tags" class="flex flex-wrap gap-2 mt-3">
                                    <!-- Selected tags will appear here -->
                                </div>
                                
                                <!-- Hidden input to store tag IDs -->
                                <input type="hidden" name="tags" id="tags-hidden" value="">
                                
                                @error('tags')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Content</h2>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Event Content <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6" 
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="Describe your event in detail...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="calendar" class="w-4 h-4 text-green-600"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Date & Time</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="start_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date & Time</label>
                                <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('start_datetime')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date & Time</label>
                                <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('end_datetime')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timezone</label>
                            <select name="timezone" id="timezone" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @php $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL); @endphp
                                @foreach($timezones as $timezone)
                                <option value="{{ $timezone }}" {{ old('timezone', 'UTC') == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Location & Pricing -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                                <i data-lucide="map-pin" class="w-4 h-4 text-purple-600"></i>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Location & Details</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Event location">
                            </div>
                            
                            <div>
                                <label for="venue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Venue</label>
                                <input type="text" name="venue" id="venue" value="{{ old('venue') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Venue name">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-3">
                                    <input type="checkbox" name="is_free" id="is_free" value="1" {{ old('is_free', true) ? 'checked' : '' }} 
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="is_free" class="text-sm font-medium text-gray-700 dark:text-gray-300">Free Event</label>
                                </div>
                                <div id="price-container" class="{{ old('is_free', true) ? 'hidden' : '' }}">
                                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" 
                                               class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="max_attendees" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Attendees</label>
                                <input type="number" name="max_attendees" id="max_attendees" value="{{ old('max_attendees') }}" min="1" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Unlimited">
                            </div>
                            
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Image URL</label>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="https://...">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <a href="{{ route('events.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center">
                                Cancel
                            </a>
                            <button type="submit" name="status" value="draft" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                Save Draft
                            </button>
                            <button type="submit" name="status" value="published" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Publish Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                            <i data-lucide="lightbulb" class="w-4 h-4 text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tips for Great Events</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Write a clear, descriptive title</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Make it easy for people to understand what your event is about</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Include detailed description</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Explain what attendees can expect and why they should join</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Add an attractive image</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Events with images get 3x more engagement</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Set appropriate visibility</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Public events reach more people and get better attendance</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-300">Need Help?</span>
                        </div>
                        <p class="text-xs text-blue-800 dark:text-blue-400 mb-3">Check our event creation guide for best practices and tips.</p>
                        <button class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                            View Event Guide →
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
    
    // Tags functionality
    let selectedTags = [];
    const maxTags = 10;
    const tagsInput = document.getElementById('tags-input');
    const tagsSuggestions = document.getElementById('tags-suggestions');
    const selectedTagsContainer = document.getElementById('selected-tags');
    const tagsHidden = document.getElementById('tags-hidden');
    
    // Fetch suggestions from server
    async function fetchTagSuggestions(query) {
        if (query.length < 2) return [];
        
        try {
            const response = await fetch(`/events/tags/search?q=${encodeURIComponent(query)}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching tag suggestions:', error);
            return [];
        }
    }
    
    // Show suggestions
    function showSuggestions(suggestions, query) {
        if (suggestions.length === 0 && query.length >= 2) {
            tagsSuggestions.innerHTML = `
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600" data-action="create" data-name="${query}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4 text-green-600"></i>
                        <span class="text-sm">Create tag: <strong>"${query}"</strong></span>
                    </div>
                </div>
            `;
        } else {
            tagsSuggestions.innerHTML = suggestions.map(tag => `
                <div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer border-b border-gray-100 dark:border-gray-600 last:border-b-0" data-tag-id="${tag.id}" data-tag-name="${tag.name}">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">${tag.name}</span>
                        <span class="text-xs text-gray-500">${tag.usage_count || 0} uses</span>
                    </div>
                </div>
            `).join('');
        }
        
        tagsSuggestions.classList.remove('hidden');
        lucide.createIcons();
    }
    
    // Hide suggestions
    function hideSuggestions() {
        setTimeout(() => tagsSuggestions.classList.add('hidden'), 150);
    }
    
    // Add tag
    function addTag(tagData) {
        if (selectedTags.length >= maxTags) {
            alert(`Maximum ${maxTags} tags allowed`);
            return;
        }
        
        // Check if tag already selected
        if (selectedTags.find(tag => tag.name.toLowerCase() === tagData.name.toLowerCase())) {
            return;
        }
        
        selectedTags.push(tagData);
        renderSelectedTags();
        updateHiddenInput();
        tagsInput.value = '';
        hideSuggestions();
    }
    
    // Remove tag
    function removeTag(index) {
        selectedTags.splice(index, 1);
        renderSelectedTags();
        updateHiddenInput();
    }
    
    // Render selected tags
    function renderSelectedTags() {
        const placeholder = document.getElementById('tags-placeholder');
        
        if (selectedTags.length === 0) {
            selectedTagsContainer.innerHTML = `
                <div class="text-sm text-gray-500 dark:text-gray-400 italic" id="tags-placeholder">
                    Selected tags will appear here...
                </div>
            `;
        } else {
            selectedTagsContainer.innerHTML = selectedTags.map((tag, index) => `
                <div class="flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                    <span>${tag.name}</span>
                    <button type="button" onclick="removeTag(${index})" class="text-blue-600 hover:text-blue-800">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                </div>
            `).join('');
        }
        lucide.createIcons();
    }
    
    // Update hidden input
    function updateHiddenInput() {
        tagsHidden.value = JSON.stringify(selectedTags);
    }
    
    // Make removeTag global
    window.removeTag = removeTag;
    
    // Input event listener
    tagsInput.addEventListener('input', async function() {
        const query = this.value.trim();
        
        if (query.length < 1) {
            hideSuggestions();
            return;
        }
        
        const suggestions = await fetchTagSuggestions(query);
        showSuggestions(suggestions, query);
    });
    
    // Handle suggestion clicks
    tagsSuggestions.addEventListener('click', function(e) {
        const suggestionEl = e.target.closest('[data-tag-id], [data-action="create"]');
        if (!suggestionEl) return;
        
        if (suggestionEl.dataset.action === 'create') {
            // Create new tag
            addTag({
                id: null,
                name: suggestionEl.dataset.name,
                isNew: true
            });
        } else {
            // Select existing tag
            addTag({
                id: suggestionEl.dataset.tagId,
                name: suggestionEl.dataset.tagName,
                isNew: false
            });
        }
    });
    
    // Handle keyboard events
    tagsInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const query = this.value.trim();
            if (query) {
                addTag({
                    id: null,
                    name: query,
                    isNew: true
                });
            }
        }
    });
    
    // Hide suggestions when clicking outside
    tagsInput.addEventListener('blur', hideSuggestions);
    
    // Free event toggle
    const isFreeCheckbox = document.getElementById('is_free');
    const priceContainer = document.getElementById('price-container');
    
    isFreeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            priceContainer.classList.add('hidden');
        } else {
            priceContainer.classList.remove('hidden');
        }
    });
});
</script>

@endsection