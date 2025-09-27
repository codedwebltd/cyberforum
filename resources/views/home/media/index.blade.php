@extends('inc.home.app')
@section('title', 'Media Library - ' . config('app.name'))
@section('content')

<main class="p-2 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-7xl">
        @include('session-message.session-message')
        
        <!-- Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 dark:from-white dark:via-blue-200 dark:to-purple-200 bg-clip-text text-transparent">
                            Media Library
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Manage your images, videos, and documents
                        </p>
                    </div>
                    
                    <!-- Upload Button -->
                    <button id="upload-media-btn" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-medium shadow-lg flex items-center gap-2">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        Upload Media
                    </button>
                </div>
                
                <!-- Stats Bar -->
                <div class="flex flex-wrap gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        <span class="text-sm font-medium" id="images-count">0 Images</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 rounded-lg">
                        <i data-lucide="video" class="w-4 h-4"></i>
                        <span class="text-sm font-medium" id="videos-count">0 Videos</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-lg">
                        <i data-lucide="file" class="w-4 h-4"></i>
                        <span class="text-sm font-medium" id="docs-count">0 Documents</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Usage Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="hard-drive" class="w-5 h-5 text-blue-600"></i>
                        Storage Usage
                    </h2>
                    <div id="storage-status" class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="storage-used">0 MB</span> of <span id="storage-total">1 GB</span> used
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-3">
                    <div id="storage-progress" class="bg-gradient-to-r from-blue-600 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span id="storage-percentage" class="text-gray-600 dark:text-gray-400">0% used</span>
                    <span id="storage-remaining" class="text-green-600 dark:text-green-400">1 GB remaining</span>
                </div>
                
                <!-- Storage Warning/Upgrade -->
                <div id="storage-warning" class="hidden mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <div class="flex items-center gap-2 text-yellow-800 dark:text-yellow-200">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Storage almost full</span>
                    </div>
                </div>
                
                <div id="storage-limit" class="hidden mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-red-800 dark:text-red-200">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            <span class="text-sm font-medium">Storage limit reached</span>
                        </div>
                        <a href="{{ '#' }}" class="text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition-colors">
                            Upgrade Storage
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" id="media-search" placeholder="Search your media..." 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <!-- Filter Tabs -->
                    <div class="flex gap-2">
                        <button class="media-filter active px-4 py-3 text-sm bg-blue-600 text-white rounded-xl font-medium" data-filter="all">
                            All
                        </button>
                        <button class="media-filter px-4 py-3 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-filter="images">
                            Images
                        </button>
                        <button class="media-filter px-4 py-3 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-filter="videos">
                            Videos
                        </button>
                        <button class="media-filter px-4 py-3 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-filter="docs">
                            Docs
                        </button>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex gap-2">
                        <button id="grid-view" class="view-toggle active p-3 bg-blue-600 text-white rounded-xl">
                            <i data-lucide="grid-3x3" class="w-4 h-4"></i>
                        </button>
                        <button id="list-view" class="view-toggle p-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            <i data-lucide="list" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="p-4 sm:p-6">
                <!-- Loading State -->
                <div id="media-loading" class="hidden grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl animate-pulse"></div>
                    <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl animate-pulse"></div>
                    <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl animate-pulse"></div>
                    <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl animate-pulse"></div>
                </div>

                <!-- Grid View -->
                <div id="media-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <!-- List View -->
                <div id="media-list" class="hidden space-y-4">
                    <!-- Dynamic list content will be loaded here -->
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="hidden text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-3xl flex items-center justify-center shadow-xl">
                        <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No media files yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-6">Upload your first image, video, or document to get started</p>
                    <button id="empty-upload-btn" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 font-medium shadow-lg">
                        Upload Media
                    </button>
                </div>

                <!-- Load More Button -->
                <div id="load-more-container" class="hidden text-center mt-8">
                    <button id="load-more-media" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                        Load More Media
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Upload Modal -->
<div id="upload-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black/50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-2xl max-h-[85vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload Media</h3>
            <button id="close-upload-modal" class="p-2 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Upload Area -->
         <div class="flex-1 p-6 overflow-y-auto">
            <div id="upload-dropzone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 transition-colors cursor-pointer">
                <i data-lucide="upload-cloud" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Drop files here or click to browse</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    Supports images (JPG, PNG, GIF), videos (MP4, MOV), and documents (PDF, DOC, ZIP)
                </p>
                <p class="text-sm text-gray-500">Maximum file size: 100MB per file</p>
                <input type="file" id="file-input" multiple accept="image/*,video/*,.pdf,.doc,.docx,.zip,.rar" class="hidden">
            </div>

            <!-- Upload Progress -->
            <div id="upload-progress" class="hidden mt-6 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-700 dark:text-gray-300">Uploading files please wait...</span>
                    <span id="upload-percentage" class="text-blue-600 font-medium">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div id="upload-progress-bar" class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Upload Queue -->
            <div id="upload-queue" class="mt-6 space-y-2 max-h-64 overflow-y-auto"></div>
        </div>

        <!-- Modal Footer -->
         <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
            <div class="flex gap-3 justify-end">
                <button id="cancel-upload" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button id="start-upload" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all font-medium">
                    Start Upload
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Media Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden p-4 bg-black/90">
    <div class="relative max-w-4xl max-h-full">
        <button id="close-preview" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <i data-lucide="x" class="w-8 h-8"></i>
        </button>
        <div id="preview-content" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-2xl">
            <!-- Preview content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (window.lucide) {
        lucide.createIcons();
    }

    let currentFilter = 'all';
    let searchTimeout;
    let storageInfo = null;

    // Initialize page
    loadStorageInfo();
    loadMedia();

    // Storage info loading
    async function loadStorageInfo() {
        try {
            const response = await fetch('/media/storage-info', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    storageInfo = data.storage;
                    updateStorageDisplay(data.storage);
                }
            }
        } catch (error) {
            console.error('Error loading storage info:', error);
        }
    }
