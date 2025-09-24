<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing posts and users
        $posts = Post::where('type', 'discussion')->limit(3)->get();
        $userIds = [1, 33]; // Your existing users
        
        if ($posts->isEmpty()) {
            $this->command->info('No discussion posts found. Please run PostSeeder first.');
            return;
        }

        foreach ($posts as $post) {
            $this->seedCommentsForPost($post, $userIds);
        }
    }

    private function seedCommentsForPost($post, $userIds)
    {
        $commentTexts = [
            "This is a really insightful discussion. I completely agree with the points raised here.",
            "I have a different perspective on this topic. Let me share my thoughts...",
            "Thanks for sharing this! It really helped me understand the concept better.",
            "I'm not sure I agree with this approach. Have you considered the potential drawbacks?",
            "This reminds me of a similar situation I encountered last year. Here's what I learned...",
            "Could you elaborate more on this specific point? I'd love to understand it better.",
            "I've been working on something similar and ran into the same challenges.",
            "This is exactly what I needed to read today. Thank you for the detailed explanation!",
            "I think there might be a better way to approach this problem. What do you think?",
            "Great post! I've bookmarked this for future reference.",
            "I disagree with some of the conclusions here, but I appreciate the thorough analysis.",
            "Has anyone tried implementing this in a production environment?",
            "This brings up an interesting question about scalability and performance.",
            "I've seen this pattern used successfully in several projects I've worked on.",
            "The examples provided really help clarify the concepts discussed.",
            "I'm curious about the long-term implications of this approach.",
            "This is a common misconception in our industry. Thanks for addressing it!",
            "I would add that security considerations are also important here.",
            "The documentation you linked is really helpful. Thanks for sharing!",
            "I've been struggling with this exact issue. Your solution is brilliant!"
        ];

        $this->command->info("Seeding comments for post: {$post->title}");

        // Create 50-100 parent comments per post
        $parentCommentCount = rand(50, 100);
        
        for ($i = 0; $i < $parentCommentCount; $i++) {
            $parentComment = Comment::create([
                'user_id' => $userIds[array_rand($userIds)],
                'post_id' => $post->id,
                'parent_id' => null,
                'content' => $commentTexts[array_rand($commentTexts)] . " (Parent comment #" . ($i + 1) . ")",
                'likes_count' => rand(0, 50),
                'is_approved' => true,
                'status' => 'published',
            ]);

            $parentComment->updatePath();

            // 70% chance of having replies
            if (rand(1, 100) <= 70) {
                $this->createReplies($parentComment, $userIds, $commentTexts, 1, rand(1, 15));
            }

            if ($i % 10 == 0) {
                $this->command->info("Created {$i} parent comments...");
            }
        }

        // Update post comment count
        $totalComments = Comment::where('post_id', $post->id)->count();
        $post->update(['comments_count' => $totalComments]);
        
        $this->command->info("Finished seeding {$totalComments} total comments for post: {$post->title}");
    }

    private function createReplies($parentComment, $userIds, $commentTexts, $depth, $replyCount)
    {
        // Limit depth to 4 levels to prevent infinite nesting
        if ($depth > 4) {
            return;
        }

        // Reduce reply count as depth increases
        $maxReplies = max(1, $replyCount - ($depth * 2));
        $actualReplies = rand(1, $maxReplies);

        for ($i = 0; $i < $actualReplies; $i++) {
            $reply = Comment::create([
                'user_id' => $userIds[array_rand($userIds)],
                'post_id' => $parentComment->post_id,
                'parent_id' => $parentComment->id,
                'content' => $commentTexts[array_rand($commentTexts)] . " (Reply depth {$depth}, #{$i})",
                'likes_count' => rand(0, 20),
                'is_approved' => true,
                'status' => 'published',
            ]);

            $reply->updatePath();

            // 50% chance of having nested replies (decreasing with depth)
            $nestedChance = max(10, 50 - ($depth * 15));
            if (rand(1, 100) <= $nestedChance && $depth < 4) {
                $this->createReplies($reply, $userIds, $commentTexts, $depth + 1, rand(1, 5));
            }
        }

        // Update parent replies count
        $parentComment->update([
            'replies_count' => $parentComment->replies()->count()
        ]);
    }
}