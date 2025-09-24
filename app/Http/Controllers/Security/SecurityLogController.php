<?php

namespace App\Http\Controllers\Security;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SecurityLogController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Get security metrics
        $metrics = $user->getSecurityMetrics(30); // Last 30 days
        $recentMetrics = $user->getSecurityMetrics(7); // Last 7 days
        
        // Get location history
        $locationHistory = $user->location_history ?? [];
        
        // Sort by timestamp (newest first)
        usort($locationHistory, function($a, $b) {
            return strtotime($b['timestamp'] ?? 0) - strtotime($a['timestamp'] ?? 0);
        });
        
        return view('home.security.index', compact('locationHistory', 'metrics', 'recentMetrics'));
    }
    
    /**
     * Clear all failed login attempts from user's history
     */
    public function clearFailedLogins(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $locationHistory = $user->location_history ?? [];
        
        // Filter out failed login attempts
        $cleanedHistory = array_filter($locationHistory, function($item) {
            return !isset($item['action']) || $item['action'] !== 'failed_login';
        });
        
        // Reindex array to avoid gaps
        $cleanedHistory = array_values($cleanedHistory);
        
        // Update user's location history
        $user->update(['location_history' => $cleanedHistory]);
        
        return redirect()->route('security.index')
            ->with('success', 'Failed login attempts have been cleared from your security log.');
    }
    
    /**
     * Clear all security logs
     */
    public function clearAllLogs(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Clear all location history
        $user->update(['location_history' => []]);
        
        return redirect()->route('security.index')
            ->with('success', 'All security logs have been cleared.');
    }
    
    /**
     * Clear logs older than specified days
     */
    public function clearOldLogs(Request $request): RedirectResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);
        
        $user = Auth::user();
        $locationHistory = $user->location_history ?? [];
        $days = $request->input('days', 30);
        
        // Filter out logs older than specified days
        $recentHistory = array_filter($locationHistory, function($item) use ($days) {
            if (!isset($item['timestamp'])) {
                return false;
            }
            
            try {
                return \Carbon\Carbon::parse($item['timestamp'])->isAfter(now()->subDays($days));
            } catch (\Exception $e) {
                return false;
            }
        });
        
        // Reindex array
        $recentHistory = array_values($recentHistory);
        
        // Update user's location history
        $user->update(['location_history' => $recentHistory]);
        
        return redirect()->route('security.index')
            ->with('success', "Security logs older than {$days} days have been cleared.");
    }
}