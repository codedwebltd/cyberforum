<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Media\MediaController;
use App\Http\Controllers\Money\WalletController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Security\SecurityLogController;
use App\Http\Controllers\Discussion\DiscussionController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Discussion\DiscussionActionController;
use App\Http\Controllers\Events\EventsController;
use App\Http\Controllers\Members\MembersController;

/*
|--------------------------------------------------------------------------
| Home Routes - Organized by Access Level
|--------------------------------------------------------------------------
*/

// =============================================================================
// UNRESTRICTED ROUTES (No onboarding requirement)
// These routes are accessible even if onboarding is incomplete
// =============================================================================
Route::middleware(['auth'])->group(function() {
    
    // Onboarding Process Routes
    // Users can access these while completing onboarding
    Route::prefix('home/onboarding')->name('onboarding.')->group(function() {
        Route::get('/', [OnboardingController::class, 'onboarding'])->name('index');           // Main onboarding page
        Route::post('/update-step', [OnboardingController::class, 'updateStep'])->name('update-step'); // Save step progress
        Route::post('/skip', [OnboardingController::class, 'skip'])->name('skip');                     // Skip onboarding
    });
    
    // System/Debug Routes
    // Always accessible for debugging/system purposes
    Route::get('/home/console-detected', [HomeController::class, 'consoleDetected'])->name('console.detected'); // Console detection warning

    // Heartbeat for user activity tracking
    Route::post('/home/heartbeat', function () {
        if (Auth::check()) {
            Auth::user()->update([
                'last_active_at' => now(),
                'is_active' => true
            ]);
            Log::info('Heartbeat received for user ID: ' . Auth::id());
            return response()->json(['status' => 'active']);
        }
        Log::info('Heartbeat received for guest user');
        return response()->json(['status' => 'guest']);
    })->name('heartbeat');


});

