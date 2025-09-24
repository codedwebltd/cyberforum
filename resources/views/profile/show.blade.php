@extends('inc.home.app')
@section('title', 'Profile - ' . config('app.name'))
@section('content')

<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Profile Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
            <!-- Cover Image -->
            <div class="h-32 bg-gradient-to-r from-blue-600 to-purple-600 relative">
                @if(auth()->user()->cover_url)
                <img src="{{ auth()->user()->cover_url }}" alt="Cover" class="w-full h-full object-cover">
                @endif
                <button class="absolute top-4 right-4 w-8 h-8 bg-black/20 hover:bg-black/40 rounded-lg flex items-center justify-center text-white transition-colors">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Profile Info -->
            <div class="px-6 py-6">
                <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-16 sm:-mt-12">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-24 h-24 rounded-2xl border-4 border-white dark:border-gray-800 shadow-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                            @if(auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                            <span class="text-2xl font-bold text-white">{{ $user_initials }}</span>
                            @endif
                        </div>
                        <button class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-colors">
                            <i data-lucide="camera" class="w-3 h-3"></i>
                        </button>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400">{{ $profession }}</p>
                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                        Joined {{ auth()->user()->created_at->format('M Y') }}
                                    </span>
                                    @if(auth()->user()->location)
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        {{ auth()->user()->location }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ Illuminate\Support\Number::abbreviate($total_points,0,2000) }}</div>
                                    <div class="text-xs text-gray-500">Points</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">${{ number_format($points_value, 2) }}</div>
                                    <div class="text-xs text-gray-500">Value</div>
                                </div>
                                <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- About Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">About</h2>
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">Edit</button>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ auth()->user()->bio ?? 'No bio available. Tell us about yourself!' }}
                    </p>
                </div>

                <!-- Onboarding Progress -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Completion</h2>
                        <span class="text-sm font-medium text-blue-600">{{ $onboarding_completion }}%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-6">
                        <div class="bg-gradient-to-r from-blue-600 to-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $onboarding_completion }}%"></div>
                    </div>

                    <!-- Onboarding Steps -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                        $steps = [
                            'profile_basic_info' => ['title' => 'Basic Info', 'icon' => 'user'],
                            'community_preferences' => ['title' => 'Preferences', 'icon' => 'settings'],
                            'interests_skills' => ['title' => 'Interests', 'icon' => 'star'],
                            'privacy_settings' => ['title' => 'Privacy', 'icon' => 'shield']
                        ];
                        @endphp
                        
                        @foreach($steps as $stepKey => $step)
                        @php
                        $stepData = $$stepKey ?? [];
                        $isCompleted = !empty($stepData);
                        @endphp
                        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" onclick="openStepModal('{{ $stepKey }}')">
                            <div class="w-8 h-8 rounded-lg {{ $isCompleted ? 'bg-green-100 dark:bg-green-900/20' : 'bg-gray-200 dark:bg-gray-600' }} flex items-center justify-center">
                                <i data-lucide="{{ $step['icon'] }}" class="w-4 h-4 {{ $isCompleted ? 'text-green-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $step['title'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $isCompleted ? 'Completed' : 'Not completed' }}
                                </div>
                            </div>
                            @if($isCompleted)
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                            @else
                            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-400"></i>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Activity Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Activity Overview</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">0</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Posts</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">0</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Comments</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">0</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Likes</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">0</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Followers</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Details</h3>
                    <div class="space-y-3">
                        @if(auth()->user()->website)
                        <div class="flex items-center gap-3">
                            <i data-lucide="globe" class="w-4 h-4 text-gray-400"></i>
                            <a href="{{ auth()->user()->website }}" class="text-blue-600 hover:text-blue-700 text-sm">Website</a>
                        </div>
                        @endif
                        
                        @if(auth()->user()->email_verified_at)
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Email verified</span>
                            <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
                        </div>
                        @endif
                        
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->profile_public ? 'Public profile' : 'Private profile' }}
                            </span>
                        </div>
                        
                        @if(auth()->user()->last_active_at)
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->is_online ? 'Online now' : 'Last seen ' . auth()->user()->last_active_at->diffForHumans() }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Verification</span>
                            <span class="px-2 py-1 text-xs font-medium {{ auth()->user()->is_verified ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} rounded-full">
                                {{ auth()->user()->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Status</span>
                            <span class="px-2 py-1 text-xs font-medium {{ auth()->user()->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }} rounded-full">
                                {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Reputation</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->reputation ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step Detail Modal -->
    <div id="step-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 max-w-lg w-full mx-4 max-h-[80vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 id="modal-step-title" class="text-lg font-semibold text-gray-900 dark:text-white">Step Details</h3>
                    <button id="close-step-modal" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                <div id="modal-step-content" class="space-y-4">
                    <!-- Dynamic content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Profile specific styles */
.profile-card {
    transition: all 0.2s ease-in-out;
}

.profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Modal animations */
#step-modal > div {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .grid.grid-cols-2.md\\:grid-cols-4 {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    const stepModal = document.getElementById('step-modal');
    const modalTitle = document.getElementById('modal-step-title');
    const modalContent = document.getElementById('modal-step-content');

    // Step data from backend
    const stepData = {
        profile_basic_info: @json($profile_basic_info ?? []),
        community_preferences: @json($community_preferences ?? []),
        interests_skills: @json($interests_skills ?? []),
        privacy_settings: @json($privacy_settings ?? [])
    };

    // Open step modal
    window.openStepModal = function(stepKey) {
        const stepTitles = {
            profile_basic_info: 'Basic Information',
            community_preferences: 'Community Preferences',
            interests_skills: 'Interests & Skills',
            privacy_settings: 'Privacy Settings'
        };

        modalTitle.textContent = stepTitles[stepKey] || 'Step Details';
        
        // Generate content based on step data
        const data = stepData[stepKey];
        let content = '';

        if (!data || Object.keys(data).length === 0) {
            content = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No data available for this step.</p>';
        } else {
            content = '<div class="space-y-3">';
            for (const [key, value] of Object.entries(data)) {
                if (value) {
                    const formattedKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    content += `
                        <div class="flex justify-between items-start gap-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">${formattedKey}</span>
                            <span class="text-gray-900 dark:text-white text-sm text-right">${Array.isArray(value) ? value.join(', ') : value}</span>
                        </div>
                    `;
                }
            }
            content += '</div>';
        }

        modalContent.innerHTML = content;
        stepModal.classList.remove('hidden');
        stepModal.classList.add('flex');
    };

    // Close modal
    function closeStepModal() {
        stepModal.classList.add('hidden');
        stepModal.classList.remove('flex');
    }

    document.getElementById('close-step-modal').addEventListener('click', closeStepModal);

    // Close modal when clicking outside
    stepModal.addEventListener('click', function(e) {
        if (e.target === stepModal) {
            closeStepModal();
        }
    });

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !stepModal.classList.contains('hidden')) {
            closeStepModal();
        }
    });
});
</script>

@endsection