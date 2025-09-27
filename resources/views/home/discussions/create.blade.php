@extends('inc.home.app')
@section('title', 'Create Discussion - ' . config('app.name'))
@section('content')
<!-- Add these to your blade template head -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<main class="p-2 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
        @include('session-message.session-message')
        
      <!-- Beautiful Breadcrumb Card -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
    <div class="p-4 sm:p-6">
        <nav class="flex items-center justify-between">
            <div class="flex items-center space-x-3 text-sm overflow-x-auto whitespace-nowrap">
                <a href="{{ route('discussion.index') }}" 
                   class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    Discussions
                </a>
                
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                
                <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span class="font-medium">Create Discussion</span>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <i data-lucide="clock" class="w-3 h-3"></i>
                <span>New Post</span>
            </div>
        </nav>
    </div>
</div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <form id="discussion-form" method="POST" action="{{ route('discussion.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 shadow-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <i data-lucide="edit-3" class="w-5 h-5 text-blue-600"></i>
                                Basic Information
                            </h2>

                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Discussion Title <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="title" name="title" required maxlength="255"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-medium"
                                        placeholder="What's your discussion about?">
                                    <div class="absolute right-3 top-3 text-xs text-gray-500">
                                        <span id="title-counter">0</span>/255
                                    </div>
                                </div>
                            </div>

                            <!-- Discussion Type & Tags Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Discussion Type -->
                                <div>
                                    <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Discussion Type
                                    </label>
                                    <select id="type" name="type" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="discussion">Discussion</option>
                                        <option value="question">Question</option>
                                        <option value="announcement">Announcement</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="mb-6">
                                <label for="tags" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Tags <span class="text-xs text-gray-500">(Max 10 tags)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="tags-input" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Type tags and press Enter or comma...">
                                    <input type="hidden" id="tags" name="tags">
                                </div>
                                <div id="tags-container" class="flex flex-wrap gap-2 mt-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 shadow-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <i data-lucide="type" class="w-5 h-5 text-blue-600"></i>
                                Content
                            </h2>

                            <!-- Main Content Editor -->
                            <div class="mb-6">
                                <label for="content" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Discussion Content <span class="text-red-500">*</span>
                                </label>
                                <!-- remove s from the textarea id to restore back summernote editor -->
                                <textarea id="contents" name="content" rows="10" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Share your thoughts, ask questions, or start a meaningful discussion..."></textarea>
                            </div>

                            <!-- Excerpt -->
                            <div class="mb-6">
                                <label for="excerpt" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Excerpt <span class="text-xs text-gray-500">(Optional - auto-generated if empty)</span>
                                </label>
                                <div class="relative">
                                    <textarea id="excerpt" name="excerpt" rows="3" maxlength="300"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Brief summary of your discussion (optional)"></textarea>
                                    <div class="absolute right-3 bottom-3 text-xs text-gray-500">
                                        <span id="excerpt-counter">0</span>/300
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media & Attachments Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 shadow-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <i data-lucide="image" class="w-5 h-5 text-blue-600"></i>
                                Media & Attachments
                            </h2>

                            <!-- Featured Image -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Featured Image <span class="text-xs text-gray-500">(Optional)</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                                    <div id="featured-image-upload" class="cursor-pointer">
                                        <i data-lucide="upload" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <span class="font-semibold text-blue-600 hover:text-blue-500">Click to upload</span> 
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 10MB</p>
                                        <input type="file" id="featured-image-input" name="featured_image" accept="image/*" class="hidden">
                                    </div>
                                    <div id="featured-image-preview" class="hidden">
                                        <div class="relative">
                                            <img id="featured-preview-img" src="" alt="Featured image preview" class="max-h-64 mx-auto rounded-lg">
                                            <button type="button" id="remove-featured-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Attachments -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    File Attachments <span class="text-xs text-gray-500">(Optional - PDF, DOC, ZIP up to 50MB each)</span>
                                </label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                                    <div id="attachments-upload" class="cursor-pointer">
                                        <i data-lucide="paperclip" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <span class="font-semibold text-blue-600 hover:text-blue-500">Click to add files</span> 
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-2">Multiple files supported</p>
                                        <input type="file" id="attachments-input" name="attachments[]" multiple accept=".pdf,.doc,.docx,.zip,.rar" class="hidden">
                                    </div>
                                </div>
                                <div id="attachments-list" class="mt-4 space-y-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 shadow-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <i data-lucide="settings" class="w-5 h-5 text-blue-600"></i>
                                Discussion Settings
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Status
                                    </label>
                                    <select id="status" name="status" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="draft">Draft</option>
                                        <option value="published" selected>Published</option>
                                    </select>
                                </div>

                                <!-- Allow Comments Toggle -->
                          <div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        Allow Comments
    </label>
    <div class="flex items-center mt-3">
        <label class="relative inline-flex items-center cursor-pointer">
            <!-- 👇 Add 'peer' here -->
            <input type="checkbox" id="allow_comments" name="allow_comments" value="1" checked class="sr-only peer">
            
            <!-- Toggle visual -->
            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 
                        peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 
                        rounded-full 
                        peer-checked:bg-blue-600 
                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                        after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 
                        after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
            </div>

            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enable comments</span>
        </label>
    </div>
