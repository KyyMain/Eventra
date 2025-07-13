<?php

namespace App\Libraries;

use CodeIgniter\Log\Logger;
use Throwable;

class ErrorHandler
{
    private Logger $logger;
    private array $sensitiveFields = [
        'password', 'token', 'secret', 'key', 'auth', 'credential'
    ];

    public function __construct()
    {
        $this->logger = service('logger');
    }

    /**
     * Handle and log exceptions with context
     */
    public function handleException(Throwable $exception, array $context = []): void
    {
        $errorData = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $this->sanitizeStackTrace($exception->getTraceAsString()),
            'context' => $this->sanitizeContext($context),
            'request_id' => $this->generateRequestId(),
            'timestamp' => date('Y-m-d H:i:s'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'ip_address' => $this->getClientIp()
        ];

        $this->logger->error('Exception occurred', $errorData);
        
        // In production, also log to external service
        if (ENVIRONMENT === 'production') {
            $this->logToExternalService($errorData);
        }
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, array $data = []): void
    {
        $securityData = [
            'event' => $event,
            'data' => $this->sanitizeContext($data),
            'ip_address' => $this->getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => $this->generateRequestId()
        ];

        $this->logger->warning("Security Event: {$event}", $securityData);
    }

    /**
     * Log performance metrics
     */
    public function logPerformanceMetric(string $operation, float $duration, array $metadata = []): void
    {
        if ($duration > 1.0) { // Only log slow operations
            $performanceData = [
                'operation' => $operation,
                'duration' => round($duration, 4),
                'metadata' => $metadata,
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true),
                'timestamp' => date('Y-m-d H:i:s')
            ];

            $this->logger->info("Performance: {$operation}", $performanceData);
        }
    }

    /**
     * Sanitize stack trace to remove sensitive information
     */
    private function sanitizeStackTrace(string $trace): string
    {
        // Remove file paths that might contain sensitive information
        $trace = preg_replace('/\/[^\/\s]*\/[^\/\s]*\//', '/***/', $trace);
        
        // Remove potential sensitive parameters
        foreach ($this->sensitiveFields as $field) {
            $trace = preg_replace("/'{$field}'[^,\)]*[,\)]/i", "'{$field}' => '***'", $trace);
        }
        
        return $trace;
    }

    /**
     * Sanitize context data to remove sensitive information
     */
    private function sanitizeContext(array $context): array
    {
        foreach ($context as $key => $value) {
            if (is_string($key)) {
                foreach ($this->sensitiveFields as $sensitiveField) {
                    if (stripos($key, $sensitiveField) !== false) {
                        $context[$key] = '***REDACTED***';
                        break;
                    }
                }
            }
            
            if (is_array($value)) {
                $context[$key] = $this->sanitizeContext($value);
            }
        }
        
        return $context;
    }

    /**
     * Generate unique request ID for tracking
     */
    private function generateRequestId(): string
    {
        return uniqid('req_', true);
    }

    /**
     * Get client IP address safely
     */
    private function getClientIp(): string
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Log to external service (placeholder for production)
     */
    private function logToExternalService(array $errorData): void
    {
        // Implement external logging service integration
        // Examples: Sentry, LogRocket, Bugsnag, etc.
        
        // For now, just ensure it doesn't break if service is unavailable
        try {
            // External service call would go here
        } catch (Throwable $e) {
            // Silently fail - don't let logging errors break the application
        }
    }
}