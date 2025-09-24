<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discussionTemplates = [
            [
                'title' => "What's the best way to learn React in 2024?",
                'content' => "I'm a beginner looking to get into React development. What resources would you recommend for someone just starting out? I've heard about various courses and tutorials, but I'm not sure which ones are worth the time investment. Any advice on the learning path would be appreciated!",
                'type' => 'question',
                'tags' => ['react', 'javascript', 'learning', 'beginner'],
            ],
            [
                'title' => "UI/UX Design Trends for 2024",
                'content' => "Sharing some insights on the latest design trends that are shaping user experiences this year. From glassmorphism to micro-interactions, here's what's driving modern design decisions and how they impact user engagement.",
                'type' => 'discussion',
                'tags' => ['design', 'ui', 'ux', 'trends'],
            ],
            [
                'title' => "Freelance Project Management Tips",
                'content' => "How do you manage multiple freelance projects efficiently? Looking for tools and strategies that help maintain quality while juggling different clients and deadlines. What's your workflow like?",
                'type' => 'discussion',
                'tags' => ['freelance', 'project-management', 'productivity'],
            ],
            [
                'title' => "The Future of AI in Web Development",
                'content' => "AI tools are becoming increasingly sophisticated in helping developers write code, debug issues, and even design interfaces. What's your experience with AI-powered development tools? Are they helping or hindering the learning process for new developers?",
                'type' => 'discussion',
                'tags' => ['ai', 'web-development', 'automation', 'future'],
            ],
            [
                'title' => "Best Practices for API Design",
                'content' => "What are your go-to principles when designing RESTful APIs? I'm particularly interested in versioning strategies, error handling, and documentation approaches. Share your experiences with different API design patterns.",
                'type' => 'question',
                'tags' => ['api', 'rest', 'backend', 'best-practices'],
            ],
            [
                'title' => "Remote Work Setup for Developers",
                'content' => "After 3 years of remote work, here's my optimal setup for productivity. From monitor configurations to ergonomic considerations, lighting, and the tools that make the biggest difference in daily workflow.",
                'type' => 'discussion',
                'tags' => ['remote-work', 'productivity', 'setup', 'workspace'],
            ],
            [
                'title' => "Database Performance Optimization Techniques",
                'content' => "Struggling with slow query performance in a growing application. What are your tried-and-true methods for optimizing database performance? Looking for both quick wins and long-term strategies.",
                'type' => 'question',
                'tags' => ['database', 'performance', 'optimization', 'sql'],
            ],
            [
                'title' => "Open Source Contribution Guide",
                'content' => "Getting started with open source can be intimidating. Here's a comprehensive guide on finding projects to contribute to, understanding codebases, making your first PR, and building relationships in the community.",
                'type' => 'discussion',
                'tags' => ['open-source', 'community', 'contribution', 'github'],
            ],
            [
                'title' => "Laravel vs Node.js for Backend Development",
                'content' => "I'm starting a new project and torn between Laravel and Node.js for the backend. What are the pros and cons of each? Performance, learning curve, ecosystem - what should I consider?",
                'type' => 'question',
                'tags' => ['laravel', 'nodejs', 'backend', 'comparison'],
            ],
            [
                'title' => "Mobile-First Design Principles",
                'content' => "In today's mobile-dominated world, designing for mobile first isn't just a trend—it's essential. Here are key principles for creating responsive, user-friendly mobile experiences.",
                'type' => 'discussion',
                'tags' => ['mobile', 'responsive', 'design', 'ux'],
            ]
        ];

        // Get count from command or default to 5
        $count = 5; // You can make this dynamic later

        for ($i = 0; $i < $count; $i++) {
            $template = $discussionTemplates[$i % count($discussionTemplates)];
            
            Post::create([
                'user_id' => rand(0, 1) ? 1 : 33,
                'title' => $template['title'],
                'content' => $template['content'],
                'excerpt' => Str::limit($template['content'], 150),
                'type' => $template['type'],
                'status' => 'published',
                'is_approved' => true,
                'is_pinned' => $i === 0 ? true : (rand(1, 10) === 1), // First one pinned, others 10% chance
                'is_featured' => rand(1, 5) === 1, // 20% chance
                'allow_comments' => true,
                'tags' => $template['tags'],
                'views_count' => rand(50, 500),
                'likes_count' => rand(5, 100),
                'comments_count' => rand(0, 50),
                'published_at' => now()->subDays(rand(1, 30)),
                'last_activity_at' => now()->subHours(rand(1, 48)),
            ]);
        }
    }
}