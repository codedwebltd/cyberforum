@extends('inc.auth.app')
@section('title',"REGISTER")
@section('content')

    <!-- Dark Mode Toggle -->
    <button id="theme-toggle" class="fixed z-50 p-3 transition-all duration-300 bg-white border border-gray-200 rounded-full shadow-lg top-4 right-4 dark:bg-gray-800 dark:border-gray-700 hover:shadow-xl">
        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2L13.09 8.26L20 9L14 14.74L15.18 21.02L10 17.77L4.82 21.02L6 14.74L0 9L6.91 8.26L10 2Z"></path>
        </svg>
    </button>

    <div class="flex min-h-screen">
        <!-- Left Side - Branding -->
        <div class="relative hidden overflow-hidden lg:flex lg:w-2/5 bg-gradient-to-br from-primary-600 via-primary-500 to-primary-700">
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="relative z-10 flex flex-col items-center justify-center p-8 text-white">
                <div class="max-w-md space-y-6 text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto bg-white/20 rounded-2xl backdrop-blur-sm">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold font-poppins">Join CyberForum</h1>
                    <p class="text-xl text-primary-100">Connect. Share. Grow.</p>
                    <div class="space-y-4 text-primary-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Connect with like-minded people</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Share ideas and get feedback</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Grow your network professionally</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute w-32 h-32 rounded-full top-10 left-10 bg-white/10 animate-pulse-glow"></div>
            <div class="absolute w-24 h-24 rounded-full bottom-10 right-10 bg-white/5 animate-pulse-glow" style="animation-delay: 1s;"></div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="flex items-center justify-center flex-1 p-6 lg:p-8">
            <div class="w-full max-w-md space-y-6 animate-fade-in">
                <!-- Mobile Logo -->
                <div class="text-center lg:hidden">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-primary-500 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white font-poppins">Create Account</h2>
                    <p class="text-gray-600 dark:text-gray-400">Join the community today</p>
                </div>

                <!-- Desktop Header -->
                <div class="hidden text-center lg:block">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white font-poppins">Create Account</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Join CyberForum and start connecting</p>
                </div>

               <!-- Referral Alert (shown when referral code is present) -->
<div id="referral-alert" class="hidden p-4 border rounded-lg bg-primary-50 dark:bg-primary-900/20 border-primary-200 dark:border-primary-800">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <p class="text-sm font-medium text-primary-800 dark:text-primary-200">You've been invited by <span id="referrer-name" class="font-semibold"></span></p>
            <p class="text-xs text-primary-600 dark:text-primary-300">Complete registration to join their network</p>
        </div>
    </div>
</div>

