@extends('inc.home.app')
@section('title', 'Security Log - ' . config('app.name'))
@section('content')

<main class="p-4 lg:p-6">
    <div class="mx-auto max-w-6xl">
        @include('session-message.session-message')
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-red-600"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Security Log</h1>
                        <p class="text-gray-600 dark:text-gray-400">Monitor account activity and login attempts</p>
                    </div>
                </div>
                
                <!-- Clear Actions -->
                <div class="flex gap-2">
                    <button onclick="openClearModal()" class="px-3 py-2 text-sm bg-orange-100 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300 rounded-lg hover:bg-orange-200 dark:hover:bg-orange-900/30 transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                        Clear Logs
                    </button>
                </div>
            </div>
        </div>

        <!-- Security Metrics (if available) -->
        @if(isset($metrics))
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 mb-6 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Security Overview (Last 30 Days)</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="text-2xl font-bold {{ $metrics['risk_level'] === 'high' ? 'text-red-600' : ($metrics['risk_level'] === 'medium' ? 'text-orange-600' : 'text-green-600') }}">
                        {{ $metrics['risk_score'] }}/100
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Risk Score</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $metrics['success_rate'] }}%</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Success Rate</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $metrics['failed_logins'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Failed Attempts</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $metrics['unique_ips'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Unique IPs</div>
                </div>
            </div>
            
            <!-- Quick Actions based on risk -->
            @if($metrics['risk_level'] === 'high' || $metrics['failed_logins'] > 5)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                    <span class="font-medium text-red-800 dark:text-red-200">High Security Risk Detected</span>
                </div>
                <p class="text-sm text-red-700 dark:text-red-300 mb-3">Your account shows suspicious activity patterns.</p>
                <form action="{{ route('security.clear.failed') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                        Clear Failed Attempts
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif

        <!-- Traditional Stats (fallback if no metrics) -->
        @if(!isset($metrics))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            @php
            $failedAttempts = collect($locationHistory)->where('action', 'failed_login')->count();
            $totalLogins = collect($locationHistory)->whereIn('action', ['login', 'logout', 'failed_login'])->count();
            $uniqueIPs = collect($locationHistory)->pluck('ip')->unique()->count();
            $recentFailed = collect($locationHistory)->where('action', 'failed_login')->where('timestamp', '>=', now()->subDays(7)->toISOString())->count();
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $failedAttempts }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Failed Attempts</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $totalLogins - $failedAttempts }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Successful Logins</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $uniqueIPs }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Unique IPs</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                <div class="text-2xl font-bold {{ $recentFailed > 5 ? 'text-red-600' : 'text-orange-600' }}">{{ $recentFailed }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">This Week</div>
            </div>
        </div>
        @endif

        <!-- Activity Log -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($locationHistory as $log)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer" onclick="openLogModal({{ json_encode($log) }})">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg {{ isset($log['action']) && $log['action'] === 'failed_login' ? 'bg-red-100 dark:bg-red-900/20' : 'bg-green-100 dark:bg-green-900/20' }} flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ isset($log['action']) && $log['action'] === 'failed_login' ? 'shield-x' : 'shield-check' }}" class="w-5 h-5 {{ isset($log['action']) && $log['action'] === 'failed_login' ? 'text-red-600' : 'text-green-600' }}"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                        {{ isset($log['action']) && $log['action'] === 'failed_login' ? 'Failed Login Attempt' : ucwords(str_replace('_', ' ', $log['action'] ?? 'Account Activity')) }}
                                    </h3>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        @if(isset($log['ip']))
                                        <span>{{ $log['ip'] }}</span>
                                        @endif
                                        
                                        @if(isset($log['location']['device']['platform']))
                                        <span>{{ $log['location']['device']['browser'] ?? '' }} on {{ $log['location']['device']['platform'] ?? '' }}</span>
                                        @elseif(isset($log['device']['platform']))
                                        <span>{{ $log['device']['browser'] ?? '' }} on {{ $log['device']['platform'] ?? '' }}</span>
                                        @endif
                                        
                                        @if(isset($log['location']['city']['name']))
                                        <span>{{ $log['location']['city']['name'] }}, {{ $log['location']['country']['name'] ?? '' }}</span>
                                        @elseif(isset($log['city']['name']))
                                        <span>{{ $log['city']['name'] }}, {{ $log['country']['name'] ?? '' }}</span>
                                        @endif
                                        
                                        @if(isset($log['timestamp']))
                                        <span>{{ \Carbon\Carbon::parse($log['timestamp'])->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(isset($log['action']) && $log['action'] === 'failed_login')
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400 rounded-full">Suspicious</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <i data-lucide="shield" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No activity found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Your security log is empty.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Log Detail Modal -->
    <div id="log-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">Activity Details</h3>
                    <button onclick="closeLogModal()" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                <div id="modal-content" class="space-y-4"></div>
            </div>
        </div>
    </div>

    <!-- Clear Logs Modal -->
    <div id="clear-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Clear Security Logs</h3>
            </div>
            
            <div class="p-6 space-y-4">
                <form action="{{ route('security.clear.failed') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full p-3 text-left rounded-lg border border-orange-200 hover:bg-orange-50 dark:border-orange-800 dark:hover:bg-orange-900/20 transition-colors">
                        <div class="font-medium text-gray-900 dark:text-white">Clear Failed Login Attempts</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Remove only failed login attempts (recommended)</div>
                    </button>
                </form>
                
                <form action="{{ route('security.clear.old') }}" method="POST" class="block">
                    @csrf
                    <input type="hidden" name="days" value="30">
                    <button type="submit" class="w-full p-3 text-left rounded-lg border border-blue-200 hover:bg-blue-50 dark:border-blue-800 dark:hover:bg-blue-900/20 transition-colors">
                        <div class="font-medium text-gray-900 dark:text-white">Clear Logs Older Than 30 Days</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Keep recent activity for security monitoring</div>
                    </button>
                </form>
                
                <form action="{{ route('security.clear.all') }}" method="POST" class="block" onsubmit="return confirm('Are you sure? This will remove ALL security logs permanently.')">
                    @csrf
                    <button type="submit" class="w-full p-3 text-left rounded-lg border border-red-200 hover:bg-red-50 dark:border-red-800 dark:hover:bg-red-900/20 transition-colors">
                        <div class="font-medium text-red-700 dark:text-red-300">Clear All Security Logs</div>
                        <div class="text-sm text-red-600 dark:text-red-400">⚠️ This action cannot be undone</div>
                    </button>
                </form>
            </div>
            
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <button onclick="closeClearModal()" class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</main>

<script>
let currentLog = null;

function openLogModal(log) {
    currentLog = log;
    const modal = document.getElementById('log-modal');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('modal-content');
    
    title.textContent = log.action === 'failed_login' ? 'Failed Login Attempt' : 'Activity Details';
    
    let html = '';
    
    // Basic info
    html += '<div class="grid grid-cols-2 gap-4">';
    html += `<div><strong>IP Address:</strong><br>${log.ip || 'N/A'}</div>`;
    html += `<div><strong>Time:</strong><br>${new Date(log.timestamp).toLocaleString()}</div>`;
    html += '</div>';
    
    // Device info - handle nested structure
    const device = log.location?.device || log.device;
    if (device) {
        html += '<div class="mt-4"><h4 class="font-medium mb-2">Device Information</h4>';
        html += '<div class="grid grid-cols-2 gap-4 text-sm">';
        html += `<div><strong>Browser:</strong> ${device.browser || 'N/A'} ${device.browser_version || ''}</div>`;
        html += `<div><strong>Platform:</strong> ${device.platform || 'N/A'} ${device.platform_version || ''}</div>`;
        html += `<div class="col-span-2"><strong>User Agent:</strong> ${device.user_agent || 'N/A'}</div>`;
        html += '</div></div>';
    }
    
    // Location info - handle nested structure
    const country = log.location?.country || log.country;
    const region = log.location?.region || log.region;
    const city = log.location?.city || log.city;
    const coordinates = log.location?.coordinates || log.coordinates;
    
    if (country || city) {
        html += '<div class="mt-4"><h4 class="font-medium mb-2">Location</h4>';
        html += '<div class="grid grid-cols-2 gap-4 text-sm">';
        html += `<div><strong>Country:</strong> ${country?.name || 'N/A'}</div>`;
        html += `<div><strong>Region:</strong> ${region?.name || 'N/A'}</div>`;
        html += `<div><strong>City:</strong> ${city?.name || 'N/A'}</div>`;
        if (coordinates && coordinates.latitude) {
            html += `<div><strong>Coordinates:</strong> ${coordinates.latitude.toFixed(4)}, ${coordinates.longitude.toFixed(4)}</div>`;
        }
        html += '</div></div>';
    }
    
    // Security info - handle nested structure
    const security = log.location?.security || log.security;
    if (security) {
        html += '<div class="mt-4"><h4 class="font-medium mb-2">Security Assessment</h4>';
        html += '<div class="grid grid-cols-2 gap-4 text-sm">';
        html += `<div>VPN: ${security.is_vpn ? '⚠️ Yes' : '✅ No'}</div>`;
        html += `<div>Proxy: ${security.is_anonymous_proxy ? '⚠️ Yes' : '✅ No'}</div>`;
        html += `<div>Tor: ${security.is_tor ? '⚠️ Yes' : '✅ No'}</div>`;
        html += `<div>Satellite: ${security.is_satellite_provider ? '⚠️ Yes' : '✅ No'}</div>`;
        html += '</div></div>';
    }
    
    content.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    lucide.createIcons();
}

function closeLogModal() {
    document.getElementById('log-modal').classList.add('hidden');
    document.getElementById('log-modal').classList.remove('flex');
}

function openClearModal() {
    document.getElementById('clear-modal').classList.remove('hidden');
    document.getElementById('clear-modal').classList.add('flex');
}

function closeClearModal() {
    document.getElementById('clear-modal').classList.add('hidden');
    document.getElementById('clear-modal').classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

@endsection