function updateStorageDisplay(storage) {
    document.getElementById('storage-used').textContent = storage.used_formatted;
    document.getElementById('storage-total').textContent = storage.total_formatted;
    document.getElementById('storage-percentage').textContent = storage.usage_percentage + '% used';
    document.getElementById('storage-remaining').textContent = storage.remaining_formatted + ' remaining';
    
    // Update progress bar
    const progressBar = document.getElementById('storage-progress');
    progressBar.style.width = storage.usage_percentage + '%';
    
    // Update progress bar color based on usage
    if (storage.usage_percentage >= 90) {
        progressBar.className = 'bg-red-500 h-3 rounded-full transition-all duration-500';
    } else if (storage.usage_percentage >= 75) {
        progressBar.className = 'bg-yellow-500 h-3 rounded-full transition-all duration-500';
    } else {
        progressBar.className = 'bg-gradient-to-r from-blue-600 to-purple-600 h-3 rounded-full transition-all duration-500';
    }
    
    // Show/hide warnings
    const warningEl = document.getElementById('storage-warning');
    const limitEl = document.getElementById('storage-limit');
    
    // Check if remaining storage is 10MB or less
    const remainingBytes = storage.remaining_bytes;
    const tenMB = 20 * 1024 * 1024; // 20MB in bytes
    
    if (storage.is_over_limit) {
        warningEl.classList.add('hidden');
        limitEl.classList.remove('hidden');
    } else if (remainingBytes <= tenMB) {
        // Show upgrade warning when 10MB or less remaining
        warningEl.classList.add('hidden');
        limitEl.classList.remove('hidden');
    } else if (storage.usage_percentage >= 85) {
        warningEl.classList.remove('hidden');
        limitEl.classList.add('hidden');
    } else {
        warningEl.classList.add('hidden');
        limitEl.classList.add('hidden');
    }
}

    // Media loading
    async function loadMedia(type = 'all') {
        currentFilter = type;
        showLoading(true);

        try {
            const queryType = type === 'all' ? '' : type;
            const response = await fetch(`/media/user-media?type=${queryType}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to load media');

            const data = await response.json();
            
            if (data.success) {
                renderMedia(data.media);
                updateStats(data.stats);
            } else {
                showError('Failed to load media files');
            }

        } catch (error) {
            console.error('Error loading media:', error);
            showError('Failed to load media files');
        } finally {
            showLoading(false);
        }
    }

    function renderMedia(media) {
        const mediaGrid = document.getElementById('media-grid');
        const mediaList = document.getElementById('media-list');
        const emptyState = document.getElementById('empty-state');
        
        if (!media || media.length === 0) {
            mediaGrid.innerHTML = '';
            mediaList.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        // Render grid view
        mediaGrid.innerHTML = media.map(file => createGridItem(file)).join('');
        
        // Render list view
        mediaList.innerHTML = media.map(file => createListItem(file)).join('');
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function createGridItem(file) {
        const isImage = file.category === 'images';
        const isVideo = file.category === 'videos';
        
        return `
            <div class="group relative aspect-square bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer">
                ${isImage ? `
                    <img src="${file.url}" alt="${file.name}" class="w-full h-full object-cover" loading="lazy">
                ` : isVideo ? `
                    <div class="w-full h-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                        <i data-lucide="video" class="w-12 h-12 text-white"></i>
                    </div>
                ` : `
                    <div class="w-full h-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-12 h-12 text-white"></i>
                    </div>
                `}
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <div class="flex gap-2">
                        <button onclick="previewFile('${file.url}', '${file.name}', '${file.category}')" class="p-2 bg-white/20 backdrop-blur-sm rounded-lg text-white hover:bg-white/30 transition-colors" title="View">
                            <i data-lucide="${isImage ? 'eye' : isVideo ? 'play' : 'download'}" class="w-4 h-4"></i>
                        </button>
                        <button onclick="copyFileUrl('${file.fileName}')" class="p-2 bg-white/20 backdrop-blur-sm rounded-lg text-white hover:bg-white/30 transition-colors" title="Copy URL">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                        <button onclick="deleteFile('${file.fileName}', '${file.name}')" class="p-2 bg-red-500/70 backdrop-blur-sm rounded-lg text-white hover:bg-red-600/70 transition-colors" title="Delete">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <!-- File Info -->
                <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/80 to-transparent text-white">
                    <p class="text-xs font-medium truncate">${file.name}</p>
                    <p class="text-xs text-gray-300">${file.formattedSize}</p>
                </div>
            </div>
        `;
    }

    function createListItem(file) {
        const iconName = file.category === 'images' ? 'image' : file.category === 'videos' ? 'video' : 'file-text';
        const colorClass = file.category === 'images' ? 'bg-blue-500' : file.category === 'videos' ? 'bg-purple-500' : 'bg-green-500';
        
        return `
            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <div class="w-16 h-16 ${colorClass} rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="${iconName}" class="w-8 h-8 text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">${file.name}</h3>
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span>${file.category}</span>
                        <span>${file.formattedSize}</span>
                        <span>${formatUploadTime(file.uploadTime)}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="previewFile('${file.url}', '${file.name}', '${file.category}')" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="View">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                    <button onclick="copyFileUrl('${file.fileName}')" class="p-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors" title="Copy URL">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
<button onclick="deleteFile('${file.fileName}', '${file.name}')" class="p-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        `;
    }

    function updateStats(stats) {
        document.getElementById('images-count').textContent = `${stats.images} Images`;
        document.getElementById('videos-count').textContent = `${stats.videos} Videos`;
        document.getElementById('docs-count').textContent = `${stats.documents} Documents`;
    }

    function formatUploadTime(timestamp) {
        if (!timestamp) return 'Unknown';
        const date = new Date(timestamp / 1000); // Convert from microseconds
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }

    // Media filter functionality
    const mediaFilters = document.querySelectorAll('.media-filter');
    mediaFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            mediaFilters.forEach(f => {
                f.classList.remove('active', 'bg-blue-600', 'text-white');
                f.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            });
            this.classList.add('active', 'bg-blue-600', 'text-white');
            this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
            
            loadMedia(this.dataset.filter);
        });
    });

    // View toggle functionality
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const mediaGrid = document.getElementById('media-grid');
    const mediaList = document.getElementById('media-list');

    gridView.addEventListener('click', function() {
        this.classList.add('active', 'bg-blue-600', 'text-white');
        this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        listView.classList.remove('active', 'bg-blue-600', 'text-white');
        listView.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        
        mediaGrid.classList.remove('hidden');
        mediaList.classList.add('hidden');
    });

    listView.addEventListener('click', function() {
        this.classList.add('active', 'bg-blue-600', 'text-white');
        this.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        gridView.classList.remove('active', 'bg-blue-600', 'text-white');
        gridView.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
        
        mediaList.classList.remove('hidden');
        mediaGrid.classList.add('hidden');
    });

    // Search functionality
    const searchInput = document.getElementById('media-search');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchMedia(this.value);
        }, 500);
    });

    async function searchMedia(query) {
        if (!query.trim()) {
            loadMedia(currentFilter);
            return;
        }

        try {
            const queryType = currentFilter === 'all' ? '' : currentFilter;
            const response = await fetch(`/media/search?q=${encodeURIComponent(query)}&type=${queryType}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    renderMedia(data.media);
                }
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    // Upload modal functionality
    const uploadBtn = document.getElementById('upload-media-btn');
    const emptyUploadBtn = document.getElementById('empty-upload-btn');
    const uploadModal = document.getElementById('upload-modal');
    const closeUploadModal = document.getElementById('close-upload-modal');
    const cancelUpload = document.getElementById('cancel-upload');
    const uploadDropzone = document.getElementById('upload-dropzone');
    const fileInput = document.getElementById('file-input');
    const startUploadBtn = document.getElementById('start-upload');

    uploadBtn.addEventListener('click', openUploadModal);
    emptyUploadBtn.addEventListener('click', openUploadModal);
    closeUploadModal.addEventListener('click', closeUploadModalFunc);
    cancelUpload.addEventListener('click', closeUploadModalFunc);
    uploadDropzone.addEventListener('click', () => fileInput.click());
    startUploadBtn.addEventListener('click', handleUpload);

    function openUploadModal() {
        // Check storage limit before opening modal
        if (storageInfo && storageInfo.is_over_limit) {
            showError('Storage limit reached. Please upgrade your storage to upload more files.');
            return;
        }
        uploadModal.classList.remove('hidden');
    }

    function closeUploadModalFunc() {
        uploadModal.classList.add('hidden');
        document.getElementById('upload-queue').innerHTML = '';
        fileInput.value = '';
        hideUploadProgress();
    }

    // File drag and drop
    uploadDropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadDropzone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
    });

    uploadDropzone.addEventListener('dragleave', () => {
        uploadDropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
    });

    uploadDropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadDropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        const uploadQueue = document.getElementById('upload-queue');
        uploadQueue.innerHTML = '';
        
        Array.from(files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
            
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            const fileIcon = getFileIcon(file.type);
            
            fileItem.innerHTML = `
                <div class="flex items-center gap-3">
                    <i data-lucide="${fileIcon}" class="w-5 h-5 text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">${file.name}</p>
                        <p class="text-xs text-gray-500">${fileSize} MB</p>
                    </div>
                </div>
                <button type="button" class="text-red-600 hover:text-red-700" onclick="this.parentElement.remove()">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;
            
            uploadQueue.appendChild(fileItem);
        });
        
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return 'image';
        if (mimeType.startsWith('video/')) return 'video';
        if (mimeType.includes('pdf')) return 'file-text';
        if (mimeType.includes('word') || mimeType.includes('document')) return 'file-text';
        if (mimeType.includes('zip') || mimeType.includes('rar')) return 'archive';
        return 'file';
    }

