<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    private $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        return view('home.media.index');
    }

    public function getUserMedia(Request $request)
    {
        $userId = auth()->id();
        $type = $request->get('type');
        $page = $request->get('page', 1);
        $maxFiles = $request->get('limit', 50);
        
        $cacheKey = "user_media_{$userId}" . ($type ? "_{$type}" : '') . "_page_{$page}";
        
        $media = Cache::remember($cacheKey, 600, function () use ($userId, $type, $maxFiles) {
            return $this->fileUploadService->listUserMedia($userId, $type, $maxFiles);
        });

        // Calculate stats for the header
        $stats = $this->calculateMediaStats($media);

        return response()->json([
            'success' => true,
            'media' => $media,
            'stats' => $stats,
            'count' => count($media)
        ]);
    }

public function upload(Request $request)
{
    $userId = auth()->id();
    $user = auth()->user();
    
    try {
        // Basic validation first
        $request->validate([
            'files.*' => 'required|file|max:204800', // 200MB max per file
            'directory' => 'string|nullable'
        ]);

        // Calculate current storage usage
        $allMedia = $this->fileUploadService->listUserMedia($userId, null, 1000);
        $currentUsage = 0;
        foreach ($allMedia as $file) {
            $currentUsage += $file['size'] ?? 0;
        }
        
        // Calculate size of incoming files
        $incomingSize = 0;
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $incomingSize += $file->getSize();
            }
        }
        
        // Check storage limit
        $storageLimit = ($user->capped_file_size ?? 1024) * 1024 * 1024; // Convert MB to bytes
        $totalAfterUpload = $currentUsage + $incomingSize;
        
        if ($totalAfterUpload > $storageLimit) {
            $exceededBy = $this->formatFileSize($totalAfterUpload - $storageLimit);
            $currentFormatted = $this->formatFileSize($currentUsage);
            $limitFormatted = $this->formatFileSize($storageLimit);
            
            return response()->json([
                'success' => false,
                'message' => "Upload would exceed your storage limit by {$exceededBy}. Current usage: {$currentFormatted} / {$limitFormatted}. Please upgrade your storage to continue uploading files.",
                'storage_exceeded' => true,
                'current_usage' => $currentUsage,
                'storage_limit' => $storageLimit,
                'attempted_upload' => $incomingSize
            ], 413);
        }

        $directory = $request->get('directory', 'social/uploads');
        $uploadedFiles = [];
        $errors = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $result = $this->fileUploadService->uploadFile(
                        $file,
                        $directory,
                        $userId
                    );
                    $uploadedFiles[] = $result;
                } catch (\Exception $e) {
                    $errors[] = [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }
        }

        // Clear relevant cache
        $this->clearUserMediaCache($userId);

        return response()->json([
            'success' => true,
            'uploaded' => $uploadedFiles,
            'errors' => $errors,
            'count' => count($uploadedFiles),
            'message' => count($uploadedFiles) . ' files uploaded successfully'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all()),
            'validation_errors' => $e->validator->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Media upload failed', [
            'user_id' => $userId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
}

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $filePath = $request->get('path');
        $userId = auth()->id();

        try {
            // Extract path from URL if needed
            if (str_contains($filePath, 'file/' . config('filesystems.disks.b2.bucket') . '/')) {
                $filePath = explode('file/' . config('filesystems.disks.b2.bucket') . '/', $filePath)[1];
            }

            // Basic security check - ensure user can only delete their own files
            if (!str_contains($filePath, "/{$userId}/")) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $deleted = $this->fileUploadService->deleteFile($filePath);

            if ($deleted) {
                $this->clearUserMediaCache($userId);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found or could not be deleted'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Media deletion failed', [
                'user_id' => $userId,
                'path' => $filePath,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $userId = auth()->id();
        $query = $request->get('q', '');
        $type = $request->get('type');
        
        try {
            // Get all user media first
            $allMedia = $this->fileUploadService->listUserMedia($userId, $type, 100);
            
            // Filter by search query
            $filteredMedia = collect($allMedia)->filter(function ($file) use ($query) {
                return str_contains(strtolower($file['name']), strtolower($query));
            })->values()->all();

            return response()->json([
                'success' => true,
                'media' => $filteredMedia,
                'count' => count($filteredMedia)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }

    public function getFileInfo(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $filePath = $request->get('path');
        $userId = auth()->id();

        // Security check
        if (!str_contains($filePath, "/{$userId}/")) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $url = $this->fileUploadService->getPublicUrl($filePath);
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $filePath
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }
    }

    private function calculateMediaStats(array $media): array
    {
        $stats = [
            'images' => 0,
            'videos' => 0,
            'documents' => 0,
            'totalSize' => 0
        ];

        foreach ($media as $file) {
            $stats['totalSize'] += $file['size'] ?? 0;
            
            switch ($file['category']) {
                case 'images':
                    $stats['images']++;
                    break;
                case 'videos':
                    $stats['videos']++;
                    break;
                case 'docs':
                    $stats['documents']++;
                    break;
            }
        }

        // Format total size
        $stats['formattedSize'] = $this->formatFileSize($stats['totalSize']);

        return $stats;
    }

private function formatFileSize(int $bytes): string
{
    if ($bytes == 0) return '0 B';
    
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $power = floor(log($bytes, 1024));
    $size = $bytes / pow(1024, $power);
    
    // Smart decimal formatting
    if ($power == 0) {
        // Bytes: no decimals
        return round($size) . ' ' . $units[$power];
    } elseif ($power == 1) {
        // KB: 1 decimal if under 10, none if over
        $decimals = $size < 10 ? 1 : 0;
    } elseif ($power >= 2) {
        // MB/GB/TB: 2 decimals if under 10, 1 decimal if under 100, none if over
        if ($size < 10) {
            $decimals = 2;
        } elseif ($size < 100) {
            $decimals = 1;
        } else {
            $decimals = 0;
        }
    }
    
    return round($size, $decimals) . ' ' . $units[$power];
}

    private function clearUserMediaCache(int $userId): void
    {
        $types = ['', '_images', '_videos', '_docs'];
        foreach ($types as $type) {
            // Clear multiple pages
            for ($page = 1; $page <= 5; $page++) {
                Cache::forget("user_media_{$userId}{$type}_page_{$page}");
            }
            Cache::forget("user_media_{$userId}{$type}");
        }
    }


    public function getStorageInfo(Request $request)
{
    $userId = auth()->id();
    $user = auth()->user();
    
    // Get user's capped file size (default 1GB if not set)
    $cappedSizeBytes = ($user->capped_file_size ?? 1024) * 1024 * 1024; // Convert MB to bytes
    
    try {
        // Get all user media to calculate total usage
        $allMedia = $this->fileUploadService->listUserMedia($userId, null, 1000);
        
        $totalUsedBytes = 0;
        foreach ($allMedia as $file) {
            $totalUsedBytes += $file['size'] ?? 0;
        }
        
        $usagePercentage = $cappedSizeBytes > 0 ? ($totalUsedBytes / $cappedSizeBytes) * 100 : 0;
        $remainingBytes = max(0, $cappedSizeBytes - $totalUsedBytes);
        $isOverLimit = $totalUsedBytes >= $cappedSizeBytes;
        
        return response()->json([
            'success' => true,
            'storage' => [
                'used_bytes' => $totalUsedBytes,
                'used_formatted' => $this->formatFileSize($totalUsedBytes),
                'total_bytes' => $cappedSizeBytes,
                'total_formatted' => $this->formatFileSize($cappedSizeBytes),
                'remaining_bytes' => $remainingBytes,
                'remaining_formatted' => $this->formatFileSize($remainingBytes),
                'usage_percentage' => round($usagePercentage, 1),
                'is_over_limit' => $isOverLimit,
                'can_upload' => !$isOverLimit
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Could not retrieve storage information'
        ], 500);
    }
}


// 2. Add this method to your MediaController
public function debugB2Response()
{
    $userId = auth()->id();
    
    try {
        $debugData = $this->fileUploadService->debugListUserMedia($userId, null, 5);
        
        // Also dump to browser for immediate viewing
        dd([
            'user_id' => $userId,
            'debug_files' => $debugData,
            'check_logs' => 'Also check storage/logs/laravel.log for detailed logs'
        ]);
        
    } catch (\Exception $e) {
        dd([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}
}