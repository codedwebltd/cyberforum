
{{--inc.home.footer.blade.php --}}
<script>
// Theme Toggle
const themeToggle = document.getElementById('theme-toggle');
const html = document.documentElement;

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.remove('light');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
}

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'dark';
html.classList.remove('dark', 'light');
html.classList.add(savedTheme);

// Sidebar Toggle
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebarClose = document.getElementById('sidebar-close');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    if (sidebar && sidebarOverlay) {
        sidebar.classList.remove('sidebar-closed');
        sidebar.classList.add('sidebar-open');
        sidebarOverlay.classList.remove('hidden');
    }
}

function closeSidebar() {
    if (sidebar && sidebarOverlay) {
        sidebar.classList.remove('sidebar-open');
        sidebar.classList.add('sidebar-closed');
        sidebarOverlay.classList.add('hidden');
    }
}

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', openSidebar);
}

if (sidebarClose) {
    sidebarClose.addEventListener('click', closeSidebar);
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
}

// Profile Dropdown
const profileDropdownToggle = document.getElementById('profile-dropdown-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

if (profileDropdownToggle && profileDropdown) {
    profileDropdownToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
    });

    // Close dropdown when clicking outside (but avoid conflicts with modals)
    document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target) && !profileDropdownToggle.contains(e.target)) {
            // Only close if we're not clicking inside a modal
            if (!e.target.closest('#comments-modal') && !e.target.closest('#announcement-modal')) {
                profileDropdown.classList.remove('active');
            }
        }
    });
}

// Live Search Functionality
const desktopSearchInput = document.getElementById('desktop-search');
const desktopSearchResults = document.getElementById('desktop-search-results');

function performSearch(query, resultsContainer) {
    if (query.length > 0 && resultsContainer) {
        resultsContainer.classList.remove('hidden');
        // Here you would typically make an API call to search
        // For now, showing static results
    } else if (resultsContainer) {
        resultsContainer.classList.add('hidden');
    }
}

if (desktopSearchInput && desktopSearchResults) {
    desktopSearchInput.addEventListener('input', (e) => {
        performSearch(e.target.value, desktopSearchResults);
    });

    // Hide search results when clicking outside (but avoid conflicts)
    document.addEventListener('click', (e) => {
        if (!desktopSearchInput.contains(e.target) && !desktopSearchResults.contains(e.target)) {
            // Only close if we're not clicking inside a modal
            if (!e.target.closest('#comments-modal') && !e.target.closest('#announcement-modal')) {
                desktopSearchResults.classList.add('hidden');
            }
        }
    });
}

// Heartbeat functionality
setInterval(() => {
    if (document.hasFocus() && !document.hidden) {
        fetch('/home/heartbeat', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).catch(error => {
            // Silent fail for heartbeat
        });
    }
}, 100000); // 10 seconds

</script>

<!-- Simple footer HTML -->
<footer class="mt-auto py-6 border-t border-border bg-card">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-sm text-muted-foreground">
                &copy; {{ date('Y') }} {{ config('app.name', 'Community Platform') }}. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<!-- Fallback for no JavaScript -->
<noscript>
    <meta http-equiv="refresh" content="0; url=/no-javascript">
</noscript>

</body>
</html>





















{{-- inc.home.footer.blade.php
<script>
// Theme Toggle
const themeToggle = document.getElementById('theme-toggle');
const html = document.documentElement;

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.remove('light');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
}

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'dark';
html.classList.remove('dark', 'light');
html.classList.add(savedTheme);

// Sidebar Toggle
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebarClose = document.getElementById('sidebar-close');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    if (sidebar && sidebarOverlay) {
        sidebar.classList.remove('sidebar-closed');
        sidebar.classList.add('sidebar-open');
        sidebarOverlay.classList.remove('hidden');
    }
}

function closeSidebar() {
    if (sidebar && sidebarOverlay) {
        sidebar.classList.remove('sidebar-open');
        sidebar.classList.add('sidebar-closed');
        sidebarOverlay.classList.add('hidden');
    }
}

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', openSidebar);
}

if (sidebarClose) {
    sidebarClose.addEventListener('click', closeSidebar);
}

if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
}

// Profile Dropdown
const profileDropdownToggle = document.getElementById('profile-dropdown-toggle');
const profileDropdown = document.getElementById('profile-dropdown');

if (profileDropdownToggle && profileDropdown) {
    profileDropdownToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!profileDropdown.contains(e.target) && !profileDropdownToggle.contains(e.target)) {
            profileDropdown.classList.remove('active');
        }
    });
}

// Live Search Functionality
const desktopSearchInput = document.getElementById('desktop-search');
const desktopSearchResults = document.getElementById('desktop-search-results');

function performSearch(query, resultsContainer) {
    if (query.length > 0 && resultsContainer) {
        resultsContainer.classList.remove('hidden');
        // Here you would typically make an API call to search
        // For now, showing static results
    } else if (resultsContainer) {
        resultsContainer.classList.add('hidden');
    }
}

if (desktopSearchInput && desktopSearchResults) {
    desktopSearchInput.addEventListener('input', (e) => {
        performSearch(e.target.value, desktopSearchResults);
    });

    // Hide search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!desktopSearchInput.contains(e.target) && !desktopSearchResults.contains(e.target)) {
            desktopSearchResults.classList.add('hidden');
        }
    });
}

// Initialize Lucide Icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Smooth animations on page load
document.addEventListener('DOMContentLoaded', () => {
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.3s ease';
        document.body.style.opacity = '1';
    }, 100);
});


// Heartbeat functionality
console.log('Heartbeat script loaded');

setInterval(() => {
    console.log('Heartbeat interval triggered');
    
    if (document.hasFocus() && !document.hidden) {
        console.log('Sending heartbeat request...');
        
        fetch('/home/heartbeat', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(response => {
            console.log('Response received:', response.status);
            return response.json();
        }).then(data => {
            console.log('Heartbeat successful:', data);
        }).catch(error => {
            console.log('Heartbeat failed:', error);
        });
    } else {
        console.log('Tab not focused, skipping heartbeat');
    }
}, 100000); // 10 seconds for testing

// console.log('Main layout JavaScript initialized');
</script>


<!-- Fallback for no JavaScript -->
<noscript>
    <meta http-equiv="refresh" content="0; url=/no-javascript">
</noscript>
 <script type="text/JavaScript" src="{{ asset('common/disabled.js') }}"></script>
</body>
</html> --}}