async function handleUpload() {
    const files = fileInput.files;
    if (!files || files.length === 0) {
        showError('Please select files to upload');
        return;
    }

    // Check storage limit before uploading
    if (storageInfo && storageInfo.is_over_limit) {
        showError('Storage limit reached. Cannot upload more files.');
        return;
    }

    const formData = new FormData();
    Array.from(files).forEach(file => {
        formData.append('files[]', file);
    });
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    try {
        showUploadProgress();
        startUploadBtn.disabled = true;
        startUploadBtn.innerHTML = '<div class="inline-flex items-center gap-2"><div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>Uploading...</div>';
        
        // Start progress animation
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90; // Don't complete until actual response
            updateProgressBar(progress);
        }, 200);
        
        const response = await fetch('/media/upload', {
            method: 'POST',
            body: formData
        });

        // Clear the progress interval and get response
        clearInterval(progressInterval);
        const data = await response.json();
        
        // Always complete progress bar regardless of success/failure
        updateProgressBar(100);
        
        // Small delay to show 100% completion before showing result
        setTimeout(() => {
            if (data.success) {
                showSuccess(data.message || `Successfully uploaded ${data.count} files`);
                loadMedia(currentFilter); // Refresh media list
                loadStorageInfo(); // Refresh storage info
                closeUploadModalFunc();
            } else {
                if (data.storage_exceeded) {
                    showStorageExceededError(data.message);
                } else {
                    showError(data.message || 'Upload failed');
                }
            }

            if (data.errors && data.errors.length > 0) {
                data.errors.forEach(error => {
                    showError(`${error.file}: ${error.error}`);
                });
            }
        }, 500);

    } catch (error) {
        console.error('Upload error:', error);
        clearInterval(progressInterval);
        updateProgressBar(100);
        setTimeout(() => {
            showError('Upload failed. Please try again.');
        }, 500);
    } finally {
        setTimeout(() => {
            hideUploadProgress();
            startUploadBtn.disabled = false;
            startUploadBtn.innerHTML = 'Start Upload';
        }, 600);
    }
}

