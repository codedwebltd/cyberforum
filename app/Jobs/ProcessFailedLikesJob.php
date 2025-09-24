<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\Like;
use App\Models\User;

class ProcessFailedLikesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $pattern = "failed_like:*";
        $keys = Redis::keys($pattern);

        foreach ($keys as $key) {
            $actionData = Redis::get($key);
            if (!$actionData) continue;

            $action = json_decode($actionData, true);
            
            try {
                $user = User::find($action['user_id']);
                $discussion = Post::where('slug', $action['discussion_slug'])->first();
                
                if ($user && $discussion) {
                    // Attempt the like action
                    Like::toggle($user, $discussion);
                    
                    // Success - remove from cache
                    Redis::del($key);
                    Log::info('Successfully processed cached like', $action);
                } else {
                    // Invalid data - remove from cache
                    Redis::del($key);
                }
                
            } catch (\Exception $e) {
                // Increment retry count
                $action['retry_count']++;
                
                if ($action['retry_count'] >= 3) {
                    // Max retries reached - remove from cache
                    Redis::del($key);
                    Log::error('Failed like action max retries exceeded', $action);
                } else {
                    // Update cache with incremented retry count
                    Redis::setex($key, 86400, json_encode($action));
                    Log::warning('Failed like action retry ' . $action['retry_count'], $action);
                }
            }
        }
    }
}