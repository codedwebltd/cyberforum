<?php

namespace App\Http\Controllers\Discussion;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessFailedLikesJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Jobs\DiscussionNotificationJob;

class DiscussionController extends Controller
{
    public function index(Request $request)
    {
        $discussions = $this->discussions($request);
        return view('home.discussions.index', compact('discussions'));
    }

public function discussions(Request $request)
{
    $query = Post::with(['user'])
        ->where('type', 'discussion')
        ->where('status', 'published')
        ->where('is_approved', true);

    // Filter by category/type if provided codedweb
    if ($request->has('filter')) {
        $filter = $request->filter;
        switch ($filter) {
            case 'trending':
                $query->where('created_at', '>=', now()->subDays(7))
                      ->orderByRaw('(likes_count + comments_count + views_count) DESC');
                break;
            case 'hot':
                $query->where('created_at', '>=', now()->subDays(3))
                      ->orderByRaw('(likes_count * 2 + comments_count * 3) DESC');
                break;
            case 'latest':
            default:
                $query->orderBy('last_activity_at', 'desc');
                break;
        }
    } else {
        $query->orderBy('is_pinned', 'desc')
              ->orderBy('last_activity_at', 'desc');
    }

    $discussions = $query->paginate(2);

    // Check which discussions the user has liked
    if (auth()->check()) {
        $likedDiscussionIds = Like::where('user_id', auth()->id())
            ->where('likeable_type', Post::class)
            ->whereIn('likeable_id', $discussions->pluck('id'))
            ->pluck('likeable_id')
            ->toArray();

        foreach ($discussions as $discussion) {
            $discussion->is_liked_by_user = in_array($discussion->id, $likedDiscussionIds);
        }
    }

    return $discussions;
}

   public function show($slug)
{
    $discussion = Post::with(['user'])
        ->where('slug', $slug)
        ->where('type', 'discussion')
        ->where('status', 'published')
        ->where('is_approved', true)
        ->firstOrFail();

    // Increment view count
    $discussion->increment('views_count');
    $discussion->update(['last_activity_at' => now()]);

    // Get only 4 top-level comments initially
    $comments = $discussion->comments()
        ->with(['user'])
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->paginate(4); // Changed from 5 to 4

    // For each comment, get first 3 replies and check like status
    foreach ($comments as $comment) {
        $comment->initial_replies = $comment->replies()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();
        
        $comment->total_replies = $comment->replies()->count();
        $comment->has_more_replies = $comment->total_replies > 3;
        
        // Check if user has liked this comment
        if (auth()->check()) {
            $comment->is_liked_by_user = Like::where('user_id', auth()->id())
                ->where('likeable_type', Comment::class)
                ->where('likeable_id', $comment->id)
                ->exists();
        } else {
            $comment->is_liked_by_user = false;
        }
    }

    // Check if user has liked this discussion
    $discussion->is_liked_by_user = false;
    if (auth()->check()) {
        $discussion->is_liked_by_user = Like::where('user_id', auth()->id())
            ->where('likeable_type', Post::class)
            ->where('likeable_id', $discussion->id)
            ->exists();
    }

    // Get related discussions
    $relatedDiscussions = Post::where('type', 'discussion')
        ->where('status', 'published')
        ->where('is_approved', true)
        ->where('id', '!=', $discussion->id)
        ->orderBy('likes_count', 'desc')
        ->limit(5)
        ->get();

    return view('home.discussions.show', compact('discussion', 'relatedDiscussions', 'comments'));
}

    public function like(Request $request, $slug)
{
    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $isLiked = Like::toggle(auth()->user(), $discussion);
    
    if ($isLiked) {
       // DiscussionNotificationJob::dispatch($discussion, null, 'like');
    }
    
    return response()->json([
        'success' => true,
        'liked' => $isLiked, // This tells us if it's now liked or unliked
        'likes_count' => $discussion->fresh()->likes_count
    ]);
}

   public function storeComment(Request $request, $slug)
{
    $request->validate([
        'content' => 'required|string|max:2000',
        'parent_id' => 'nullable|exists:comments,id'
    ]);

    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $discussion->id,
        'parent_id' => $request->parent_id,
        'content' => $request->content,
    ]);

    // Update comment path for hierarchy
    $comment->updatePath();
    
    // Update discussion counters
    $discussion->increment('comments_count');
    $discussion->update(['last_activity_at' => now()]);

    // Dispatch notification job
    //DiscussionNotificationJob::dispatch($discussion, $comment, 'comment');

    // Always return JSON for replies
    return response()->json([
        'success' => true,
        'message' => 'Reply posted successfully!',
        'comment' => $comment->load('user')
    ]);
}


