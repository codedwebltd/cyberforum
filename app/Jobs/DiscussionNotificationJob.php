<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Traits\EmailNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DiscussionNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EmailNotificationTrait;

    protected $post;
    protected $comment;
    protected $actionType;
    protected $batchSize = 3; // Send to 3 users per batch
    protected $sleepTime = 17; // 17 seconds between batches (snail mode)

    public $timeout = 3600; // 1 hour
    public $tries = 2;

    public function __construct(Post $post, Comment $comment = null, string $actionType)
    {
        $this->post = $post;
        $this->comment = $comment;
        $this->actionType = $actionType;
    }

    public function handle(): void
    {
        try {
            $usersToNotify = $this->getUsersToNotify();
            
            if ($usersToNotify->isEmpty()) {
                Log::info('No users to notify for discussion action', [
                    'post_id' => $this->post->id,
                    'action' => $this->actionType
                ]);
                return;
            }

            Log::info('Starting discussion notifications', [
                'post_id' => $this->post->id,
                'action' => $this->actionType,
                'users_count' => $usersToNotify->count()
            ]);

            $totalUsers = $usersToNotify->count();
            $totalBatches = ceil($totalUsers / $this->batchSize);

            // Process users in batches for snail sending
            $usersToNotify->chunk($this->batchSize)->each(function ($batch, $batchIndex) use ($totalBatches) {
                foreach ($batch as $user) {
                    $this->sendNotificationToUser($user);
                }

                // Sleep between batches (except for the last batch)
                if ($batchIndex < ($totalBatches - 1)) {
                    Log::info('Sleeping between notification batches', ['seconds' => $this->sleepTime]);
                    sleep($this->sleepTime);
                }
            });

            Log::info('Discussion notifications completed', [
                'post_id' => $this->post->id,
                'total_notified' => $totalUsers
            ]);

        } catch (\Exception $e) {
            Log::error('Discussion notification job failed', [
                'error' => $e->getMessage(),
                'post_id' => $this->post->id,
                'action' => $this->actionType
            ]);
            throw $e;
        }
    }

    private function getUsersToNotify()
    {
        $userIds = collect();
        $currentUserId = auth()->id();

        // Get post author (if not the current user)
        if ($this->post->user_id !== $currentUserId) {
            $userIds->push($this->post->user_id);
        }

        // Get users who liked the post
        $likedUserIds = $this->post->likes()
            ->where('user_id', '!=', $currentUserId)
            ->pluck('user_id');
        $userIds = $userIds->merge($likedUserIds);

        // Get users who commented on the post
        $commentUserIds = $this->post->comments()
            ->where('user_id', '!=', $currentUserId)
            ->distinct()
            ->pluck('user_id');
        $userIds = $userIds->merge($commentUserIds);

        // For comment likes, also notify the comment author
        if ($this->actionType === 'like_comment' && $this->comment) {
            if ($this->comment->user_id !== $currentUserId) {
                $userIds->push($this->comment->user_id);
            }
        }

        // Remove duplicates and get users with their settings
        return User::with('settings')
            ->whereIn('id', $userIds->unique()->values())
            ->get()
            ->filter(function ($user) {
                // Check if user wants this type of notification
                if (!$user->settings) return false;
                
                switch ($this->actionType) {
                    case 'comment':
                        return $user->settings->comment_notifications;
                    case 'like':
                    case 'like_comment':
                        return $user->settings->like_notifications;
                    default:
                        return $user->settings->push_notifications;
                }
            });
    }

    private function sendNotificationToUser(User $user): void
    {
        $message = $this->buildNotificationMessage($user);
        
        try {
            $this->ActionNotification($user->id, $message);
            
            Log::info('Notification sent successfully', [
                'user_id' => $user->id,
                'action' => $this->actionType,
                'post_id' => $this->post->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send notification to user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'post_id' => $this->post->id
            ]);
        }
    }

    private function buildNotificationMessage(User $user): array
    {
        $actorName = auth()->user()->name ?? 'Someone';
        $postTitle = \Illuminate\Support\Str::limit($this->post->title, 50);

        switch ($this->actionType) {
            case 'comment':
                if ($user->id === $this->post->user_id) {
                    $message = "{$actorName} commented on your discussion \"{$postTitle}\"";
                } else {
                    $message = "{$actorName} also commented on \"{$postTitle}\"";
                }
                break;

            case 'like':
                $message = "{$actorName} liked your discussion \"{$postTitle}\"";
                break;

            case 'like_comment':
                if ($user->id === $this->comment->user_id) {
                    $message = "{$actorName} liked your comment on \"{$postTitle}\"";
                } else {
                    $message = "{$actorName} liked a comment on \"{$postTitle}\"";
                }
                break;

            default:
                $message = "{$actorName} interacted with \"{$postTitle}\"";
        }

        return [
            'response' => $message,
            'subject' => 'New activity on ' . config('app.name'),
            'type' => 'discussion_activity',
            'post_id' => $this->post->id,
            'comment_id' => $this->comment?->id,
            'action_type' => $this->actionType,
            'actor_id' => auth()->id(),
            'notify_admin' => false
        ];
    }
}