<!-- Registration Form -->
@include('session-message.session-message')
<form class="space-y-5" id="registerForm" action="/register" method="POST">
    @csrf
    <!-- Basic Info -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="firstName" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
            <input id="firstName" name="first_name" type="text" required
                   class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   placeholder="John">
            <span class="hidden text-sm text-red-500" id="firstName-error">First name is required</span>
        </div>

        <div>
            <label for="lastName" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
            <input id="lastName" name="last_name" type="text" required
                   class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   placeholder="Doe">
            <span class="hidden text-sm text-red-500" id="lastName-error">Last name is required</span>
        </div>
    </div>

    <div>
        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
        <input id="email" name="email" type="email" required
               class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
               placeholder="john@example.com">
        <span class="hidden text-sm text-red-500" id="email-error">Please enter a valid email address</span>
    </div>

    <div>
        <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
        <div class="relative">
            <input id="password" name="password" type="password" required
                   class="w-full px-4 py-3 pr-12 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                   placeholder="Create a strong password">
            <button type="button" id="toggle-password" class="absolute text-gray-500 transform -translate-y-1/2 right-3 top-1/2 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
        </div>
        <div class="mt-2">
            <div class="flex items-center space-x-2 text-xs">
                <div id="length-check" class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="10"/>
                    </svg>
                    <span class="ml-1 text-gray-500 dark:text-gray-400">8+ characters</span>
                </div>
                <div id="special-check" class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="10"/>
                    </svg>
                    <span class="ml-1 text-gray-500 dark:text-gray-400">Special character</span>
                </div>
            </div>
        </div>
        <span class="hidden text-sm text-red-500" id="password-error">Password must be at least 8 characters with special character</span>
    </div>

    <div>
        <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
        <input id="confirmPassword" name="password_confirmation" type="password"
               class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
               placeholder="Optional">
        <span class="hidden text-sm text-red-500" id="confirmPassword-error">Passwords do not match</span>
    </div>

    <!-- Referral Code (Optional) -->
    <div>
        <label for="referralCode" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            Referral Code
            <span class="font-normal text-gray-500 dark:text-gray-400">(Optional)</span>
        </label>
        <input readonly id="referralCode" name="referral_code" type="text"
               class="w-full px-4 py-3 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
               placeholder="Enter referral code if you have one">
    </div>

    <!-- Terms and Privacy -->
    <div class="flex items-start">
        <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 mt-1 bg-gray-100 border-gray-300 rounded text-primary-600 dark:bg-gray-700 dark:border-gray-600 focus:ring-primary-500 focus:ring-2">
        <label for="terms" class="block ml-3 text-sm text-gray-700 dark:text-gray-300">
            I agree to the
            <a href="#" class="transition-colors text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">Terms of Service</a>
            and
            <a href="#" class="transition-colors text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">Privacy Policy</a>
        </label>
    </div>

    <!-- Register Button -->
    <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-800 transform hover:scale-[1.02] active:scale-[0.98]">
        <span id="register-text">Create Account</span>
        <span id="register-loading" class="hidden">
            <svg class="inline w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating account...
        </span>
    </button>

    <!-- Social Registration -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 text-gray-500 bg-gray-50 dark:bg-gray-900 dark:text-gray-400">Or register with</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-6">
            <button type="button" class="inline-flex justify-center w-full px-4 py-3 text-sm font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span class="ml-2">Google</span>
            </button>

            <button type="button" class="inline-flex justify-center w-full px-4 py-3 text-sm font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
                <span class="ml-2">Twitter</span>
            </button>
        </div>
    </div>
</form>

                <!-- Sign In Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Already have an account?
                        <a href="/login" class="font-medium transition-colors text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Check for saved theme preference or default to 'light'
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
            darkIcon.classList.remove('hidden');
        } else {
            lightIcon.classList.remove('hidden');
        }

        themeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');

            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            } else {
                localStorage.setItem('theme', 'light');
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            }
        });

// Check for referral code in URL and fetch referrer info
const urlParams = new URLSearchParams(window.location.search);
const referralCode = urlParams.get('ref');

