<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadService
{
    private const B2_KEY_ID = 'ba7aff8597d8';
    private const B2_APPLICATION_KEY = '0057f966ebf3298b2de7a360957999f6c8d4adcf12';
    private const B2_BUCKET_NAME = 'westernkits';
    private const B2_BUCKET_ID = '9b1a07ba7f8fe85599870d18';
    private const B2_API_URL = 'https://api.backblazeb2.com';

    private const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB chunks
    private const SIMPLE_UPLOAD_LIMIT = 100 * 1024 * 1024; // 100MB
    private const MAX_RETRIES = 3;

    private $authData = null;

    public function uploadFile(UploadedFile $file, string $directory, ?int $userId = null): array
    {
        $this->validateFile($file);

        $fileName = $this->generateFileName($file);
        $filePath = $this->buildFilePath($directory, $fileName, $userId);

        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();

        try {
            if ($fileSize <= self::SIMPLE_UPLOAD_LIMIT) {
                $result = $this->simpleUpload($tempPath, $filePath);
            } else {
                $result = $this->largeFileUpload($tempPath, $filePath);
            }

            return [
                'success' => true,
                'url' => $this->getPublicUrl($filePath),
                'path' => $filePath,
                'size' => $fileSize,
                'type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ];

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'file' => $fileName,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('Upload failed: ' . $e->getMessage());
        }
    }

    public function deleteFile(string $filePath): bool
    {
        try {
            $auth = $this->getAuthToken();

            // List file to get fileId
            $listResponse = Http::withHeaders([
                'Authorization' => $auth['authorizationToken']
            ])->post($auth['apiUrl'] . '/b2api/v2/b2_list_file_names', [
                'bucketId' => self::B2_BUCKET_ID,
                'prefix' => $filePath,
                'maxFileCount' => 1
            ]);

            if (!$listResponse->successful()) {
                return false;
            }

            $files = $listResponse->json()['files'] ?? [];
            if (empty($files)) {
                return false;
            }

            $fileId = $files[0]['fileId'];

            $deleteResponse = Http::withHeaders([
                'Authorization' => $auth['authorizationToken']
            ])->post($auth['apiUrl'] . '/b2api/v2/b2_delete_file_version', [
                'fileId' => $fileId,
                'fileName' => $filePath
            ]);

            return $deleteResponse->successful();

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $filePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getPublicUrl(string $filePath): string
    {
        $auth = $this->getAuthToken();
        $downloadUrl = $auth['downloadUrl'] ?? 'https://f005.backblazeb2.com';
        return $downloadUrl . "/file/" . self::B2_BUCKET_NAME . "/" . $filePath;
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        $maxSize = 500 * 1024 * 1024; // 500MB limit
        if ($file->getSize() > $maxSize) {
            throw new \Exception('File too large. Maximum size is 500MB');
        }

        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/mpeg', 'video/quicktime',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain', 'application/zip'
        ];

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \Exception('File type not allowed');
        }
    }

    private function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$timestamp}_{$random}.{$extension}";
    }

    private function buildFilePath(string $directory, string $fileName, ?int $userId = null): string
    {
        $path = trim($directory, '/');

        if ($userId) {
            $path .= "/{$userId}";
        }

        return "{$path}/{$fileName}";
    }

    private function getAuthToken(): array
    {
        if ($this->authData && time() < $this->authData['expires']) {
            return $this->authData;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(self::B2_KEY_ID . ':' . self::B2_APPLICATION_KEY)
        ])->timeout(30)->get(self::B2_API_URL . '/b2api/v2/b2_authorize_account');

        if (!$response->successful()) {
            throw new \Exception('B2 authorization failed');
        }

        $data = $response->json();
        $this->authData = [
            'authorizationToken' => $data['authorizationToken'],
            'apiUrl' => $data['apiUrl'],
            'expires' => time() + 3600
        ];

        return $this->authData;
    }

    private function getUploadUrl(): array
    {
        $auth = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => $auth['authorizationToken']
        ])->timeout(30)->post($auth['apiUrl'] . '/b2api/v2/b2_get_upload_url', [
            'bucketId' => self::B2_BUCKET_ID
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to get upload URL');
        }

        return $response->json();
    }

    private function simpleUpload(string $localPath, string $remotePath): bool
    {
        $uploadAuth = $this->getUploadUrl();

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                $fileContent = file_get_contents($localPath);
                if ($fileContent === false) {
                    throw new \Exception('Cannot read file');
                }

                $response = Http::withHeaders([
                    'Authorization' => $uploadAuth['authorizationToken'],
                    'X-Bz-File-Name' => urlencode($remotePath),
                    'Content-Type' => 'application/octet-stream',
                    'X-Bz-Content-Sha1' => sha1($fileContent)
                ])->timeout(900)->withBody($fileContent, 'application/octet-stream')
                  ->post($uploadAuth['uploadUrl']);

                if ($response->successful()) {
                    return true;
                }

                throw new \Exception('Upload failed: ' . $response->body());

            } catch (\Exception $e) {
                if ($attempt >= self::MAX_RETRIES) {
                    throw $e;
                }
                sleep($attempt * 2);
            }
        }

        return false;
    }

    private function largeFileUpload(string $localPath, string $remotePath): bool
    {
        $fileId = $this->startLargeFile($remotePath);
        $fileHandle = fopen($localPath, 'rb');

        if (!$fileHandle) {
            throw new \Exception('Cannot open file');
        }

        $partNumber = 1;
        $parts = [];

        try {
            while (!feof($fileHandle)) {
                $chunk = fread($fileHandle, self::CHUNK_SIZE);
                if (empty($chunk)) break;

                $partSha1 = $this->uploadPart($fileId, $partNumber, $chunk);
                $parts[] = [
                    'partNumber' => $partNumber,
                    'partSha1' => $partSha1
                ];

                $partNumber++;
            }

            $this->finishLargeFile($fileId, $parts);
            return true;

        } finally {
            if (is_resource($fileHandle)) {
                fclose($fileHandle);
            }
        }
    }

    private function startLargeFile(string $fileName): string
    {
        $auth = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => $auth['authorizationToken']
        ])->timeout(30)->post($auth['apiUrl'] . '/b2api/v2/b2_start_large_file', [
            'bucketId' => self::B2_BUCKET_ID,
            'fileName' => $fileName,
            'contentType' => 'application/octet-stream'
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to start large file');
        }

        return $response->json()['fileId'];
    }

    private function uploadPart(string $fileId, int $partNumber, string $data): string
    {
        $auth = $this->getAuthToken();

        $urlResponse = Http::withHeaders([
            'Authorization' => $auth['authorizationToken']
        ])->timeout(30)->post($auth['apiUrl'] . '/b2api/v2/b2_get_upload_part_url', [
            'fileId' => $fileId
        ]);

        if (!$urlResponse->successful()) {
            throw new \Exception('Failed to get upload part URL');
        }

        $urlData = $urlResponse->json();
        $partSha1 = sha1($data);

        $response = Http::withHeaders([
            'Authorization' => $urlData['authorizationToken'],
            'X-Bz-Part-Number' => (string)$partNumber,
            'Content-Length' => (string)strlen($data),
            'X-Bz-Content-Sha1' => $partSha1
        ])->timeout(300)->withBody($data, 'application/octet-stream')
          ->post($urlData['uploadUrl']);

        if (!$response->successful()) {
            throw new \Exception("Failed to upload part {$partNumber}");
        }

        return $partSha1;
    }

    private function finishLargeFile(string $fileId, array $parts): void
    {
        $auth = $this->getAuthToken();

        $partSha1Array = array_map(fn($part) => $part['partSha1'], $parts);

        $response = Http::withHeaders([
            'Authorization' => $auth['authorizationToken']
        ])->timeout(60)->post($auth['apiUrl'] . '/b2api/v2/b2_finish_large_file', [
            'fileId' => $fileId,
            'partSha1Array' => $partSha1Array
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to finish large file');
        }
    }


  

public function replaceFile(UploadedFile $newFile, string $directory, ?string $oldFilePath = null, ?int $userId = null): array
{
    try {
        Log::info('Replace file called', [
            'new_file' => $newFile->getClientOriginalName(),
            'old_path' => $oldFilePath
        ]);
        
        // Upload new file first
        $result = $this->uploadFile($newFile, $directory, $userId);
        
        // Only delete old file after successful upload
        if ($oldFilePath && $result['success']) {
            // Extract just the path part if it's a full URL
            $pathToDelete = $oldFilePath;
            if (str_contains($oldFilePath, 'file/' . self::B2_BUCKET_NAME . '/')) {
                $pathToDelete = explode('file/' . self::B2_BUCKET_NAME . '/', $oldFilePath)[1];
            }
            
            $deleted = $this->deleteFile($pathToDelete);
            
            if ($deleted) {
                Log::info('Old file deleted successfully', ['path' => $pathToDelete]);
            } else {
                Log::warning('Old file deletion failed', ['path' => $pathToDelete]);
            }
        }
        
        Log::info('File replacement completed successfully');
        return $result;
        
    } catch (\Exception $e) {
        Log::error('File replacement failed', [
            'old_path' => $oldFilePath,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}
}