function updateProgressBar(percentage) {
    const progressBar = document.getElementById('upload-progress-bar');
    const progressText = document.getElementById('upload-percentage');
    
    if (progressBar) {
        progressBar.style.width = Math.min(percentage, 100) + '%';
    }
    if (progressText) {
        progressText.textContent = Math.min(Math.round(percentage), 100) + '%';
    }
}

function showStorageExceededError(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm';
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <div class="font-semibold mb-1">Storage Limit Exceeded</div>
                <div class="text-sm text-red-100">${message}</div>
                <a href="/billing" class="inline-block mt-2 px-3 py-1 bg-white text-red-600 rounded text-sm font-medium hover:bg-red-50 transition-colors">
                    Upgrade Storage
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-200 hover:text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    if (window.lucide) {
        lucide.createIcons();
    }
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 20000);
}

function updateProgressBar(percentage) {
    const progressBar = document.getElementById('upload-progress-bar');
    const progressText = document.getElementById('upload-percentage');
    
    if (progressBar) {
        progressBar.style.width = Math.min(percentage, 100) + '%';
    }
    if (progressText) {
        progressText.textContent = Math.min(Math.round(percentage), 100) + '%';
    }
}

function showStorageExceededError(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm';
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
            <div class="flex-1">
                <div class="font-semibold mb-1">Storage Limit Exceeded</div>
                <div class="text-sm text-red-100">${message}</div>
                <a href="/billing" class="inline-block mt-2 px-3 py-1 bg-white text-red-600 rounded text-sm font-medium hover:bg-red-50 transition-colors">
                    Upgrade Storage
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-200 hover:text-white">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    if (window.lucide) {
        lucide.createIcons();
    }
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 20000);
}

    function showUploadProgress() {
        document.getElementById('upload-progress').classList.remove('hidden');
    }

    function hideUploadProgress() {
        document.getElementById('upload-progress').classList.add('hidden');
    }

    // Global functions for file actions
    window.previewFile = function(url, name, category) {
        const modal = document.getElementById('preview-modal');
        const content = document.getElementById('preview-content');
        
        if (category === 'images') {
            content.innerHTML = `<img src="${url}" alt="${name}" class="max-w-full max-h-full object-contain">`;
        } else if (category === 'videos') {
            content.innerHTML = `<video controls class="max-w-full max-h-full"><source src="${url}" type="video/mp4">Your browser does not support the video tag.</video>`;
        } else {
            content.innerHTML = `
                <div class="p-8 text-center">
                    <i data-lucide="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">${name}</h3>
                    <a href="${url}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        Download File
                    </a>
                </div>
            `;
        }
        
        modal.classList.remove('hidden');
        
        if (window.lucide) {
            lucide.createIcons();
        }
    };

    window.copyFileUrl = async function(filePath) {
        try {
            const response = await fetch(`/media/file-info?path=${encodeURIComponent(filePath)}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    await navigator.clipboard.writeText(data.url);
                    showSuccess('URL copied to clipboard');
                } else {
                    showError('Failed to get file URL');
                }
            }
        } catch (error) {
            console.error('Copy URL error:', error);
            showError('Failed to copy URL');
        }
    };

 window.deleteFile = async function(filePath, fileName) {
    if (!confirm(`Are you sure you want to delete "${fileName}"?`)) {
        return;
    }

    // Find the delete button and add spinner
    const deleteBtn = event.target.closest('button');
    const originalContent = deleteBtn.innerHTML;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<div class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></div>';

    try {
        const response = await fetch('/media/delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ path: filePath })
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                showSuccess('File deleted successfully');
                loadMedia(currentFilter);
                loadStorageInfo();
            } else {
                showError(data.message || 'Delete failed');
            }
        } else {
            showError('Delete failed');
        }

    } catch (error) {
        console.error('Delete error:', error);
        showError('Failed to delete file');
    } finally {
        // Restore button state
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = originalContent;
    }
};

    // Preview modal close
    document.getElementById('close-preview').addEventListener('click', function() {
        document.getElementById('preview-modal').classList.add('hidden');
    });

    // UI Helper functions
    function showLoading(show) {
        const loading = document.getElementById('media-loading');
        const grid = document.getElementById('media-grid');
        
        if (show) {
            loading.classList.remove('hidden');
            grid.classList.add('hidden');
        } else {
            loading.classList.add('hidden');
            grid.classList.remove('hidden');
        }
    }

    function showSuccess(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function showError(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
});
</script>

@endsection