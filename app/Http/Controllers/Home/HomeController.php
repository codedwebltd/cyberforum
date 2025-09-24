<?php

namespace App\Http\Controllers\Home;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Discussion\DiscussionController;

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is authenticated and onboarding is incomplete alerady handled at route level
        // if (auth()->check() && !auth()->user()->onboarding_completed) {
        //     return redirect()->route('onboarding');
        // }

        $discussion = new DiscussionController();
        $discussions = $discussion->discussions(request());
        return view('home.index', compact('discussions'));
    }

    // public function onboarding()
    // {
    //     // Redirect if user is not authenticated
    //     if (!auth()->check()) {
    //         return redirect()->route('login');
    //     }

    //     // Redirect if onboarding is already completed
    //     if (auth()->user()->onboarding_completed) {
    //         return redirect()->route('home');
    //     }

    //     return view('home.onboarding');
    // }
    public function consoleDetected(Request $request)
{
    // Log the console detection attempt
    Log::warning('Developer console access detected', [
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'timestamp' => now(),
        'user_id' => auth()->id(),
        'referrer' => $request->header('referer')
    ]);

    // Extract location data for security logging
    if (method_exists($this, 'extractLocationData')) {
        $locationData = $this->extractLocationData();
        Log::info('Console detection location', $locationData);
    }

    // If user is authenticated, log to their security history
    if (auth()->check()) {
        $user = auth()->user();
        $this->logSecurityEvent($user, 'console_access_attempt');
    }

    // Rate limit console detection to prevent spam
    $key = 'console_detected:' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 5)) {
        abort(429, 'Too many attempts');
    }
    RateLimiter::hit($key, 300); // 5 minutes

    return response()->view('security.console-detected', [
        'timestamp' => now()->format('Y-m-d H:i:s'),
        'ip' => $request->ip()
    ])->header('X-Frame-Options', 'DENY');
}

private function logSecurityEvent($user, $eventType)
{
    $locationHistory = $user->location_history ?? [];

    $securityEvent = [
        'event_type' => $eventType,
        'timestamp' => now()->toISOString(),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'session_id' => session()->getId()
    ];

    $locationHistory[] = $securityEvent;

    // Keep only last 100 security events
    if (count($locationHistory) > 100) {
        $locationHistory = array_slice($locationHistory, -100);
    }

    $user->update(['location_history' => $locationHistory]);
}

public function loadMoreDiscussions(Request $request)
{
    $discussion = new DiscussionController();
    
    $page = $request->get('page', 1);
    $discussions = $discussion->discussions(request());
    // $discussions = $this->discussions($request);
    
    $html = '';
    foreach ($discussions as $discussion) {
        $html .= view('home.discussions.partials.discussion-item', compact('discussion'))->render();
    }
    
    return response()->json([
        'success' => true,
        'html' => $html,
        'has_more' => $discussions->hasMorePages(),
        'next_page' => $discussions->currentPage() + 1,
        'total' => $discussions->total()
    ]);
}


// public function loadMoreDiscussions(Request $request)
// {
//     $query = Post::with(['user'])
//         ->where('type', 'discussion')
//         ->where('status', 'published')
//         ->where('is_approved', true);

//     // Apply filter if present
//     if ($request->has('filter')) {
//         $filter = $request->filter;
//         switch ($filter) {
//             case 'trending':
//                 $query->where('created_at', '>=', now()->subDays(7))
//                       ->orderByRaw('(likes_count + comments_count + views_count) DESC');
//                 break;
//             case 'hot':
//                 $query->where('created_at', '>=', now()->subDays(3))
//                       ->orderByRaw('(likes_count * 2 + comments_count * 3) DESC');
//                 break;
//             case 'latest':
//             default:
//                 $query->orderBy('last_activity_at', 'desc');
//                 break;
//         }
//     } else {
//         $query->orderBy('is_pinned', 'desc')
//               ->orderBy('last_activity_at', 'desc');
//     }

//     // Pagination (5 per page, or whatever you want)
//     $discussions = $query->paginate(5, ['*'], 'page', $request->page ?? 1);

//     // Mark liked discussions for authenticated users
//     if (auth()->check()) {
//         $likedDiscussionIds = Like::where('user_id', auth()->id())
//             ->where('likeable_type', Post::class)
//             ->whereIn('likeable_id', $discussions->pluck('id'))
//             ->pluck('likeable_id')
//             ->toArray();

//         foreach ($discussions as $discussion) {
//             $discussion->is_liked_by_user = in_array($discussion->id, $likedDiscussionIds);
//         }
//     }

//     // Render HTML using your discussion partial
//     $html = '';
//     foreach ($discussions as $discussion) {
//         $html .= view('home.discussions.partials.discussion-item', compact('discussion'))->render();
//     }

//     return response()->json([
//         'success' => true,
//         'html' => $html,
//         'has_more' => $discussions->hasMorePages(),
//         'next_page' => $discussions->currentPage() + 1
//     ]);
// }

}
