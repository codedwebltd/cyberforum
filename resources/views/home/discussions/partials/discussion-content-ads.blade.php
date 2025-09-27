{{-- resources/views/home/discussions/partials/discussion-content-ads.blade.php --}}

{{-- Top Banner Ad (appears at the start of content) --}}
@if($position === 'top')
<div class="my-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-600">
    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 text-center">Advertisement</div>
    <div class="bg-gradient-to-r from-blue-100 to-purple-100 dark:from-gray-700 dark:to-gray-600 rounded-xl h-32 flex items-center justify-center text-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-500">
        <div>
            <i data-lucide="monitor" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Leaderboard Ad</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">728x90</div>
        </div>
    </div>
</div>
@endif

{{-- Mid-Content Banner Ad (appears in middle of content) --}}
@if($position === 'mid')
<div class="my-8 p-4 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 text-center">Advertisement</div>
    <div class="bg-gradient-to-br from-green-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-xl h-64 flex items-center justify-center text-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-500">
        <div>
            <i data-lucide="image" class="w-16 h-16 text-gray-400 mx-auto mb-3"></i>
            <div class="text-lg font-semibold text-gray-600 dark:text-gray-300 mb-2">Medium Rectangle</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">300x250</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Premium ad placement</div>
        </div>
    </div>
</div>
@endif

{{-- Bottom Banner Ad (appears at the end of content) --}}
@if($position === 'bottom')
<div class="my-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-600">
    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 text-center">Sponsored</div>
    <div class="bg-gradient-to-r from-orange-100 to-pink-100 dark:from-gray-700 dark:to-gray-600 rounded-xl h-40 flex items-center justify-center text-center p-6 border-2 border-dashed border-gray-300 dark:border-gray-500">
        <div>
            <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-orange-500 to-pink-500 rounded-full flex items-center justify-center">
                <i data-lucide="star" class="w-6 h-6 text-white"></i>
            </div>
            <div class="text-lg font-bold text-gray-900 dark:text-white mb-2">Promote Your Business</div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">Reach engaged users</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">728x160 Banner</div>
        </div>
    </div>
</div>
@endif

{{-- Enhanced Inline Text Ad (super responsive and adaptive) --}}
@if($position === 'inline')
<div class="my-6 sm:my-8">
    <div class="relative group">
        <!-- Main Ad Container -->
        <div class="p-4 sm:p-5 lg:p-6 bg-gradient-to-br from-amber-50/80 via-yellow-50/60 to-orange-50/40 dark:from-amber-900/20 dark:via-yellow-900/15 dark:to-orange-900/10 rounded-2xl border border-amber-200/60 dark:border-amber-700/40 shadow-sm hover:shadow-md transition-all duration-300 backdrop-blur-sm">
            
            <!-- Sponsored Label -->
            <div class="flex items-center justify-center mb-4">
                <div class="px-3 py-1 bg-amber-100/80 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-full border border-amber-200/60 dark:border-amber-700/50">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Sponsored
                    </span>
                </div>
            </div>

            <!-- Ad Content -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <!-- Icon/Logo -->
                <div class="flex-shrink-0 mx-auto sm:mx-0">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-18 lg:h-18 bg-gradient-to-br from-amber-400 via-yellow-500 to-orange-500 dark:from-amber-500 dark:via-yellow-600 dark:to-orange-600 rounded-2xl flex items-center justify-center shadow-lg ring-2 ring-amber-200/50 dark:ring-amber-700/50 group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="zap" class="w-7 h-7 sm:w-8 sm:h-8 text-white drop-shadow-sm"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 text-center sm:text-left min-w-0">
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 dark:from-white dark:via-gray-100 dark:to-white bg-clip-text text-transparent mb-2 leading-tight">
                        Boost Your Skills Today
                    </h3>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed mb-3 max-w-md">
                        Professional development courses designed for modern developers. Learn cutting-edge technologies and advance your career.
                    </p>
                    
                    <!-- Features/Benefits -->
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 mb-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Expert-led
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-medium rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Lifetime Access
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Certified
                        </span>
                    </div>
                </div>

                <!-- Call-to-Action -->
                <div class="flex-shrink-0 w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 dark:from-amber-600 dark:to-orange-600 dark:hover:from-amber-700 dark:hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500/50 dark:focus:ring-amber-400/50">
                            <span class="flex items-center justify-center gap-2">
                                Start Learning
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </button>
                        <button class="w-full sm:w-auto px-4 py-3 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium rounded-xl border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-300 backdrop-blur-sm">
                            Learn More
                        </button>
                    </div>
                </div>
            </div>

            <!-- Subtle Animation Element -->
            <div class="absolute top-2 right-2 w-2 h-2 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full animate-pulse"></div>
        </div>
    </div>
</div>
@endif

{{-- Mobile Banner (shows only on mobile) --}}
@if($position === 'mobile')
<div class="block sm:hidden my-4 p-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-800 dark:to-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
    <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 text-center">Advertisement</div>
    <div class="bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600 rounded-lg h-20 flex items-center justify-center text-center border-2 border-dashed border-gray-300 dark:border-gray-500">
        <div>
            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">Mobile Banner</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">320x50</div>
        </div>
    </div>
</div>
@endif

{{-- Square Ad (for compact spaces) --}}
@if($position === 'square')
<div class="my-6 p-4 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl border border-gray-200 dark:border-gray-600 max-w-xs mx-auto">
    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3 text-center">Advertisement</div>
    <div class="bg-gradient-to-br from-cyan-100 to-blue-100 dark:from-gray-700 dark:to-gray-600 rounded-xl aspect-square flex items-center justify-center text-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-500">
        <div>
            <i data-lucide="square" class="w-12 h-12 text-gray-400 mx-auto mb-2"></i>
            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-1">Square Ad</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">300x300</div>
        </div>
    </div>
</div>
@endif