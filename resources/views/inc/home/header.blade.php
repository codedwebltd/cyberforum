<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Community Forum - Modern Social Platform')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Join our vibrant community forum with trending discussions, events, marketplace, and professional networking features.">
    <meta name="keywords" content="forum, community, social, networking, discussions, events, marketplace">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        card: 'hsl(var(--card))',
                        'card-foreground': 'hsl(var(--card-foreground))',
                        primary: 'hsl(var(--primary))',
                        'primary-foreground': 'hsl(var(--primary-foreground))',
                        secondary: 'hsl(var(--secondary))',
                        'secondary-foreground': 'hsl(var(--secondary-foreground))',
                        muted: 'hsl(var(--muted))',
                        'muted-foreground': 'hsl(var(--muted-foreground))',
                        accent: 'hsl(var(--accent))',
                        'accent-foreground': 'hsl(var(--accent-foreground))',
                        border: 'hsl(var(--border))',
                        electric: 'hsl(var(--electric))',
                        cyber: 'hsl(var(--cyber))',
                        'neon-green': 'hsl(var(--neon-green))',
                        'neon-pink': 'hsl(var(--neon-pink))'
                    },
                    backgroundImage: {
                        'gradient-primary': 'var(--gradient-primary)',
                        'gradient-card': 'var(--gradient-card)'
                    },
                    boxShadow: {
                        'glow': 'var(--shadow-glow)',
                        'card': 'var(--shadow-card)'
                    }
                }
            }
        }
    </script>

    <style>

/* Add to your existing styles 
body {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-touch-callout: none;
    -webkit-tap-highlight-color: transparent;
}
*/

