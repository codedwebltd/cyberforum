@php
    $completedSteps = $completedSteps ?? collect([]);
    
    // Determine current step based on completed steps
    $currentStep = 1;
    if($completedSteps->contains('profile_basic_info') && !$completedSteps->contains('community_preferences')) {
        $currentStep = 2;
    } elseif($completedSteps->contains('community_preferences') && !$completedSteps->contains('interests_skills')) {
        $currentStep = 3;
    } elseif($completedSteps->contains('interests_skills') && !$completedSteps->contains('privacy_settings')) {
        $currentStep = 4;
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CyberForum - Complete Your Profile</title>
    <meta name="description" content="Complete your CyberForum profile and join the community">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        .skill-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .scale-98 {
            transform: scale(0.98);
        }
        input, select, textarea, button, label {
            transition: all 0.2s ease;
        }
        @media (max-width: 768px) {
            input[type="checkbox"],
            input[type="radio"] {
                width: 20px;
                height: 20px;
            }
        }
    </style>
</head>

<body class="transition-colors duration-300 font-inter bg-gray-50">
    <div class="flex flex-col min-h-screen lg:flex-row">
        <!-- Left Side - Progress & Welcome -->
        <div class="relative overflow-hidden lg:w-2/5 bg-gradient-to-br from-primary-600 via-primary-500 to-primary-700">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative z-10 flex flex-col items-center justify-center p-8 text-white min-h-64 lg:min-h-screen">
                <div class="max-w-md space-y-6 text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto bg-white/20 rounded-2xl backdrop-blur-sm">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="mb-2 text-3xl font-bold lg:text-4xl font-poppins">Welcome to CyberForum!</h1>
                        <p class="text-lg lg:text-xl text-primary-100"> Let's set up your profile</p>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="w-full max-w-sm mx-auto">
                        <div class="flex items-center justify-between mb-2 text-sm">
                            <span>Step {{ $currentStep }} of 4</span>
                            <span>{{ round(($currentStep / 4) * 100) }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/20">
                            <div class="h-2 transition-all duration-500 ease-out bg-white rounded-full" style="width: {{ ($currentStep / 4) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm text-primary-200">
                        @foreach(['Profile Picture & Basic Info', 'Community Preferences', 'Interests & Skills', 'Privacy Settings'] as $index => $stepName)
                        <div class="flex items-center space-x-3">
                            @if($index + 1 < $currentStep)
                                <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @elseif($index + 1 == $currentStep)
                                <div class="w-4 h-4 border-2 rounded-full bg-primary-200 border-primary-200"></div>
                            @else
                                <div class="w-4 h-4 border-2 rounded-full border-primary-200"></div>
                            @endif
                            <span>{{ $stepName }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Onboarding Steps -->
        <div class="flex flex-col justify-between flex-1 p-6 lg:p-8">
            <div class="flex items-center justify-center flex-1">
                <div class="w-full max-w-2xl">
                    
                    <!-- Step 1: Profile Picture & Basic Info -->
                    @if($currentStep == 1)
                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 lg:text-3xl font-poppins">Let's start with your profile</h2>
                            <p class="text-gray-600">{{ ucfirst(auth()->user()->name) }}, Help others recognize you in the community</p>
                        </div>

                        <form id="step1-form" action="{{ route('onboarding.update-step') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step_name" value="profile_basic_info">

                            <!-- Profile Picture Upload -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <div id="profile-preview" class="flex items-center justify-center w-32 h-32 mx-auto overflow-hidden bg-gray-200 border-4 rounded-full cursor-pointer border-primary-200 hover:border-primary-400 transition-all">
                                        <svg id="default-avatar" class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <img id="profile-image" class="hidden object-cover w-full h-full" alt="Profile" style="display: none;">
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-center space-y-2">
                                    <input type="file" id="profile-upload" name="avatar" accept="image/*" class="hidden">
                                    <label for="profile-upload" class="px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg cursor-pointer bg-primary-500 hover:bg-primary-600 touch-manipulation">
                                        Choose Profile Picture
                                    </label>
                                    <p class="text-xs text-gray-500">Maximum 10MB (JPEG, PNG, GIF)</p>
                                </div>
                            </div>

                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Gender</label>
                                    <select name="gender" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="">Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="non-binary">Non-binary</option>
                                        <option value="prefer-not-to-say">Prefer not to say</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Location</label>
                                    <input type="text" name="location" placeholder="City, Country" class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Profession</label>
                                    <input type="text" name="profession" placeholder="Software Developer, Designer, etc." class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Bio</label>
                                <textarea name="bio" rows="3" maxlength="150" placeholder="Tell us a bit about yourself..." class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-primary-500"></textarea>
                                <p class="mt-1 text-sm text-gray-500">Maximum 150 characters</p>
                            </div>

                            <!-- Navigation -->
                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('onboarding.skip') }}" class="px-6 py-3 text-gray-500 transition-colors hover:text-gray-700">
                                    Skip
                                </a>
                                <button type="submit" class="px-8 py-3 text-white transition-all rounded-lg bg-primary-500 hover:bg-primary-600">
                                    Next →
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Step 2: Community Preferences -->
                    @if($currentStep == 2)
                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 lg:text-3xl font-poppins">Community Preferences</h2>
                            <p class="text-gray-600">How do you want to engage with the community?</p>
                        </div>

                        <form id="step2-form" action="{{ route('onboarding.update-step') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step_name" value="community_preferences">

                            <div>
                                <label class="block mb-4 text-sm font-medium text-gray-700">Which topics interest you most? (Select up to 5)</label>
                                <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                                    @php
                                        $interests = [
                                            'technology' => 'Technology',
                                            'gaming' => 'Gaming',
                                            'design' => 'Design',
                                            'business' => 'Business',
                                            'science' => 'Science',
                                            'art-creative' => 'Art & Creative',
                                            'sports' => 'Sports',
                                            'health-fitness' => 'Health & Fitness',
                                            'entertainment' => 'Entertainment'
                                        ];
                                    @endphp
                                    @foreach($interests as $value => $label)
                                    <label class="flex items-center p-3 transition-colors border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="checkbox" name="interests[]" value="{{ $value }}" class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600">
                                        <span class="ml-3 text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block mb-4 text-sm font-medium text-gray-700">How active do you plan to be?</label>
                                <div class="space-y-3">
                                    @foreach([
                                        'lurker' => ['Observer', 'I prefer to read and learn from discussions'],
                                        'occasional' => ['Occasional Participant', "I'll comment and share when I have something valuable to add"],
                                        'active' => ['Active Member', 'I plan to participate regularly in discussions and help others']
                                    ] as $value => $details)
                                    <label class="flex items-center p-4 transition-colors border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="engagement_level" value="{{ $value }}" required class="w-4 h-4 bg-gray-100 border-gray-300 text-primary-600">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-700">{{ $details[0] }}</div>
                                            <div class="text-xs text-gray-500">{{ $details[1] }}</div>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('onboarding.skip') }}" class="px-6 py-3 text-gray-500 transition-colors hover:text-gray-700">
                                    Skip
                                </a>
                                <button type="submit" class="px-8 py-3 text-white transition-all rounded-lg bg-primary-500 hover:bg-primary-600">
                                    Next →
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Step 3: Interests & Skills -->
                    @if($currentStep == 3)
                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 lg:text-3xl font-poppins">Your Skills & Interests</h2>
                            <p class="text-gray-600">Help others discover your expertise</p>
                        </div>

                        <form id="step3-form" action="{{ route('onboarding.update-step') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step_name" value="interests_skills">

                           <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Skills & Technologies</label>
                            <input type="text" id="skills-input" placeholder="Type skills separated by commas (e.g., JavaScript, React, PHP)" class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 bg-white border border-gray-300 rounded-lg">
                            <div id="skills-container" class="flex flex-wrap gap-2 mt-3"></div>
                            <input type="hidden" name="skills" id="skills-hidden">
                        </div>

                            <div>
                                <label class="block mb-4 text-sm font-medium text-gray-700">Overall experience level</label>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    @foreach(['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'expert' => 'Expert'] as $value => $label)
                                    <label class="flex flex-col items-center p-4 transition-colors border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="experience_level" value="{{ $value }}" required class="w-4 h-4 mb-2 bg-gray-100 border-gray-300 text-primary-600">
                                        <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block mb-4 text-sm font-medium text-gray-700">Social profiles (optional)</label>
                                <div class="space-y-3">
                                    <input type="url" name="social_links[twitter]" placeholder="Twitter/X profile URL" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg">
                                    <input type="url" name="social_links[linkedin]" placeholder="LinkedIn profile URL" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg">
                                    <input type="url" name="social_links[github]" placeholder="GitHub profile URL" class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg">
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('onboarding.skip') }}" class="px-6 py-3 text-gray-500 transition-colors hover:text-gray-700">
                                    Skip
                                </a>
                                <button type="submit" class="px-8 py-3 text-white transition-all rounded-lg bg-primary-500 hover:bg-primary-600">
                                    Next →
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Step 4: Privacy Settings -->
                    @if($currentStep == 4)
                    <div class="space-y-8">
                        <div class="text-center">
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 lg:text-3xl font-poppins">Privacy & Notifications</h2>
                            <p class="text-gray-600">Control how you interact with the community</p>
                        </div>

                        <form id="step4-form" action="{{ route('onboarding.update-step') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="step_name" value="privacy_settings">

                            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-medium text-gray-900">Profile Visibility</h3>
                                <div class="space-y-4">
                                    @foreach([
                                        'profile_public' => ['Public Profile', 'Allow others to view your profile and posts'],
                                        'show_online_status' => ['Show Online Status', "Let others see when you're online"],
                                        'allow_messages' => ['Allow Direct Messages', 'Let other members send you private messages']
                                    ] as $name => $details)
                                    <label class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">{{ $details[0] }}</div>
                                            <div class="text-xs text-gray-500">{{ $details[1] }}</div>
                                        </div>
                                        <input type="checkbox" name="{{ $name }}" value="1" checked class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-medium text-gray-900">Notifications</h3>
                                <div class="space-y-4">
                                    @foreach([
                                        'email_notifications' => ['Email Notifications', 'Receive important updates via email', true],
                                        'reply_notifications' => ['Reply Notifications', 'Get notified when someone replies to your posts', true],
                                        'weekly_digest' => ['Weekly Digest', 'Receive a summary of community highlights', false],
                                        'marketing_emails' => ['Marketing Communications', 'Receive updates about new features and events', false]
                                    ] as $name => $details)
                                    <label class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">{{ $details[0] }}</div>
                                            <div class="text-xs text-gray-500">{{ $details[1] }}</div>
                                        </div>
                                        <input type="checkbox" name="{{ $name }}" value="1" {{ $details[2] ? 'checked' : '' }} class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600">
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('onboarding.skip') }}" class="px-6 py-3 text-gray-500 transition-colors hover:text-gray-700">
                                    Skip
                                </a>
                                <button type="submit" class="px-8 py-3 text-white transition-all rounded-lg bg-green-500 hover:bg-green-600">
                                    Complete ✓
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
// Clean Onboarding Script - Working Mobile Upload
$(document).ready(function() {
    console.log('Clean onboarding script loaded');
    
    // ============================================
    // MOBILE FILE UPLOAD HANDLING
    // ============================================
    let selectedFile = null;
    
    // File input change handler
    $('#profile-upload').on('change', function(e) {
        console.log('File input changed');
        const file = e.target.files[0];
        selectedFile = file;
        
        if (!file) {
            console.log('No file selected');
            return;
        }
        
        console.log('File selected:', {
            name: file.name,
            type: file.type,
            size: file.size
        });
        
        // Validation
        if (file.size > 10 * 1024 * 1024) {
            alert('File too large (max 10MB)');
            $(this).val('');
            selectedFile = null;
            return;
        }
        
        const fileName = file.name.toLowerCase();
        const validExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
        const isValidFile = validExtensions.some(ext => fileName.endsWith(ext)) || 
                           (file.type && file.type.startsWith('image/'));
        
        if (!isValidFile) {
            alert('Please select an image file');
            $(this).val('');
            selectedFile = null;
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(event) {
            console.log('File preview loaded');
            $('#profile-image').attr('src', event.target.result).removeClass('hidden').show();
            $('#default-avatar').addClass('hidden').hide();
        };
        
        reader.onerror = function() {
            console.error('FileReader error');
            alert('Error reading file');
            selectedFile = null;
        };
        
        reader.readAsDataURL(file);
    });
    
    // Make preview clickable
    $('#profile-preview').on('click', function() {
        console.log('Preview clicked, triggering file input');
        $('#profile-upload').click();
    });
    
    // ============================================
    // INTEREST SELECTION (MAX 5)
    // ============================================
    $('input[name="interests[]"]').on('change', function() {
        const checkedCount = $('input[name="interests[]"]:checked').length;
        console.log('Interests selected:', checkedCount);
        
        if (checkedCount >= 5) {
            $('input[name="interests[]"]').not(':checked').prop('disabled', true)
                .closest('label').addClass('opacity-50 cursor-not-allowed');
        } else {
            $('input[name="interests[]"]').prop('disabled', false)
                .closest('label').removeClass('opacity-50 cursor-not-allowed');
        }
    });
    
    // ============================================
    // SKILLS TAG INPUT
    // ============================================
    let skills = [];
    
    function addSkill(skillText) {
        const trimmedSkill = skillText.trim();
        
        if (!trimmedSkill) return;
        if (trimmedSkill.length > 50) {
            alert('Skill name too long (max 50 characters)');
            return;
        }
        if (skills.includes(trimmedSkill)) {
            alert('Skill already added');
            return;
        }
        if (skills.length >= 20) {
            alert('Maximum 20 skills allowed');
            return;
        }
        
        skills.push(trimmedSkill);
        console.log('Skill added:', trimmedSkill, 'Total skills:', skills.length);
        
        const tag = $(`
            <div class="skill-tag inline-flex items-center px-3 py-1 rounded-full text-white text-sm font-medium">
                <span>${escapeHtml(trimmedSkill)}</span>
                <button type="button" class="ml-2 focus:outline-none hover:opacity-75 remove-skill" data-skill="${escapeHtml(trimmedSkill)}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `);
        
        $('#skills-container').append(tag);
        updateSkillsHidden();
        $('#skills-input').val('');
    }
    
    function updateSkillsHidden() {
        $('#skills-hidden').val(JSON.stringify(skills));
        console.log('Skills updated:', skills);
    }
    
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }
    
    // Remove skill handler
    $(document).on('click', '.remove-skill', function() {
        const skillText = $(this).data('skill');
        skills = skills.filter(s => s !== skillText);
        $(this).closest('.skill-tag').remove();
        updateSkillsHidden();
        console.log('Skill removed:', skillText);
    });
    
    // Skills input handlers
// Skills input handlers with comma trigger
$('#skills-input').on('input', function(e) {
    const value = $(this).val();
    if (value.includes(',')) {
        const skills = value.split(',');
        const skillToAdd = skills[0].trim();
        
        if (skillToAdd) {
            addSkill(skillToAdd);
        }
        
        // Set input to remaining text after comma
        const remaining = skills.slice(1).join(',');
        $(this).val(remaining);
    }
}).on('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkill($(this).val());
    }
}).on('blur', function() {
    if ($(this).val().trim()) {
        addSkill($(this).val());
    }
});
    
    // ============================================
    // PROVEN WORKING FORM SUBMISSION
    // ============================================
    $('form[id^="step"]').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();
        const hasFile = $form.find('input[type="file"]').length > 0 && selectedFile;
        const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || 'ontouchstart' in window;
        
        console.log('=== FORM SUBMISSION START ===');
        console.log('Form action:', $form.attr('action'));
        console.log('Has file:', hasFile);
        console.log('Is mobile:', isMobile);
        console.log('Selected file:', selectedFile);
        
        // Show loading state
        $submitBtn.prop('disabled', true).html(`
            <svg class="inline w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `);
        
        if (isMobile && hasFile) {
            console.log('=== MOBILE FILE UPLOAD - CONVERTING TO BASE64 ===');
            
            // Convert file to base64 for mobile browsers
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('File converted to base64, length:', e.target.result.length);
                
                // Create regular form data (no file)
                const formData = new FormData();
                
                // Add all form fields except file
                $form.find('input, select, textarea').each(function() {
                    const $input = $(this);
                    const name = $input.attr('name');
                    const type = $input.attr('type');
                    
                    if (type === 'file') {
                        // Skip file input - we'll send base64 instead
                        return;
                    } else if (name === 'skills' && type === 'hidden') {
                        // Handle skills array specially for mobile
                        const skillsJson = $input.val();
                        if (skillsJson) {
                            try {
                                const skillsArray = JSON.parse(skillsJson);
                                skillsArray.forEach((skill, index) => {
                                    formData.append(`skills[${index}]`, skill);
                                });
                            } catch (e) {
                                console.error('Error parsing skills JSON:', e);
                            }
                        }
                        return;
                    } else if (type === 'checkbox') {
                        if ($input.is(':checked')) {
                            formData.append(name, $input.val());
                        }
                    } else if (type === 'radio') {
                        if ($input.is(':checked')) {
                            formData.append(name, $input.val());
                        }
                    } else if (name && $input.val()) {
                        formData.append(name, $input.val());
                    }
                });
                
                // Add file as base64 string
                formData.append('avatar_base64', e.target.result);
                formData.append('avatar_name', selectedFile.name);
                formData.append('avatar_type', selectedFile.type);
                formData.append('avatar_size', selectedFile.size);
                formData.append('is_mobile_upload', '1');
                
                console.log('=== MOBILE FORM DATA ===');
                for (let pair of formData.entries()) {
                    if (pair[0] === 'avatar_base64') {
                        console.log(pair[0] + ': BASE64 DATA (length: ' + pair[1].length + ')');
                    } else {
                        console.log(pair[0] + ':', pair[1]);
                    }
                }
                
                submitFormData($form, formData, $submitBtn, originalText);
            };
            
            reader.onerror = function() {
                console.error('Failed to convert file to base64');
                alert('Failed to process file. Please try again.');
                $submitBtn.prop('disabled', false).html(originalText);
            };
            
            reader.readAsDataURL(selectedFile);
            
        } else {
            console.log('=== DESKTOP UPLOAD - USING NORMAL FORM DATA ===');
            
            // Create FormData normally for desktop, but handle skills specially
            const formData = new FormData($form[0]);
            
            // Fix skills array for desktop too
            const skillsInput = $form.find('input[name="skills"]');
            if (skillsInput.length && skillsInput.val()) {
                // Remove the JSON string version
                formData.delete('skills');
                
                // Add skills as proper array
                try {
                    const skillsArray = JSON.parse(skillsInput.val());
                    skillsArray.forEach((skill, index) => {
                        formData.append(`skills[${index}]`, skill);
                    });
                } catch (e) {
                    console.error('Error parsing skills JSON:', e);
                }
            }
            
            console.log('=== DESKTOP FORM DATA ===');
            for (let pair of formData.entries()) {
                if (pair[1] instanceof File) {
                    console.log(pair[0] + ': FILE -', {
                        name: pair[1].name,
                        size: pair[1].size,
                        type: pair[1].type
                    });
                } else {
                    console.log(pair[0] + ':', pair[1]);
                }
            }
            
            submitFormData($form, formData, $submitBtn, originalText);
        }
    });
    
    function submitFormData($form, formData, $submitBtn, originalText) {
        // Submit via jQuery AJAX
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response, textStatus, xhr) {
                console.log('=== AJAX SUCCESS ===');
                console.log('Status:', xhr.status);
                console.log('Response type:', typeof response);
                
                // Show success animation
                $submitBtn.html(`
                    <svg class="inline w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Success!
                `).removeClass('bg-primary-500').addClass('bg-green-500');
                
                // Show success message
                const $successMessage = $(`
                    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg p-6 mx-4 max-w-sm w-full text-center transform animate-bounce">
                            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Step Completed!</h3>
                            <p class="text-gray-600">Moving to next step...</p>
                        </div>
                    </div>
                `);
                
                $('body').append($successMessage);
                
                // Wait 1.5 seconds then redirect
                setTimeout(function() {
                    console.log('Success - redirecting after animation');
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('=== AJAX ERROR ===');
                console.log('Status:', xhr.status);
                console.log('Status Text:', xhr.statusText);
                console.log('Text Status:', textStatus);
                console.log('Error Thrown:', errorThrown);
                console.log('Response Text:', xhr.responseText.substring(0, 200));
                
                alert('Submission failed. Please try again.');
            },
            complete: function(xhr, textStatus) {
                console.log('=== AJAX COMPLETE ===');
                console.log('Final Status:', textStatus);
                
                // Reset button
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }
    
    // ============================================
    // BIO CHARACTER COUNTER
    // ============================================
    $('textarea[name="bio"]').on('input', function() {
        const maxLength = $(this).attr('maxlength') || 150;
        const remaining = maxLength - $(this).val().length;
        const $counter = $(this).next();
        
        if ($counter.length) {
            $counter.text(remaining + ' characters remaining');
            $counter.toggleClass('text-orange-500', remaining < 20);
        }
    });
    
    // ============================================
    // MOBILE ENHANCEMENTS
    // ============================================
    $('input, select, textarea').on('focus', function() {
        $(this).css('font-size', '16px'); // Prevent iOS zoom
    });
    
    console.log('=== CLEAN ONBOARDING SCRIPT READY ===');
});
    </script>
</body>
</html>