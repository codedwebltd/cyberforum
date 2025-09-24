<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlockScrapersMiddleware
{
    private $blockedUserAgents = [
        'webcopy',
        'httrack',
        'wget',
        'curl',
        'scrapy',
        'python-requests',
        'crawler',
        'spider',
        'bot',
        'scraper',
        'offline explorer',
        'website downloader',
        'teleport',
        'webzip',
        'sitesnagger',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Log User-Agent and IP for every request
    Log::info('Incoming request details', [
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'path' => $request->path(),
    ]);

        $userAgent = strtolower($request->header('User-Agent', ''));

        // Block empty user agents (common scraper behavior)
        if (empty($userAgent)) {
            Log::warning('Blocked request with empty User-Agent', ['ip' => $request->ip()]);
            return response('Access denied', 403);
        }

        foreach ($this->blockedUserAgents as $blocked) {
            if (strpos($userAgent, $blocked) !== false) {
                Log::warning('Blocked scraper detected', ['user_agent' => $userAgent, 'ip' => $request->ip()]);
                return response()->view('errors.blocked-scraper', [], 403);
            }
        }

        return $next($request);
    }
}