</div>

                            </div>

                            <!-- Admin/Moderator Options -->
                            @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator')))
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Moderator Options</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Pin Discussion -->
                                    <div>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" id="is_pinned" name="is_pinned" value="1" class="sr-only">
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 relative"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pin Discussion</span>
                                        </label>
                                    </div>

                                    <!-- Feature Discussion -->
                                    <div>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="sr-only">
                                            <div class="w-11 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-600 relative"></div>
                                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Feature Discussion</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                                <button type="button" onclick="window.history.back()" 
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                                    Cancel
                                </button>
                                <button type="submit" id="save-draft-btn" 
                                    class="px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors font-medium">
                                    Save Draft
                                </button>
                                <button type="submit" id="publish-btn" 
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-medium shadow-lg">
                                    Publish Discussion
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <!-- Media Library -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="folder" class="w-5 h-5 text-blue-600"></i>
                        Select Media
                    </h3>
                    
                    <!-- Media Tabs -->
                    <div class="flex gap-2 mb-4">
                        <button type="button" class="media-tab active px-3 py-2 text-sm bg-blue-600 text-white rounded-lg" data-tab="images">
                            Images
                        </button>
                        <button type="button" class="media-tab px-3 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg" data-tab="videos">
                            Videos
                        </button>
                        <button type="button" class="media-tab px-3 py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg" data-tab="docs">
                            Docs
                        </button>
                    </div>

                    <!-- Media Grid -->
                    <div id="media-grid" class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto">
                        <!-- Media items will be loaded here -->
                        <div class="text-center py-8 col-span-2">
                            <i data-lucide="image" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-500">No media files found</p>
                            <p class="text-xs text-gray-400 mt-1">Upload some files to see them here</p>
                        </div>
                    </div>
                </div>

                <!-- Discussion Tips -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600"></i>
                        Tips for Great Discussions
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Write a clear, descriptive title
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Add relevant tags to help others find your discussion
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Include images or videos when helpful
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0"></i>
                            Be respectful and constructive
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

    let mediaPage = 1;
    let hasMoreMedia = false;

    // Character counters
    const titleInput = document.getElementById('title');
    const titleCounter = document.getElementById('title-counter');
    const excerptInput = document.getElementById('excerpt');
    const excerptCounter = document.getElementById('excerpt-counter');

    titleInput.addEventListener('input', function() {
        titleCounter.textContent = this.value.length;
    });

    excerptInput.addEventListener('input', function() {
        excerptCounter.textContent = this.value.length;
    });

    // Tags management
    const tagsInput = document.getElementById('tags-input');
    const tagsContainer = document.getElementById('tags-container');
    const hiddenTagsInput = document.getElementById('tags');
    let tags = [];

    function addTag(tagText) {
        const trimmedTag = tagText.trim();
        if (trimmedTag && !tags.includes(trimmedTag) && tags.length < 10) {
            tags.push(trimmedTag);
            updateTagsDisplay();
            updateHiddenInput();
        }
    }

    function removeTag(tagText) {
        tags = tags.filter(tag => tag !== tagText);
        updateTagsDisplay();
        updateHiddenInput();
    }

    function updateTagsDisplay() {
        tagsContainer.innerHTML = '';
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'inline-flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm rounded-full';
            tagElement.innerHTML = `
                ${tag}
                <button type="button" class="hover:text-red-600" onclick="removeTagHandler('${tag}')">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            `;
            tagsContainer.appendChild(tagElement);
        });
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function updateHiddenInput() {
        hiddenTagsInput.value = JSON.stringify(tags);
    }

    // Global function for removing tags
    window.removeTagHandler = function(tag) {
        removeTag(tag);
    };

    tagsInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag(this.value);
            this.value = '';
        }
    });

    tagsInput.addEventListener('blur', function() {
        if (this.value.trim()) {
            addTag(this.value);
            this.value = '';
        }
    });

    // Featured image upload
    const featuredImageUpload = document.getElementById('featured-image-upload');
    const featuredImageInput = document.getElementById('featured-image-input');
    const featuredImagePreview = document.getElementById('featured-image-preview');
    const featuredPreviewImg = document.getElementById('featured-preview-img');
    const removeFeaturedImage = document.getElementById('remove-featured-image');

    featuredImageUpload.addEventListener('click', () => featuredImageInput.click());

    featuredImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                featuredPreviewImg.src = e.target.result;
                featuredImageUpload.classList.add('hidden');
                featuredImagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeFeaturedImage.addEventListener('click', function() {
        featuredImageInput.value = '';
        featuredImageUpload.classList.remove('hidden');
        featuredImagePreview.classList.add('hidden');
    });

    // File attachments
    const attachmentsUpload = document.getElementById('attachments-upload');
    const attachmentsInput = document.getElementById('attachments-input');
    const attachmentsList = document.getElementById('attachments-list');

    attachmentsUpload.addEventListener('click', () => attachmentsInput.click());

    attachmentsInput.addEventListener('change', function(e) {
        Array.from(e.target.files).forEach(file => {
            addAttachment(file);
        });
    });

    function addAttachment(file) {
        const attachmentElement = document.createElement('div');
        attachmentElement.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
        
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        
        attachmentElement.innerHTML = `
            <div class="flex items-center gap-3">
                <i data-lucide="file" class="w-5 h-5 text-blue-600"></i>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
            </div>
            <button type="button" class="text-red-600 hover:text-red-700" onclick="this.parentElement.remove()">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        
        attachmentsList.appendChild(attachmentElement);
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    // Media Library Functionality
    const mediaTabs = document.querySelectorAll('.media-tab');
    const mediaGrid = document.getElementById('media-grid');
    let currentMediaType = 'images';
    let mediaCache = {};

    // Initialize media library
    loadMedia('images');

    mediaTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            mediaTabs.forEach(t => {
                t.classList.remove('active', 'bg-blue-600', 'text-white');
                t.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            });
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            
            currentMediaType = this.dataset.tab;
            loadMedia(currentMediaType);
        });
    });

async function loadMedia(type) {
    mediaPage = 1;
    // Remove any existing load more button
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (loadMoreBtn) loadMoreBtn.remove();
    
    // Show loading state
    mediaGrid.innerHTML = `
        <div class="col-span-2 flex flex-col items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
            <p class="text-sm text-gray-500">Loading ${type}...</p>
        </div>
    `;

    try {
        const response = await fetch(`/discussion/new/media?type=${type}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load media');
        }

        const data = await response.json();
        
        // Cache the results
        const cacheKey = `media_${type}`;
        mediaCache[cacheKey] = {
            data: data.media,
            timestamp: Date.now()
        };

        renderMedia(data.media, type);
        
        // Check if we should show load more button
        hasMoreMedia = data.media.length === 1;
        if (hasMoreMedia) {
            addLoadMoreButton();
        }

    } catch (error) {
        console.error('Error loading media:', error);
        mediaGrid.innerHTML = `
            <div class="col-span-2 text-center py-8">
                <i data-lucide="alert-circle" class="w-12 h-12 text-red-400 mx-auto mb-2"></i>
                <p class="text-sm text-red-500">Error loading ${type}</p>
                <button onclick="loadMedia('${type}')" class="text-xs text-blue-600 hover:text-blue-500 mt-1">Try again</button>
            </div>
        `;
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }
}

