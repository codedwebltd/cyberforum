<?php

namespace App\Http\Controllers\Discussion;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DiscussionActionController extends Controller
{
    public function create()
    {
     

        return view('home.discussions.create');
    }


    public function getUserMedia(Request $request)
{
    $userId = auth()->id();
    $type = $request->get('type');    
    
    $cacheKey = "user_media_{$userId}" . ($type ? "_{$type}" : '');
    
    $media = Cache::remember($cacheKey, 800, function () use ($userId, $type) {
        $fileService = app(FileUploadService::class);
        return $fileService->listUserMedia($userId, $type);
    });

    return response()->json([
        'success' => true,
        'media' => $media,
        'count' => count($media)
    ]);
}

public function uploadImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:102400'
    ]);
    

    // Before the try block:
    $storageCheck = $this->checkStorageLimit($request->file('files') ?? []);
    if (!$storageCheck['can_upload']) {
        return response()->json([
            'success' => false,
            'message' => 'Upload would exceed your storage limit. Kindly upgrade your storage limit or delete some files from your media.',
            'storage_exceeded' => true
        ], 413);
    }

    
    try {
        $fileService = app(FileUploadService::class);
        $result = $fileService->uploadFile(
            $request->file('image'),
            'social/discussion-images',
            auth()->id()
        );

        // Clear media cache for this user
        Cache::forget("user_media_" . auth()->id());
        Cache::forget("user_media_" . auth()->id() . "_images");

        return response()->json([
            'success' => true,
            'url' => $result['url'],
            'path' => $result['path']
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}


public function store(Request $request)
{
    
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'excerpt' => 'nullable|string|max:300',
        'type' => 'required|in:discussion,question,announcement',
        'status' => 'required|in:published,draft',
        'tags' => 'nullable|string',
        'allow_comments' => 'boolean',
        'is_pinned' => 'boolean',
        'is_featured' => 'boolean',
        'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:102400',
        'attachments.*' => 'nullable|file|max:102400' // 50MB max per file
    ]);

    // Before file uploads, add this after validation:
        $filesToCheck = [];
        if ($request->hasFile('featured_image')) {
            $filesToCheck[] = $request->file('featured_image');
        }
        if ($request->hasFile('attachments')) {
            $filesToCheck = array_merge($filesToCheck, $request->file('attachments'));
        }

        $storageCheck = $this->checkStorageLimit($filesToCheck);
        if (!$storageCheck['can_upload']) {
            return back()->withInput()->with('error', 
                'Upload would exceed your storage limit. Please upgrade your storage or delete some files from your media'
            );
        }
    try {
        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt ?: Str::limit(strip_tags($request->content), 300);
        $post->type = $request->type;
        $post->status = $request->status;
        $post->allow_comments = $request->boolean('allow_comments', true);
        $post->is_pinned = $request->boolean('is_pinned', false);
        $post->is_featured = $request->boolean('is_featured', false);
        
        if ($request->status === 'published') {
            $post->published_at = now();
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $fileService = app(FileUploadService::class);
            $result = $fileService->uploadFile(
                $request->file('featured_image'),
                'social/discussion-images',
                auth()->id()
            );
            $post->featured_image_url = $result['url'];
        }

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments') && is_array($request->file('attachments'))) {
            $fileService = app(FileUploadService::class);
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $result = $fileService->uploadFile(
                        $file,
                        'social/discussion-attachments',
                        auth()->id()
                    );
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'url' => $result['url'],
                        'size' => $result['size'],
                        'type' => $result['type']
                    ];
                }
            }
        }
        $post->attachments = $attachments;

        $post->save();

        // Handle tags
        if ($request->tags) {
            $tagNames = json_decode($request->tags, true) ?: [];
            if (!empty($tagNames)) {
                $post->syncTags($tagNames);
            }
        }

        // Clear media cache
        Cache::forget("user_media_" . auth()->id());
        Cache::forget("user_media_" . auth()->id() . "_images");

        return redirect()->route('discussion.show', $post->slug)
            ->with('success', 'Discussion created successfully!');

    } catch (\Exception $e) {
        Log::error('Discussion creation failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage()
        ]);

        // return back()->withInput()
        //     ->with('error', 'Failed to create discussion. Please try again.');
    }
}

private function checkStorageLimit(array $incomingFiles = []): array
{
    $userId = auth()->id();
    $user = auth()->user();
    
    // Get current storage usage
    $fileService = app(FileUploadService::class);
    $allMedia = $fileService->listUserMedia($userId, null, 1000);
    $currentUsage = 0;
    foreach ($allMedia as $file) {
        $currentUsage += $file['size'] ?? 0;
    }
    
    // Calculate size of incoming files
    $incomingSize = 0;
    foreach ($incomingFiles as $file) {
        if ($file && $file->isValid()) {
            $incomingSize += $file->getSize();
        }
    }
    
    // Check storage limit
    $storageLimit = ($user->capped_file_size ?? 1024) * 1024 * 1024; // Convert MB to bytes
    $totalAfterUpload = $currentUsage + $incomingSize;
    
    return [
        'can_upload' => $totalAfterUpload <= $storageLimit,
        'current_usage' => $currentUsage,
        'storage_limit' => $storageLimit,
        'incoming_size' => $incomingSize,
        'total_after' => $totalAfterUpload,
        'exceeded_by' => max(0, $totalAfterUpload - $storageLimit)
    ];
}

}