public function storeReplyByPostId(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:2000',
        'parent_id' => 'required|exists:comments,id',
        'post_id' => 'required|exists:posts,id'
    ]);

    $discussion = Post::findOrFail($request->post_id);
    
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $discussion->id,
        'parent_id' => $request->parent_id,
        'content' => $request->content,
    ]);

    $comment->updatePath();
    $discussion->increment('comments_count');
    $discussion->update(['last_activity_at' => now()]);

    // Generate HTML for the new reply
    $replyHtml = view('home.discussions.partials.reply-item', ['reply' => $comment->load('user')])->render();

    return response()->json([
        'success' => true,
        'message' => 'Reply posted successfully!',
        'reply_html' => $replyHtml
    ]);
}

public function storeMainComment(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:2000',
        'post_id' => 'required|exists:posts,id'
    ]);

    $discussion = Post::findOrFail($request->post_id);
    
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $discussion->id,
        'parent_id' => null,
        'content' => $request->content,
    ]);

    $comment->updatePath();
    $discussion->increment('comments_count');
    $discussion->update(['last_activity_at' => now()]);

    // Prepare comment for display with like status
    $comment->initial_replies = collect();
    $comment->total_replies = 0;
    $comment->has_more_replies = false;
    
    // Check if current user has liked this comment (will be false for new comment)
    $comment->is_liked_by_user = false;

    $commentHtml = view('home.discussions.partials.comment-modal', ['comment' => $comment->load('user')])->render();

    return response()->json([
        'success' => true,
        'message' => 'Comment posted successfully!',
        'comment_html' => $commentHtml
    ]);
}

    public function likeComment(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $isLiked = Like::toggle(auth()->user(), $comment);
        
        // Dispatch notification if liked
        if ($isLiked) {
            DiscussionNotificationJob::dispatch($comment->post, $comment, 'like_comment');
        }

        return response()->json([
            'success' => true,
            'liked' => $isLiked,
            'likes_count' => $comment->fresh()->likes_count
        ]);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::where('id', $commentId)
                         ->where('user_id', auth()->id())
                         ->firstOrFail();
        
        $discussion = $comment->post;
        $discussion->decrement('comments_count');
        
        $comment->delete();

        return response()->json(['success' => true]);
    }

    private function formatTime($datetime)
    {
        $diff = now()->diffInSeconds($datetime);
        
        if ($diff < 60) return $diff . 's';
        if ($diff < 3600) return floor($diff / 60) . 'm';
        if ($diff < 86400) return floor($diff / 3600) . 'h';
        if ($diff < 2592000) return floor($diff / 86400) . 'd';
        if ($diff < 31536000) return floor($diff / 2592000) . 'mo';
        
        return floor($diff / 31536000) . 'y';
    }


public function getComments($postId, Request $request)
{
    $post = Post::findOrFail($postId);
    $page = $request->get('page', 1);
    $perPage = 6;
    
    $comments = $post->comments()
        ->with(['user'])
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);

    // For each comment, get only first 3 replies
    foreach ($comments as $comment) {
        $comment->initial_replies = $comment->replies()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();
        
        $comment->total_replies = $comment->replies()->count();
        $comment->has_more_replies = $comment->total_replies > 3;
        
        // Check if user has liked this comment
        if (auth()->check()) {
            $comment->is_liked_by_user = Like::where('user_id', auth()->id())
                ->where('likeable_type', Comment::class)
                ->where('likeable_id', $comment->id)
                ->exists();
        } else {
            $comment->is_liked_by_user = false;
        }
    }

    $html = '';
    foreach ($comments as $comment) {
        $html .= view('home.discussions.partials.comment-modal', compact('comment'))->render();
    }

    return response()->json([
        'success' => true,
        'html' => $html,
        'has_more' => $comments->hasMorePages(),
        'next_page' => $comments->currentPage() + 1,
        'current_page' => $comments->currentPage(),
        'total' => $comments->total()
    ]);
}

public function loadMoreModalComments($postId, Request $request)
{
    return $this->getComments($postId, $request);
}

public function loadMoreComments(Request $request, $slug)
{
    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $comments = $discussion->comments()
        ->with(['user'])
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->paginate(5, ['*'], 'page', $request->page);

    foreach ($comments as $comment) {
        $comment->initial_replies = $comment->replies()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();
        
        $comment->total_replies = $comment->replies()->count();
        $comment->has_more_replies = $comment->total_replies > 3;
    }

    $html = '';
    foreach ($comments as $comment) {
        $html .= view('home.discussions.partials.comment-paginated', compact('comment', 'discussion'))->render();
    }

    

    return response()->json([
        'success' => true,
        'html' => $html,
        'has_more' => $comments->hasMorePages(),
        'next_page' => $comments->currentPage() + 1
    ]);
}


