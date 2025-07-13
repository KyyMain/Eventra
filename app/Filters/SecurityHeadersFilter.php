<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nothing to do before request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Security headers for all responses
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'DENY');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Environment-specific Content Security Policy
        if (ENVIRONMENT === 'development') {
            // More lenient CSP for development
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:",
                "style-src 'self' 'unsafe-inline' https: http:",
                "font-src 'self' https: http: data:",
                "img-src 'self' data: https: http:",
                "connect-src 'self' https: http:",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'"
            ];
        } else {
            // Stricter CSP for production
            $csp = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.tailwindcss.com",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://cdn.tailwindcss.com",
                "font-src 'self' https://fonts.gstatic.com https://fonts.googleapis.com data:",
                "img-src 'self' data: https:",
                "connect-src 'self'",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'"
            ];
        }
        
        $response->setHeader('Content-Security-Policy', implode('; ', $csp));
        
        // HSTS for HTTPS (only in production)
        if (ENVIRONMENT === 'production' && $request->isSecure()) {
            $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;
    }
}