if (referralCode) {
    document.getElementById('referralCode').value = referralCode;
    
    // Fetch referrer information from your backend
    fetch(`/referrer-info/${referralCode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.referrer) {
                document.getElementById('referrer-name').textContent = data.referrer.name;
                document.getElementById('referral-alert').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.log('Could not fetch referrer info:', error);
            // Still show the alert without the name
            document.getElementById('referrer-name').textContent = 'a friend';
            document.getElementById('referral-alert').classList.remove('hidden');
        });
}

// Password Toggle
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('toggle-password');

togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});

// Password Strength Checker
const lengthCheck = document.getElementById('length-check');
const specialCheck = document.getElementById('special-check');

passwordInput.addEventListener('input', function() {
    const password = this.value;

    // Length check
    if (password.length >= 8) {
        lengthCheck.querySelector('svg').classList.remove('text-gray-400');
        lengthCheck.querySelector('svg').classList.add('text-green-500');
        lengthCheck.querySelector('span').classList.remove('text-gray-500', 'dark:text-gray-400');
        lengthCheck.querySelector('span').classList.add('text-green-600', 'dark:text-green-400');
    } else {
        lengthCheck.querySelector('svg').classList.remove('text-green-500');
        lengthCheck.querySelector('svg').classList.add('text-gray-400');
        lengthCheck.querySelector('span').classList.remove('text-green-600', 'dark:text-green-400');
        lengthCheck.querySelector('span').classList.add('text-gray-500', 'dark:text-gray-400');
    }

    // Special character check
    const specialChars = /[!@#$%^&*(),.?":{}|<>]/;
    if (specialChars.test(password)) {
        specialCheck.querySelector('svg').classList.remove('text-gray-400');
        specialCheck.querySelector('svg').classList.add('text-green-500');
        specialCheck.querySelector('span').classList.remove('text-gray-500', 'dark:text-gray-400');
        specialCheck.querySelector('span').classList.add('text-green-600', 'dark:text-green-400');
    } else {
        specialCheck.querySelector('svg').classList.remove('text-green-500');
        specialCheck.querySelector('svg').classList.add('text-gray-400');
        specialCheck.querySelector('span').classList.remove('text-green-600', 'dark:text-green-400');
        specialCheck.querySelector('span').classList.add('text-gray-500', 'dark:text-gray-400');
    }
});

// Form Validation
const registerForm = document.getElementById('registerForm');

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    const minLength = password.length >= 8;
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    return minLength && hasSpecial;
}

function showError(element, errorElement) {
    element.classList.add('border-red-500', 'focus:ring-red-500');
    element.classList.remove('border-gray-300', 'focus:ring-primary-500');
    errorElement.classList.remove('hidden');
}

function hideError(element, errorElement) {
    element.classList.remove('border-red-500', 'focus:ring-red-500');
    element.classList.add('border-gray-300', 'focus:ring-primary-500');
    errorElement.classList.add('hidden');
}

// Real-time validation
document.getElementById('firstName').addEventListener('input', function() {
    if (this.value.trim().length > 0) {
        hideError(this, document.getElementById('firstName-error'));
    }
});

document.getElementById('lastName').addEventListener('input', function() {
    if (this.value.trim().length > 0) {
        hideError(this, document.getElementById('lastName-error'));
    }
});

document.getElementById('email').addEventListener('input', function() {
    if (validateEmail(this.value)) {
        hideError(this, document.getElementById('email-error'));
    }
});

document.getElementById('confirmPassword').addEventListener('input', function() {
    /*if (this.value === passwordInput.value) {
        hideError(this, document.getElementById('confirmPassword-error'));
    }*/
});

registerForm.addEventListener('submit', function(e) {
    e.preventDefault();

    let isValid = true;
    const formData = new FormData(this);

    // Validate all fields
    const firstName = formData.get('first_name').trim();
    const lastName = formData.get('last_name').trim();
    const email = formData.get('email');
    const password = formData.get('password');
    const confirmPassword = formData.get('password_confirmation');
    const terms = formData.get('terms');

    if (!firstName) {
        showError(document.getElementById('firstName'), document.getElementById('firstName-error'));
        isValid = false;
    }

    if (!lastName) {
        showError(document.getElementById('lastName'), document.getElementById('lastName-error'));
        isValid = false;
    }

    if (!validateEmail(email)) {
        showError(document.getElementById('email'), document.getElementById('email-error'));
        isValid = false;
    }

    if (!validatePassword(password)) {
        showError(document.getElementById('password'), document.getElementById('password-error'));
        isValid = false;
    }

    /*if (password !== confirmPassword) {
        showError(document.getElementById('confirmPassword'), document.getElementById('confirmPassword-error'));
        isValid = false;
    }*/

    if (!terms) {
        alert('Please accept the Terms of Service and Privacy Policy');
        isValid = false;
    }

    if (isValid) {
        // Show loading state
        document.getElementById('register-text').classList.add('hidden');
        document.getElementById('register-loading').classList.remove('hidden');

        // Submit the form
        this.submit();
    }
});

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.animate-fade-in');
    elements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
    });
});
    </script>
@endsection