/* Disable image dragging 
img {
    -webkit-user-drag: none;
    -khtml-user-drag: none;
    -moz-user-drag: none;
    -o-user-drag: none;
    user-drag: none;
    pointer-events: none;
}
*/
        :root {
            --background: 222 47% 11%;
            --foreground: 210 40% 98%;
            --card: 222 84% 5%;
            --card-foreground: 210 40% 98%;
            --primary: 217 91% 60%;
            --primary-foreground: 222 47% 11%;
            --secondary: 217 33% 17%;
            --secondary-foreground: 210 40% 98%;
            --muted: 217 33% 17%;
            --muted-foreground: 215 20% 65%;
            --accent: 142 76% 36%;
            --accent-foreground: 210 40% 98%;
            --border: 217 33% 17%;
            --electric: 217 91% 60%;
            --cyber: 142 76% 36%;
            --neon-green: 142 76% 36%;
            --neon-pink: 330 81% 60%;
            --gradient-primary: linear-gradient(135deg, hsl(217 91% 60%), hsl(142 76% 36%));
            --gradient-card: linear-gradient(145deg, hsl(222 84% 5%), hsl(222 84% 4%));
            --shadow-glow: 0 0 20px hsl(217 91% 60% / 0.25);
            --shadow-card: 0 4px 20px hsl(222 84% 4% / 0.5);
        }

        .light {
            --background: 0 0% 98%;
            --foreground: 222 47% 11%;
            --card: 0 0% 100%;
            --card-foreground: 222 47% 11%;
            --primary: 217 91% 60%;
            --primary-foreground: 0 0% 98%;
            --secondary: 210 40% 96%;
            --secondary-foreground: 222 47% 11%;
            --muted: 210 40% 96%;
            --muted-foreground: 215 16% 47%;
            --accent: 142 76% 36%;
            --accent-foreground: 0 0% 98%;
            --border: 214 32% 91%;
            --gradient-card: linear-gradient(145deg, hsl(0 0% 100%), hsl(210 40% 98%));
            --shadow-card: 0 2px 10px hsl(222 47% 11% / 0.1);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .font-display {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }

        .forum-card {
            background: var(--gradient-card);
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid hsl(var(--border));
        }

        .forum-card:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-1px);
            border-color: hsl(var(--primary) / 0.3);
        }

        .gradient-text {
            background: var(--gradient-primary);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .truncate-lines-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .truncate-lines-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .sidebar-toggle {
            transition: transform 0.3s ease;
        }

        .sidebar-open {
            transform: translateX(0);
        }

        .sidebar-closed {
            transform: translateX(-100%);
        }

        @media (min-width: 1024px) {
            .sidebar-closed {
                transform: translateX(0);
            }
        }

        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .mobile-menu {
            transition: all 0.3s ease;
        }

        .dropdown-menu {
            transition: all 0.2s ease;
            transform: scale(0.95) translateY(-10px);
            opacity: 0;
            pointer-events: none;
        }

        .dropdown-menu.active {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* Live Search Styles */
        .search-results {
            max-height: 300px;
            overflow-y: auto;
        }

        .search-result-item:hover {
            background: hsl(var(--muted));
        }

        /* Mobile Search Toggle */
        .mobile-search {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }

        .mobile-search.active {
            max-height: 100px;
        }

        /* Hashtag Search */
        .hashtag-search {
            transition: all 0.2s ease;
        }


        /*BANNER ANIMATION*/
        @keyframes typeWriter {
    0% { width: 0; }
    90% { width: 100%; }
    100% { width: 0; }
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

@keyframes wiggle {
    0%, 100% { transform: rotate(-3deg); }
    50% { transform: rotate(3deg); }
}

@keyframes boldPulse {
    0% { font-weight: 400; }
    50% { font-weight: 700; }
    100% { font-weight: 400; }
}

.typing-animation {
    overflow: hidden;
    white-space: nowrap;
    border-right: 2px solid white;
    animation: typeWriter 3s steps(15, end) infinite, blink-caret 0.75s step-end infinite;
}

@keyframes blink-caret {
    from, to { border-color: transparent; }
    50% { border-color: white; }
}

.dance-animation {
    animation: wiggle 0.5s ease-in-out 3;
}

.bold-animation {
    animation: boldPulse 2s ease-in-out infinite;
}
    </style>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>

<body class="min-h-screen bg-background text-foreground">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 z-50 h-full overflow-y-auto border-r w-80 sidebar-toggle sidebar-closed lg:sidebar-open bg-card border-border hide-scrollbar">
        <div class="p-6">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-primary">
                        <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold font-display gradient-text">{{ env('APP_NAME') }}</h2>
                        <p class="text-xs text-muted-foreground">Professional Network</p>
                    </div>
                </div>
                <button id="sidebar-close" class="p-2 transition-colors rounded-lg lg:hidden hover:bg-muted">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- User Profile Card - Enhanced -->
            <div class="p-4 mb-6 forum-card rounded-xl">
                <div class="flex items-center mb-4 space-x-3">
                   <x-user-avatar size="w-12 h-12" />
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold truncate">{{ auth()->check() ? ucfirst(auth()->user()->name) : 'Guest' }} </h3>
                        <p class="text-sm truncate text-muted-foreground">{{ $profession }}</p>
                        <p class="text-xs font-medium text-primary">{{ Illuminate\Support\Number::abbreviate($total_points,0,2000) }} pts</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 rounded-lg bg-muted">
                        <div class="text-lg font-bold text-primary">{{ Illuminate\Support\Number::abbreviate($total_points,0,2000) }} </div>
                        <div class="text-xs text-muted-foreground">Points</div>
                    </div>
                    <div class="p-3 rounded-lg bg-muted">
                        <div class="text-lg font-bold text-accent">{{ auth()->check() ? auth()->user()->posts_count : 0 }}</div>
                        <div class="text-xs text-muted-foreground">Posts</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mb-6 space-y-2">
                <a href="/" class="flex items-center px-4 py-3 space-x-3 rounded-lg bg-primary text-primary-foreground">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    <span class="font-medium">Home</span>
                </a>
                <a href="{{route('discussion.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    <span>Discussions</span>
                </a>
                <a href="{{ route('media.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="image" class="w-5 h-5"></i>
                    <span>Media</span>
                </a>
                <a href="{{ route('security.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="shield" class="w-5 h-5"></i>
                    <span>Security</span>
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    <span>Events</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Marketplace</span>
                </a>
                <a href="{{ route('members.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Members</span>
                </a>

                <a href="{{ route('members.messages') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <div class="relative">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
                    </div>
                    <span>Messages</span>
                </a>

                <a href="{{ route('money.index') }}" class="flex items-center px-4 py-3 space-x-3 transition-colors rounded-lg hover:bg-muted">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    <span>Wallet</span>
                    <span class="px-2 py-1 ml-auto text-xs rounded-full bg-accent text-accent-foreground">${{ auth()->check() ? number_format(auth()->user()->wallet->balance,2) : '0.0' }}</span>
                </a>

            </nav>

            <!-- Quick Actions -->
            <div class="space-y-3">
              @auth
    <a href="{{ route('discussion.create') }}" class="flex items-center justify-center w-full px-4 py-3 space-x-2 transition-colors rounded-lg bg-primary text-primary-foreground hover:bg-primary/90">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span class="font-medium">New Post</span>
    </a>
@else
    <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 space-x-2 transition-colors rounded-lg bg-primary text-primary-foreground hover:bg-primary/90">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span class="font-medium">Login to Post</span>
    </a>
@endauth

@auth
    <button class="flex items-center justify-center w-full px-4 py-3 space-x-2 transition-colors rounded-lg bg-accent text-accent-foreground hover:bg-accent/90">
        <i data-lucide="heart" class="w-4 h-4"></i>
        <span class="font-medium">Donate</span>
    </button>
@else
    <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 space-x-2 transition-colors rounded-lg bg-accent text-accent-foreground hover:bg-accent/90">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span class="font-medium">Login to Donate</span>
    </a>
@endauth
            </div>
        </div>
    </aside>

    <!-- Main Layout -->
    <div class="min-h-screen lg:ml-80">
        <!-- Header -->
        <header class="sticky top-0 z-30 w-full border-b border-border backdrop-blur-lg bg-background/95">
            <div class="px-4 lg:px-6">
                <div class="flex items-center justify-between h-16">
                    <!-- Mobile Menu & Search -->
                    <div class="flex items-center flex-1 space-x-4">
                        <button id="sidebar-toggle" class="p-2 transition-colors rounded-lg hover:bg-muted">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>

                        <!-- Mobile Search Toggle -->
                        {{-- <button id="mobile-search-toggle"
                            class="p-2 transition-colors rounded-lg sm:hidden hover:bg-muted">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button> --}}

                        <!-- Desktop Search Bar -->
                        <div class="relative flex-1 hidden max-w-md sm:flex">
                            <i data-lucide="search"
                                class="absolute w-4 h-4 transform -translate-y-1/2 left-3 top-1/2 text-muted-foreground"></i>
                            <input type="text" id="desktop-search" placeholder="Search discussions, members, events..."
                                class="w-full py-2 pl-10 pr-4 text-sm border rounded-lg bg-muted border-border focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-transparent">
                            <!-- Desktop Search Results -->
                            <div id="desktop-search-results"
                                class="absolute left-0 right-0 hidden mt-2 overflow-y-auto border rounded-lg shadow-lg top-full bg-card border-border max-h-80">
                                <div class="p-3">
                                    <div class="mb-2 text-sm text-muted-foreground">Recent searches</div>
                                    <div class="space-y-2">
                                        <div class="p-2 rounded cursor-pointer search-result-item">
                                            <div class="flex items-center space-x-2">
                                                <i data-lucide="clock" class="w-4 h-4 text-muted-foreground"></i>
                                                <span class="text-sm">React hooks tutorial</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Header Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Online Status -->
                        <div class="hidden md:flex items-center space-x-2 px-3 py-1.5 bg-muted rounded-full text-sm">
                            <div class="w-2 h-2 rounded-full bg-neon-green animate-pulse-slow"></div>
                            <span class="font-medium">1.2k online</span>
                        </div>

                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="p-2 transition-colors rounded-lg hover:bg-muted">
                            <i data-lucide="sun" class="hidden w-5 h-5"></i>
                            <i data-lucide="moon" class="block w-5 h-5"></i>
                        </button>

                       <!-- Notifications -->
                        <a href="{{ route('notifications.index') }}" class="relative p-2 transition-colors rounded-lg hover:bg-muted">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            @if($unread_notification_count > 0)
                            <span class="absolute flex items-center justify-center w-5 h-5 text-xs font-medium text-white rounded-full -top-1 -right-1 bg-red-500">
                                {{ $unread_notification_count > 99 ? '99+' : $unread_notification_count }}
                            </span>
                            @endif
                        </a>

                        <!-- Profile Dropdown codedweb-->
                        <div class="relative">
                           <button id="profile-dropdown-toggle"
                                    class="flex items-center p-1 space-x-2 transition-colors rounded-lg hover:bg-muted">
                                <x-user-avatar />
                            </button>

                            <!-- Profile Dropdown Menu -->
                            <div id="profile-dropdown"
                                class="absolute right-0 w-64 mt-2 border rounded-lg shadow-lg dropdown-menu top-full bg-card border-border">
                                <div class="p-4 border-b border-border">
                                    <div class="flex items-center space-x-3">
                                        <x-user-avatar size="w-12 h-12" />
                                        <div>
                                            <h3 class="font-semibold">{{ auth()->check() ? ucfirst(auth()->user()->name) : 'Guest' }}</h3>
                                            <p class="text-sm text-muted-foreground">{{ $profession }}</p>
                                            <p class="text-xs font-medium text-primary">{{ Illuminate\Support\Number::abbreviate($total_points,0,2000) }} points</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2">
                                    <div class="flex items-center justify-between p-3 mb-2 rounded-lg bg-muted/50">
                                        <div class="flex items-center space-x-2">
                                            <i data-lucide="wallet" class="w-4 h-4 text-accent"></i>
                                            <span class="text-sm font-medium">Wallet Balance</span>
                                        </div>
                                        <span class="text-sm font-bold text-accent">${{ auth()->check() ? number_format(auth()->user()->wallet->balance_in_dollars,2) : 0.00  }}</span>
                                    </div>

                                    <a href="{{ route('profile.show') }}"
                                        class="flex items-center px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-muted">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        <span class="text-sm">My Profile</span>
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-muted">
                                        <i data-lucide="settings" class="w-4 h-4"></i>
                                        <span class="text-sm">Settings</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-muted">
                                        <i data-lucide="help-circle" class="w-4 h-4"></i>
                                        <span class="text-sm">Help & Support</span>
                                    </a>
                                    <div class="pt-2 mt-2 border-t border-border">
                                    @auth
                                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-3 py-2 space-x-3 text-red-500 transition-colors rounded-lg hover:bg-muted">
                                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                                <span class="text-sm">Sign Out</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="flex items-center px-3 py-2 space-x-3 text-blue-500 transition-colors rounded-lg hover:bg-muted">
                                            <i data-lucide="log-in" class="w-4 h-4"></i>
                                            <span class="text-sm">Sign In</span>
                                        </a>
                                    @endauth
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </header>
