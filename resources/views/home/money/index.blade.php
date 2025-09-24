@extends('inc.home.app')
@section('title', 'Wallet - ' . config('app.name'))
@section('content')

<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Wallet</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage your earnings and transactions</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Balance Cards -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Balance -->
                <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl p-8 text-white">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-medium opacity-90">Available Balance</h2>
                            <p class="text-4xl font-bold">${{ number_format($wallet->balance / 100, 2) }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                            <i data-lucide="dollar-sign" class="w-8 h-8"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm opacity-75">{{ $wallet->currency }}</span>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                                Withdraw
                            </button>
                            <button class="px-4 py-2 bg-white text-green-600 hover:bg-white/90 rounded-lg text-sm font-medium transition-colors">
                                Add Funds
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Pending</h3>
                        <p class="text-2xl font-bold text-orange-600">${{ number_format($wallet->pending_balance / 100, 2) }}</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Total Earned</h3>
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($wallet->total_earned / 100, 2) }}</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="trending-down" class="w-6 h-6 text-red-600"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Total Spent</h3>
                        <p class="text-2xl font-bold text-red-600">${{ number_format($wallet->total_spent / 100, 2) }}</p>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h2>
                            <a href="{{ route('money.transactions') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <i data-lucide="receipt" class="w-8 h-8 text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No transactions yet</h3>
                            <p class="text-gray-600 dark:text-gray-400">Your transaction history will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Wallet Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Wallet Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status</span>
                            <span class="px-2 py-1 text-xs font-medium {{ $wallet->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }} rounded-full">
                                {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Currency</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $wallet->currency }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Created</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $wallet->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($wallet->last_transaction_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Last Transaction</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $wallet->last_transaction_at->diffForHumans() }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <button class="w-full p-3 text-left bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="download" class="w-4 h-4 text-blue-600"></i>
                                <span class="text-sm font-medium text-blue-600">Withdraw Funds</span>
                            </div>
                        </button>
                        <button class="w-full p-3 text-left bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="plus" class="w-4 h-4 text-green-600"></i>
                                <span class="text-sm font-medium text-green-600">Add Funds</span>
                            </div>
                        </button>
                        <button class="w-full p-3 text-left bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="file-text" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Transaction History</span>
                            </div>
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
});
</script>

@endsection