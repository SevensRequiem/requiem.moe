<?php

namespace App\Cache\Profiles;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class AnimeProfile implements CacheProfile
{
    public function shouldCacheRequest(Request $request): bool
    {
        $ignoredUrls = [
            env('APP_URL') . '/fetchanime', 
            env('APP_URL') . '/fetchwaifus', 
            env('APP_URL') . '/fetchfavs'
        ];

        if (in_array($request->fullUrl(), $ignoredUrls)) {
            return false;
        }

        return $request->isMethod('get');
    }

    public function shouldCacheResponse(Response $response): bool
    {
        return $response->isSuccessful() || $response->isRedirection();
    }

    public function cacheNameSuffix(Request $request): string
    {
        return $request->url();
    }

    public function cacheRequestUntil(Request $request): DateTime
    {
        return new DateTime('tomorrow');
    }

    public function enabled(Request $request): bool
    {
        // Determine if the cache is enabled.
        // This example enables the cache for all requests.
        return true;
    }

    public function useCacheNameSuffix(Request $request): string
    {
        // Return a string to be used as the cache name suffix.
        // This example uses the request URL.
        return $request->url();
    }
}