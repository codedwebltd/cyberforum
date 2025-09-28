@extends('inc.home.app')
@section('title', 'Wallet - ' . config('app.name'))
@section('content')

<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Page Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">My Wallet</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage your earnings and transactions</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-700 dark:text-gray-300 text-sm font-medium">
                        {{ strtoupper($wallet->currency) }}
                    </span>
                    <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Cards -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 overflow-hidden">
            <!-- Balance Info -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Balance -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Available Balance</h3>
                            <i data-lucide="wallet" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($wallet->balance_in_dollars, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ number_format($wallet->balance) }} points
                        </div>
                    </div>
                    
                    <!-- Pending Balance -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Pending</h3>
                            <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($wallet->pending_balance_in_dollars, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Processing...
                        </div>
                    </div>
                    
                    <!-- Total Earned -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg p-6 border border-gray-100 dark:border-gray-600">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Earned</h3>
                            <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($wallet->total_earned_in_dollars, 2) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            All time
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-wrap gap-3 mt-6">
                    <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add Funds
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Withdraw
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        View History
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Transactions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h2>
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</button>
                    </div>
                    
                    <!-- Placeholder for when no transactions -->
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="receipt" class="w-8 h-8 text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No transactions yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Your transaction history will appear here once you start earning or spending.</p>
                        <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Start Earning
                        </button>
                    </div>
                </div>

                <!-- Earning Opportunities -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Earning Opportunities</h2>
                        <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">See More</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Daily Tasks -->
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="target" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Daily Tasks</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Complete daily activities</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-blue-600">+$0.50</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">per task</p>
                            </div>
                        </div>

                        <!-- Community Posts -->
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-700">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Community Posts</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Share and engage</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-green-600">+$0.25</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">per post</p>
                            </div>
                        </div>

                        <!-- Referrals -->
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl border border-purple-200 dark:border-purple-700">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="users" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Referrals</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Invite friends</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-purple-600">+$5.00</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">per friend</p>
                            </div>
                        </div>

                        <!-- Surveys -->
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border border-orange-200 dark:border-orange-700">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center">
                                    <i data-lucide="clipboard-list" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Surveys</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Share your opinion</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-orange-600">+$2.00</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">per survey</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Chart Placeholder -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Earnings Overview</h2>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-8 text-center">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="bar-chart-3" class="w-8 h-8 text-gray-400"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">Earnings chart will be displayed here</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Start earning to see your progress</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Wallet Status -->
                {{-- <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Wallet Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Status</span>
                            <span class="px-2 py-1 text-xs font-medium {{ $wallet->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }} rounded-full">
                                {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Currency</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ strtoupper($wallet->currency) }}</span>
                        </div>
                        
                        @if($wallet->last_transaction_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Last Activity</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $wallet->last_transaction_at->diffForHumans() }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Total Spent</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($wallet->total_spent_in_dollars, 2) }}</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="text-xl font-bold text-blue-600">{{ number_format(($wallet->total_earned - $wallet->total_spent) / 100, 2) }}%</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Savings Rate</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="text-xl font-bold text-green-600">${{ number_format(($wallet->total_earned / max(1, $wallet->created_at->diffInMonths(now()))) / 100, 2) }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Avg Monthly</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div class="text-xl font-bold text-purple-600">{{ $wallet->created_at->diffInDays(now()) }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400">Days Active</div>
                        </div>
                    </div>
                </div>

                <!-- Security & Settings -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-check" class="w-4 h-4 text-green-600"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Wallet Encrypted</span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <i data-lucide="lock" class="w-4 h-4 text-green-600"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">2FA Protected</span>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <i data-lucide="eye" class="w-4 h-4 text-blue-600"></i>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Activity Monitoring</span>
                        </div>
                    </div>
                    
                    <button class="w-full mt-4 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        Security Settings
                    </button>
                </div>

                <!-- Support -->
                {{-- <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Need Help?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Have questions about your wallet or transactions?
                    </p>
                    <div class="space-y-2">
                        <button class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Contact Support
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            View FAQ
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</main>

<style>
/* Wallet specific styles */
.wallet-card {
    transition: all 0.2s ease-in-out;
}

.wallet-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Balance cards animation */
.balance-card {
    animation: slideInUp 0.6s ease-out;
}

.balance-card:nth-child(2) {
    animation-delay: 0.1s;
}

.balance-card:nth-child(3) {
    animation-delay: 0.2s;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Earning opportunity cards hover effect */
.earning-card {
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.earning-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .balance-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    // Add click handlers for earning opportunities
    document.querySelectorAll('.earning-card').forEach(card => {
        card.addEventListener('click', function() {
            const cardType = this.querySelector('h4').textContent;
            // Handle navigation or modal opening
            console.log(`Clicked on ${cardType}`);
        });
    });

    // Add click handlers for quick action buttons
    document.querySelectorAll('button').forEach(button => {
        button.addEventListener('click', function(e) {
            const buttonText = this.textContent.trim();
            
            switch(buttonText) {
                case 'Add Funds':
                    // Handle add funds
                    console.log('Add funds clicked');
                    break;
                case 'Withdraw':
                    // Handle withdraw
                    console.log('Withdraw clicked');
                    break;
                case 'View History':
                    // Handle view history
                    window.location.href = '/';
                    break;
                default:
                    // Handle other buttons
                    break;
            }
        });
    });

    // Auto-refresh wallet data every 30 seconds
    setInterval(function() {
        // Only refresh if user is active (optional)
        if (document.visibilityState === 'visible') {
            // You could make an AJAX call here to refresh wallet data
            console.log('Auto-refreshing wallet data...');
        }
    }, 30000);
});
</script>

@endsection