// =============================================================================
// PROTECTED ROUTES (Require completed onboarding)
// These routes redirect to onboarding if user hasn't completed it
// =============================================================================
Route::middleware(['auth', 'onboarding.check'])->group(function() {
    
    // Main Application Routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');           // Primary homepage

    // User Settings Management
    Route::prefix('home/settings')->name('settings.')->group(function() {
        Route::get('/', [SettingController::class, 'index'])->name('index');                           // Settings page
        Route::put('/', [SettingController::class, 'update'])->name('update');                         // Save settings
        Route::post('/reset', [SettingController::class, 'reset'])->name('reset');                     // Reset to defaults
        Route::delete('/account', [SettingController::class, 'deactivateAccount'])->name('deactivate'); // Account deactivation
        Route::post('/export', [SettingController::class, 'exportData'])->name('export');              // Export user data
    });
    
    // Notification Management System
    Route::prefix('home/notifications')->name('notifications.')->group(function() {
        Route::get('/', [NotificationController::class, 'index'])->name('index');                      // Notifications list
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');         // Mark single as read
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read'); // Mark all read
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');           // Delete single notification
        Route::delete('/clear', [NotificationController::class, 'clear'])->name('clear');              // Clear all notifications
    });


    // Profile Management
    Route::prefix('home/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });


    // Security Management System
    Route::prefix('home/security')->name('security.')->group(function () {
        Route::get('/', [SecurityLogController::class, 'index'])->name('index');
        Route::post('/security/clear/failed', [SecurityLogController::class, 'clearFailedLogins'])->name('clear.failed');
        Route::post('/security/clear/old', [SecurityLogController::class, 'clearOldLogs'])->name('clear.old');
        Route::post('/security/clear/all', [SecurityLogController::class, 'clearAllLogs'])->name('clear.all');
    });


    // Money Management System
    Route::prefix('home/money')->name('money.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        
    });

    // Discussion Management System
Route::prefix('discussion')->name('discussion.')->group(function () {
    Route::get('/index', [DiscussionController::class, 'index'])->name('index');
    Route::get('/{slug}', [DiscussionController::class, 'show'])->name('show');
    Route::post('/{slug}/like', [DiscussionController::class, 'like'])->name('like');
    Route::post('/{slug}/share', [DiscussionController::class, 'share'])->name('share');
    Route::post('/{slug}/comment', [DiscussionController::class, 'storeComment'])->name('comment.store');
    Route::post('/comment/{comment}/like', [DiscussionController::class, 'likeComment'])->name('comment.like');
    Route::delete('/comment/{comment}', [DiscussionController::class, 'deleteComment'])->name('comment.delete');
    Route::get('/comments/{post}', [DiscussionController::class, 'getComments'])->name('comments');
    Route::get('/comments/{post}/load-more', [DiscussionController::class, 'loadMoreModalComments'])->name('comments.load-more');
    Route::get('/comment/{comment}/replies/load-more', [DiscussionController::class, 'loadMoreReplies'])->name('comment.replies.load-more');
    Route::post('/cache-failed-like', [DiscussionController::class, 'cacheFailedLike'])->name('cache-failed-like');
    Route::post('/comment/reply', [DiscussionController::class, 'storeReplyByPostId'])->name('comment.reply');
    Route::post('/comment/main', [DiscussionController::class, 'storeMainComment'])->name('comment.main');
    Route::get('/{slug}/comments/load-more', [DiscussionController::class, 'loadMoreCommentsForShow'])->name('comments.show.load-more');
    Route::post('/{slug}/share', [DiscussionController::class, 'share'])->name('share');
    Route::post('/{slug}/comment-show', [DiscussionController::class, 'storeCommentForShow'])->name('comment.show.store');
    Route::post('/{slug}/reply-show', [DiscussionController::class, 'storeReplyForShow'])->name('reply.show.store');

   // New Discussion Creation Route (renamed to avoid slug conflict)
    Route::get('/new/create', [DiscussionActionController::class, 'create'])->name('create');
    Route::post('/new/store', [DiscussionActionController::class, 'store'])->name('store');
    Route::post('/new/upload-image', [DiscussionActionController::class, 'uploadImage'])->name('upload-image');
    Route::get('/new/media', [DiscussionActionController::class, 'getUserMedia'])->name('media');


});

Route::get('/discussions/filter', [DiscussionController::class, 'filterDiscussions'])->name('discussions.filter');
Route::get('/discussions/load-more', [HomeController::class, 'loadMoreDiscussions'])->name('discussions.load-more');




// Media Management System
Route::prefix('media')->name('media.')->middleware('auth')->group(function () {
    Route::get('/', [MediaController::class, 'index'])->name('index');
    Route::get('/user-media', [MediaController::class, 'getUserMedia'])->name('user.media');
    Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
    Route::delete('/delete', [MediaController::class, 'delete'])->name('delete');
    Route::get('/search', [MediaController::class, 'search'])->name('search');
    Route::get('/file-info', [MediaController::class, 'getFileInfo'])->name('file.info');
    Route::get('/storage-info', [MediaController::class, 'getStorageInfo'])->name('storage.info');
// 1. Add this route to your web.php (TEMPORARILY)
Route::get('/debug-b2-response', [MediaController::class, 'debugB2Response'])->middleware('auth');


});


Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventsController::class, 'index'])->name('index');
    Route::get('/create', [EventsController::class, 'create'])->name('create');
    Route::post('/', [EventsController::class, 'store'])->name('store');
    Route::get('/tags/search', [EventsController::class, 'searchTags'])->name('tags.search');
    Route::get('/{event:slug}', [EventsController::class, 'show'])->name('show');
    Route::get('/{event:slug}/edit', [EventsController::class, 'edit'])->name('edit');
    Route::put('/{event:slug}', [EventsController::class, 'update'])->name('update');
    Route::delete('/{event:slug}', [EventsController::class, 'destroy'])->name('destroy');
});


Route::prefix('members')->name('members.')->group(function () {
    Route::get('/', [MembersController::class, 'index'])->name('index');
    Route::get('/search', [MembersController::class, 'search'])->name('search');
    Route::get('/messages', [MembersController::class, 'messages'])->name('messages');
    Route::get('/chat/{user}', [MembersController::class, 'chat'])->name('chat');
    Route::get('/{member}/details', [MembersController::class, 'details'])->name('details');
});

    // Future Feature Routes (Commented for reference)
    // Route::get('/home/discussions', [DiscussionController::class, 'index'])->name('discussions');
    // Route::get('/home/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
    // Route::get('/home/events', [EventController::class, 'index'])->name('events');
    // Route::get('/home/members', [MemberController::class, 'index'])->name('members');
});

// =============================================================================
// ROUTE NAME ALIASES (For backward compatibility)
// =============================================================================
// Create a simple alias for notifications if needed elsewhere
Route::redirect('/notifications', '/home/notifications')->middleware(['auth', 'onboarding.check']);
Route::redirect('/profile', '/home/profile')->middleware(['auth', 'onboarding.check']);
Route::redirect('/dashboard', '/home')->middleware(['auth', 'onboarding.check'])->name('dashboard');
Route::redirect('/discussion', '/discussion/index')->middleware(['auth', 'onboarding.check']);
