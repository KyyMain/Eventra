<?php

namespace App\Helpers;

/**
 * Enhanced Security Helper
 * Provides additional security utilities for the application
 */

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input to prevent XSS and other attacks
     */
    function sanitize_input($input, $type = 'string')
    {
        if (is_array($input)) {
            return array_map(function($item) use ($type) {
                return sanitize_input($item, $type);
            }, $input);
        }

        switch ($type) {
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var(trim($input), FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'html':
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
            default:
                return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
        }
    }
}

if (!function_exists('generate_secure_token')) {
    /**
     * Generate a cryptographically secure random token
     */
    function generate_secure_token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('hash_password_secure')) {
    /**
     * Hash password with additional security measures
     */
    function hash_password_secure($password)
    {
        // Use Argon2ID if available, otherwise fallback to bcrypt
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID, [
                'memory_cost' => 65536, // 64 MB
                'time_cost' => 4,       // 4 iterations
                'threads' => 3,         // 3 threads
            ]);
        }
        
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }
}

if (!function_exists('verify_password_secure')) {
    /**
     * Verify password with timing attack protection
     */
    function verify_password_secure($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

if (!function_exists('rate_limit_check')) {
    /**
     * Check if request is within rate limits
     */
    function rate_limit_check($key, $limit = 60, $window = 60)
    {
        $cache = \Config\Services::cache();
        $attempts = $cache->get($key) ?? 0;
        
        if ($attempts >= $limit) {
            return false;
        }
        
        $cache->save($key, $attempts + 1, $window);
        return true;
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security-related events
     */
    function log_security_event($event, $details = [], $level = 'warning')
    {
        $logData = [
            'event' => $event,
            'ip' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'timestamp' => date('Y-m-d H:i:s'),
            'details' => $details
        ];
        
        log_message($level, 'Security Event: ' . json_encode($logData));
    }
}

if (!function_exists('validate_file_upload')) {
    /**
     * Validate file upload for security
     */
    function validate_file_upload($file, $allowedTypes = [], $maxSize = 2048)
    {
        if (!$file->isValid()) {
            return ['valid' => false, 'error' => 'File upload failed'];
        }
        
        // Check file size (in KB)
        if ($file->getSize() > ($maxSize * 1024)) {
            return ['valid' => false, 'error' => "File size exceeds {$maxSize}KB limit"];
        }
        
        // Check file type
        if (!empty($allowedTypes) && !in_array($file->getMimeType(), $allowedTypes)) {
            return ['valid' => false, 'error' => 'File type not allowed'];
        }
        
        // Check for malicious content
        $content = file_get_contents($file->getTempName());
        if (preg_match('/<\?php|<script|javascript:/i', $content)) {
            return ['valid' => false, 'error' => 'Malicious content detected'];
        }
        
        return ['valid' => true, 'error' => null];
    }
}