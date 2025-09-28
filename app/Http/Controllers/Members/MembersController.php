<?php

namespace App\Http\Controllers\Members;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MembersController extends Controller
{
    public function index(Request $request)
{
    $query = User::where('is_active', true)
        ->where('profile_public', true);

    // Filter by specific user ID
    if ($request->filled('user')) {
        $query->where('id', $request->user);
    }

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('bio', 'like', "%{$search}%");
        });
    }

    // Sort options
    $sortBy = $request->get('sort', 'recent');
    switch ($sortBy) {
        case 'points':
            $query->orderBy('points', 'desc');
            break;
        case 'reputation':
            $query->orderBy('reputation', 'desc');
            break;
        case 'followers':
            $query->orderBy('followers_count', 'desc');
            break;
        case 'posts':
            $query->orderBy('posts_count', 'desc');
            break;
        case 'alphabetical':
            $query->orderBy('name', 'asc');
            break;
        default:
            $query->orderBy('last_active_at', 'desc');
    }

    $members = $query->paginate(20)->appends(request()->query());
    
    // Stats
    $totalMembers = User::where('is_active', true)->where('profile_public', true)->count();
    $verifiedMembers = User::where('is_active', true)->where('profile_public', true)->where('is_verified', true)->count();
    $onlineMembers = User::where('is_active', true)
        ->where('profile_public', true)
        ->where('last_active_at', '>=', now()->subMinutes(15))
        ->count();

    return view('home.members.index', compact(
        'members',
        'totalMembers',
        'verifiedMembers',
        'onlineMembers'
    ));
}

    public function search(Request $request)
{
    $query = $request->get('q');
    
    if (strlen($query) < 2) {
        return response()->json([]);
    }
    
    $members = User::where('is_active', true)
        ->where('profile_public', true)
        ->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('username', 'like', "%{$query}%");
        })
        ->limit(8)
        ->get(['id', 'name', 'username', 'avatar_url']);
    
    return response()->json($members);
}

public function details(User $member)
{
    if (!$member->is_active || !$member->profile_public) {
        abort(404);
    }
    
    return response()->json($member->only([
        'id', 'name', 'username', 'bio', 'location', 'avatar_url',
        'points', 'followers_count', 'posts_count', 'is_verified'
    ]));
}

public function messages()
{
    return view('home.members.messages');
}

public function chat(User $user)
{
    if (!$user->is_active || !$user->profile_public) {
        abort(404);
    }
    
    return view('home.members.chat', ['chatUser' => $user]);
}
}