function addLoadMoreButton() {
    if (!document.getElementById('load-more-btn')) {
        mediaGrid.insertAdjacentHTML('afterend', `
            <button id="load-more-btn" onclick="loadMoreMedia()" 
                    class="w-full mt-4 p-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">
                Load More Media
            </button>
        `);
    }
}

window.loadMoreMedia = async function() {
    const btn = document.getElementById('load-more-btn');
    btn.textContent = 'Loading...';
    btn.disabled = true;
    
    try {
        mediaPage++;
        const response = await fetch(`/discussion/new/media?type=${currentMediaType}&page=${mediaPage}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.media.length > 0) {
            // Append new media to existing
            data.media.forEach(file => {
                mediaGrid.insertAdjacentHTML('beforeend', createMediaItem(file));
            });
            
            // Check if more pages exist
            hasMoreMedia = data.media.length === 1; // Based on your test settings
            
            if (!hasMoreMedia) {
                btn.remove();
            } else {
                btn.textContent = 'Load More Media';
                btn.disabled = false;
            }
        } else {
            btn.remove();
        }
        
        if (window.lucide) {
            lucide.createIcons();
        }
        
    } catch (error) {
        btn.textContent = 'Load More Media';
        btn.disabled = false;
    }
};

    function renderMedia(mediaFiles, type) {
        if (!mediaFiles || mediaFiles.length === 0) {
            const typeLabels = {
                'images': 'images',
                'videos': 'videos',
                'docs': 'documents'
            };

            mediaGrid.innerHTML = `
                <div class="text-center py-8 col-span-2">
                    <i data-lucide="${getIconForType(type)}" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-500">No ${typeLabels[type]} found</p>
                    <p class="text-xs text-gray-400 mt-1">Upload some files to see them here</p>
                </div>
            `;
        } else {
            mediaGrid.innerHTML = mediaFiles.map(file => createMediaItem(file)).join('');
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function createMediaItem(file) {
        const isImage = file.category === 'images';
        const isVideo = file.category === 'videos';

        return `
            <div class="media-item relative group cursor-pointer bg-white dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 transition-colors"
                 data-url="${file.url}" data-name="${file.name}" onclick="selectMedia('${file.url}', '${file.name}', '${file.category}')">
                
                ${isImage ? `
                    <div class="aspect-square bg-gray-100 dark:bg-gray-600">
                        <img src="${file.url}" alt="${file.name}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                ` : isVideo ? `
                    <div class="aspect-square bg-gray-100 dark:bg-gray-600 relative flex items-center justify-center">
                        <i data-lucide="play-circle" class="w-8 h-8 text-gray-600 dark:text-gray-400"></i>
                        <div class="absolute bottom-1 right-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded">
                            VIDEO
                        </div>
                    </div>
                ` : `
                    <div class="aspect-square bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                        <i data-lucide="${getIconForType('docs')}" class="w-8 h-8 text-gray-600 dark:text-gray-400"></i>
                    </div>
                `}
                
                <div class="p-2">
                    <p class="text-xs text-gray-700 dark:text-gray-300 truncate" title="${file.name}">${file.name}</p>
                    <p class="text-xs text-gray-500">${file.formattedSize}</p>
                </div>
                
                <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </div>
                </div>
            </div>
        `;
    }

    function getIconForType(type) {
        const icons = {
            'images': 'image',
            'videos': 'video',
            'docs': 'file-text'
        };
        return icons[type] || 'file';
    }

    // Global function for selecting media
window.selectMedia = function(url, name, category) {
    showMediaOptionsModal(url, name, category);
};

function showMediaOptionsModal(url, name, category) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 break-words">Insert <span class="text-sm opacity-75">${name}</span></h3>
            <div class="space-y-3">
                <button onclick="insertToContent('${url}', '${category}', '${name}'); closeModal(this)" 
                        class="w-full p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Insert to Content
                </button>
                ${category === 'images' ? `
                <button onclick="setAsFeatured('${url}', '${name}'); closeModal(this)" 
                        class="w-full p-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Set as Featured Image
                </button>` : ''}
                <button onclick="addToAttachments('${url}', '${name}'); closeModal(this)" 
                        class="w-full p-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Add as Attachment
                </button>
                <button onclick="closeModal(this)" 
                        class="w-full p-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

window.closeModal = function(btn) {
    btn.closest('.fixed').remove();
};

window.insertToContent = function(url, category, name) {
    if (category === 'images') {
        $('#content').summernote('insertImage', url);
    } else {
        insertIntoEditor(url, category);
    }
    showMediaFeedback(`Added "${name}" to content`);
};

window.setAsFeatured = function(url, name) {
    const featuredPreviewImg = document.getElementById('featured-preview-img');
    const featuredImageUpload = document.getElementById('featured-image-upload');
    const featuredImagePreview = document.getElementById('featured-image-preview');
    
    featuredPreviewImg.src = url;
    featuredImageUpload.classList.add('hidden');
    featuredImagePreview.classList.remove('hidden');
    showMediaFeedback(`Set "${name}" as featured image`);
};

window.addToAttachments = function(url, name) {
    const fakeFile = { name: name, size: 0 };
    addAttachment(fakeFile);
    showMediaFeedback(`Added "${name}" as attachment`);
};

    function insertIntoEditor(url, category) {
        // If using Summernote (you mentioned WYSIWYG)
        if (typeof $('#content').summernote === 'function') {
            if (category === 'images') {
                $('#content').summernote('insertImage', url);
            } else if (category === 'videos') {
                $('#content').summernote('insertVideo', url);
            } else {
                // For documents, insert as a link
                const linkHtml = `<a href="${url}" target="_blank" rel="noopener">${url.split('/').pop()}</a>`;
                $('#content').summernote('pasteHTML', linkHtml);
            }
        } else {
            // Fallback for regular textarea
            const textarea = document.getElementById('content');
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);
            
            let insertText = '';
            if (category === 'images') {
                insertText = `![Image](${url})`;
            } else if (category === 'videos') {
                insertText = `[Video](${url})`;
            } else {
                insertText = `[Document](${url})`;
            }
            
            textarea.value = textBefore + insertText + textAfter;
            textarea.setSelectionRange(cursorPos + insertText.length, cursorPos + insertText.length);
            textarea.focus();
        }
    }

    function showMediaFeedback(message) {
        // Create and show a temporary success message
        const feedback = document.createElement('div');
        feedback.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity';
        feedback.textContent = message;
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.style.opacity = '0';
            setTimeout(() => feedback.remove(), 300);
        }, 2000);
    }

    // Summernote initialization and image upload
    if (typeof $ !== 'undefined' && $.fn.summernote) {
        $('#content').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    uploadImageToServer(files[0]);
                }
            }
        });
    }

    function uploadImageToServer(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch('/discussion/new/upload-image', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                $('#content').summernote('insertImage', data.url);
            }
        });
    }

    // Form submission
    const form = document.getElementById('discussion-form');
    const saveDraftBtn = document.getElementById('save-draft-btn');
    const publishBtn = document.getElementById('publish-btn');

    saveDraftBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('status').value = 'draft';
        form.submit();
    });

    publishBtn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('status').value = 'published';
        form.submit();
    });
});
</script>

<style>
:root[data-theme="dark"] .note-editor.note-frame { background: #374151 !important; border: 1px solid #4B5563 !important; }
:root[data-theme="dark"] .note-toolbar { background: #374151 !important; border-bottom: 1px solid #4B5563 !important; }
:root[data-theme="dark"] .note-editable { background: #1F2937 !important; color: #F9FAFB !important; }
:root[data-theme="dark"] .note-btn { color: #D1D5DB !important; }
:root[data-theme="dark"] .note-btn:hover { background: #4B5563 !important; }

/* Better tab visibility */
.media-tab.active { background: #3B82F6 !important; color: white !important; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3) !important; }
.media-tab:not(.active) { border: 1px solid #D1D5DB; }
</style>
@endsection