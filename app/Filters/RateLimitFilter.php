<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimitFilter implements FilterInterface
{
    private $limits = [
        'api' => ['requests' => 100, 'window' => 3600], // 100 requests per hour for API
        'auth' => ['requests' => 5, 'window' => 300],   // 5 requests per 5 minutes for auth
        'default' => ['requests' => 60, 'window' => 60] // 60 requests per minute for general
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $cache = \Config\Services::cache();
        $ip = $request->getIPAddress();
        $uri = $request->getUri()->getPath();
        
        // Sanitize IP address for cache key (remove reserved characters)
        $sanitizedIp = $this->sanitizeIpForCacheKey($ip);
        
        // Determine rate limit type based on URI
        $limitType = $this->getLimitType($uri);
        $limit = $this->limits[$limitType];
        
        $key = "rate_limit_{$limitType}_{$sanitizedIp}";
        $attempts = $cache->get($key) ?? 0;
        
        // Check if rate limit exceeded
        if ($attempts >= $limit['requests']) {
            log_message('warning', "Rate limit exceeded for IP: {$ip}, URI: {$uri}");
            
            return service('response')
                ->setStatusCode(429)
                ->setHeader('X-RateLimit-Limit', $limit['requests'])
                ->setHeader('X-RateLimit-Remaining', 0)
                ->setHeader('X-RateLimit-Reset', time() + $limit['window'])
                ->setJSON([
                    'error' => 'Rate limit exceeded',
                    'message' => "Too many requests. Limit: {$limit['requests']} per {$limit['window']} seconds",
                    'retry_after' => $limit['window']
                ]);
        }
        
        // Increment counter with TTL
        $cache->save($key, $attempts + 1, $limit['window']);
        
        // Add rate limit headers to response
        $remaining = max(0, $limit['requests'] - $attempts - 1);
        service('response')
            ->setHeader('X-RateLimit-Limit', $limit['requests'])
            ->setHeader('X-RateLimit-Remaining', $remaining)
            ->setHeader('X-RateLimit-Reset', time() + $limit['window']);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here
    }
    
    private function getLimitType(string $uri): string
    {
        if (strpos($uri, '/api/') === 0) {
            return 'api';
        }
        
        if (in_array($uri, ['/auth/login', '/auth/register', '/auth/forgot-password'])) {
            return 'auth';
        }
        
        return 'default';
    }
    
    /**
     * Sanitize IP address for use as cache key
     * Removes or replaces reserved characters: {}()/\@:
     */
    private function sanitizeIpForCacheKey(string $ip): string
    {
        // Replace reserved characters with safe alternatives
        $sanitized = str_replace(['{', '}', '(', ')', '/', '\\', '@', ':'], 
                                ['_', '_', '_', '_', '_', '_', '_', '_'], 
                                $ip);
        
        // Additional cleanup for common cases
        $sanitized = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $sanitized);
        
        return $sanitized;
    }
}