public function loadMoreReplies(Request $request, $commentId)
{
    $comment = Comment::findOrFail($commentId);
    $page = $request->get('page', 1);
    $perPage = 5;
    
    $replies = $comment->replies()
        ->with(['user'])
        ->orderBy('created_at', 'asc')
        ->skip(($page - 1) * $perPage)
        ->take($perPage)
        ->get();

    $totalReplies = $comment->replies()->count();
    $loadedCount = (($page - 1) * $perPage) + $replies->count();
    $hasMore = $loadedCount < $totalReplies;

    $html = '';
    foreach ($replies as $reply) {
        $html .= view('home.discussions.partials.reply-item', compact('reply'))->render();
    }

    return response()->json([
        'success' => true,
        'html' => $html,
        'has_more' => $hasMore,
        'next_page' => $page + 1,
        'loaded_count' => $loadedCount,
        'total_count' => $totalReplies
    ]);
}


public function cacheFailedLike(Request $request)
{
    $action = [
        'user_id' => auth()->id(),
        'discussion_slug' => $request->slug,
        'action_type' => 'like',
        'timestamp' => now()->toISOString(),
        'ip' => $request->ip(),
        'retry_count' => 0
    ];

    // Store in Redis with expiry (24 hours)
    Redis::setex(
        "failed_like:" . auth()->id() . ":" . $request->slug . ":" . time(), 
        86400, 
        json_encode($action)
    );

    // Dispatch job to retry later
    ProcessFailedLikesJob::dispatch()->delay(now()->addMinutes(5));

    return response()->json([
        'success' => false,
        'cached' => true,
        'message' => 'Action cached for retry'
    ]);
}

// Add to DiscussionController
public function filterDiscussions(Request $request)
{
    $filter = $request->get('filter', 'latest');
    $discussions = $this->discussions($request);
    
    $html = '';
    foreach ($discussions as $discussion) {
        $html .= view('home.discussions.partials.discussion-item', compact('discussion'))->render();
    }
    
    return response()->json(['success' => true, 'html' => $html]);
}


public function loadMoreCommentsForShow($slug, Request $request)
{
    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $comments = $discussion->comments()
        ->with(['user'])
        ->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->paginate(4, ['*'], 'page', $request->page); // Changed to 4

    foreach ($comments as $comment) {
        $comment->initial_replies = $comment->replies()
            ->with(['user'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();
        
        $comment->total_replies = $comment->replies()->count();
        $comment->has_more_replies = $comment->total_replies > 3;
        
        // Add like status check
        if (auth()->check()) {
            $comment->is_liked_by_user = Like::where('user_id', auth()->id())
                ->where('likeable_type', Comment::class)
                ->where('likeable_id', $comment->id)
                ->exists();
        } else {
            $comment->is_liked_by_user = false;
        }
    }

    $html = '';
    foreach ($comments as $comment) {
        $html .= view('home.discussions.partials.comment', ['comment' => $comment, 'depth' => 0, 'discussion' => $discussion])->render();
    }

    return response()->json([
        'success' => true,
        'html' => $html,
        'has_more' => $comments->hasMorePages(),
        'next_page' => $comments->currentPage() + 1
    ]);
}
public function share(Request $request, $slug)
{
    $discussion = Post::where('slug', $slug)->firstOrFail();
    $discussion->increment('shares_count');
    
    return response()->json([
        'success' => true,
        'shares_count' => $discussion->fresh()->shares_count
    ]);
}


public function storeCommentForShow(Request $request, $slug)
{
    $request->validate([
        'content' => 'required|string|max:2000'
    ]);

    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $discussion->id,
        'parent_id' => null,
        'content' => $request->content,
    ]);

    $comment->updatePath();
    $discussion->increment('comments_count');
    $discussion->update(['last_activity_at' => now()]);

    // Prepare comment for display
    $comment->initial_replies = collect();
    $comment->total_replies = 0;
    $comment->has_more_replies = false;
    $comment->is_liked_by_user = false;

    $commentHtml = view('home.discussions.partials.comment', [
        'comment' => $comment->load('user'), 
        'depth' => 0, 
        'discussion' => $discussion
    ])->render();

    return response()->json([
        'success' => true,
        'message' => 'Comment posted successfully!',
        'comment_html' => $commentHtml,
        'comments_count' => $discussion->fresh()->comments_count
    ]);
}

public function storeReplyForShow(Request $request, $slug)
{
    $request->validate([
        'content' => 'required|string|max:2000',
        'parent_id' => 'required|exists:comments,id'
    ]);

    $discussion = Post::where('slug', $slug)->firstOrFail();
    
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $discussion->id,
        'parent_id' => $request->parent_id,
        'content' => $request->content,
    ]);

    $comment->updatePath();
    $discussion->increment('comments_count');
    $discussion->update(['last_activity_at' => now()]);

    $replyHtml = view('home.discussions.partials.reply-item', ['reply' => $comment->load('user')])->render();

    return response()->json([
        'success' => true,
        'message' => 'Reply posted successfully!',
        'reply_html' => $replyHtml
    ]);
}

}