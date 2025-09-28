<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Events;
use App\Models\Tag;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    private $limit = 15;
    
    private $imageUrls = [
        'https://f005.backblazeb2.com/file/westernkits/social/uploads/1/2025-09-26_21-17-07_kDeMpXnk.jpg',
        'https://f005.backblazeb2.com/file/westernkits/social/uploads/1/2025-09-26_18-26-54_2LCcZMVK.jpeg',
        'https://f005.backblazeb2.com/file/westernkits/social/discussion-images/1/2025-09-25_17-27-49_sq0OaEgl.jpeg'
    ];
    
    private $userIds = [1, 33, 35];
    
    private $eventTitles = [
        'wedding' => [
            'Sarah & John\'s Dream Wedding',
            'Mediterranean Beach Wedding',
            'Vintage Garden Wedding Ceremony',
            'Rustic Barn Wedding Celebration'
        ],
        'birthday' => [
            'Emma\'s 25th Birthday Bash',
            'Kids Birthday Extravaganza',
            'Surprise 30th Birthday Party',
            'Golden 50th Birthday Celebration'
        ],
        'seminar' => [
            'Digital Marketing Mastery',
            'AI & Future Technology Summit',
            'Personal Development Workshop',
            'Business Growth Strategies'
        ],
        'conference' => [
            'Tech Innovation Conference 2024',
            'Global Climate Action Summit',
            'Startup Pitch Competition',
            'Healthcare Innovation Forum'
        ],
        'workshop' => [
            'Photography Basics Workshop',
            'Cooking Masterclass Experience',
            'Web Development Bootcamp',
            'Creative Writing Workshop'
        ],
        'party' => [
            'New Year\'s Eve Celebration',
            'Halloween Costume Party',
            'Summer Pool Party',
            'Rooftop Dance Party'
        ],
        'meeting' => [
            'Community Board Meeting',
            'Neighborhood Safety Discussion',
            'Town Hall Quarterly Update',
            'Local Business Network Meeting'
        ],
        'festival' => [
            'Summer Music Festival',
            'Food & Wine Festival',
            'Art & Culture Celebration',
            'International Film Festival'
        ],
        'concert' => [
            'Jazz Under the Stars',
            'Rock Concert Experience',
            'Classical Music Evening',
            'Local Band Showcase'
        ],
        'sports' => [
            'Basketball Tournament Finals',
            'Community Soccer League',
            'Annual Marathon Event',
            'Tennis Championship Match'
        ]
    ];
    
    private $eventDescriptions = [
        'Join us for an unforgettable experience that brings our community together. This event promises to deliver excitement, learning, and meaningful connections.',
        'Don\'t miss this incredible opportunity to be part of something special. We\'ve planned every detail to ensure you have an amazing time.',
        'Come celebrate with us as we create lasting memories. This event is designed for people of all ages and backgrounds to enjoy together.',
        'Experience the magic of live entertainment and community spirit. Bring your friends and family for a day filled with joy and wonder.',
        'Discover new passions and connect with like-minded individuals. This gathering offers something unique for everyone who attends.',
        'Step into a world of creativity and inspiration. Join fellow enthusiasts for an event that will leave you motivated and energized.'
    ];
    
    private $locations = [
        'Grand Ballroom, City Hotel Downtown',
        'Central Park Amphitheater',
        'Community Center Main Hall',
        'Rooftop Bar, Sunset Plaza',
        'Beach Resort Conference Center',
        'Historic Town Square',
        'University Campus Auditorium',
        'Outdoor Pavilion, Garden District',
        'Convention Center Hall A',
        'Local Library Meeting Room'
    ];
    
    private $venues = [
        'City Hotel', 'Central Park', 'Community Center', 'Sunset Plaza',
        'Beach Resort', 'Town Square', 'University Campus', 'Garden Pavilion',
        'Convention Center', 'Public Library'
    ];

    public function run(): void
    {
        // Create tags first
        $this->createTags();
        
        // Create events
        for ($i = 0; $i < $this->limit; $i++) {
            $this->createEvent($i);
        }
    }
    
    private function createTags(): void
    {
        $tagNames = [
            'networking', 'education', 'entertainment', 'food', 'music',
            'technology', 'health', 'fitness', 'art', 'culture',
            'business', 'startup', 'innovation', 'community', 'family',
            'outdoor', 'indoor', 'workshop', 'seminar', 'celebration',
            'competition', 'charity', 'fundraising', 'social', 'professional'
        ];
        
        foreach ($tagNames as $name) {
            Tag::firstOrCreate([
                'name' => $name
            ], [
                'slug' => \Str::slug($name),
                'usage_count' => rand(0, 50)
            ]);
        }
    }
    
    private function createEvent(int $index): void
    {
        $category = array_rand($this->eventTitles);
        $titles = $this->eventTitles[$category];
        $title = $titles[array_rand($titles)] . ' #' . ($index + 1);
        
        $startDate = Carbon::now()->addDays(rand(1, 60))->addHours(rand(9, 20));
        $endDate = $startDate->copy()->addHours(rand(2, 8));
        
        $isFree = rand(0, 1);
        $price = $isFree ? null : rand(10, 200);
        
        $event = Events::create([
            'user_id' => $this->userIds[array_rand($this->userIds)],
            'title' => $title,
            'slug' => \Str::slug($title),
            'description' => $this->eventDescriptions[array_rand($this->eventDescriptions)],
            'category' => $category,
            'visibility' => ['public', 'public', 'public', 'connections', 'private'][array_rand(['public', 'public', 'public', 'connections', 'private'])], // More public events
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'timezone' => ['UTC', 'America/New_York', 'Europe/London'][array_rand(['UTC', 'America/New_York', 'Europe/London'])],
            'location' => $this->locations[array_rand($this->locations)],
            'venue' => $this->venues[array_rand($this->venues)],
            'is_free' => $isFree,
            'price' => $price,
            'max_attendees' => rand(0, 1) ? rand(20, 500) : null,
            'current_attendees' => rand(0, 50),
            'image_url' => rand(0, 1) ? $this->imageUrls[array_rand($this->imageUrls)] : null,
            'status' => ['published', 'published', 'published', 'draft'][array_rand(['published', 'published', 'published', 'draft'])], // More published
        ]);
        
        // Attach random tags
        $this->attachRandomTags($event);
    }
    
    private function attachRandomTags(Events $event): void
    {
        $allTags = Tag::all();
        $numTags = rand(1, 5);
        $randomTags = $allTags->random($numTags);
        
        $tagIds = $randomTags->pluck('id')->toArray();
        $event->tags()->attach($tagIds);
        
        // Increment usage count
        Tag::whereIn('id', $tagIds)->increment('usage_count');
    }
}