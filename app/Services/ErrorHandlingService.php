<?php

namespace App\Services;

/**
 * Error Handling and Logging Service
 * Provides centralized error handling and logging functionality
 */
class ErrorHandlingService
{
    private $logger;
    private $config;

    public function __construct()
    {
        $this->logger = \Config\Services::logger();
        $this->config = config('App');
    }

    /**
     * Handle application errors with proper logging and user feedback
     */
    public function handleError(\Throwable $exception, $context = [])
    {
        // Generate unique error ID for tracking
        $errorId = uniqid('ERR_', true);
        
        // Prepare error data
        $errorData = [
            'error_id' => $errorId,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'request_uri' => service('request')->getUri()->getPath(),
            'method' => service('request')->getMethod(),
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => session()->get('user_id') ?? 'guest'
        ];

        // Log the error
        $this->logger->error('Application Error: ' . json_encode($errorData));

        // Return user-friendly error response
        return $this->formatErrorResponse($exception, $errorId);
    }

    /**
     * Format error response based on request type
     */
    private function formatErrorResponse(\Throwable $exception, string $errorId)
    {
        $request = service('request');
        
        if ($request->isAJAX()) {
            return service('response')
                ->setStatusCode(500)
                ->setJSON([
                    'error' => true,
                    'message' => $this->getUserFriendlyMessage($exception),
                    'error_id' => $errorId,
                    'timestamp' => date('c')
                ]);
        }

        // For web requests, redirect with error message
        $message = $this->getUserFriendlyMessage($exception);
        return redirect()->back()->with('error', $message . " (Error ID: {$errorId})");
    }

    /**
     * Get user-friendly error message
     */
    private function getUserFriendlyMessage(\Throwable $exception): string
    {
        // In production, don't expose sensitive error details
        if (ENVIRONMENT === 'production') {
            switch (get_class($exception)) {
                case 'CodeIgniter\Database\Exceptions\DatabaseException':
                    return 'Terjadi kesalahan pada database. Silakan coba lagi nanti.';
                case 'CodeIgniter\View\Exceptions\ViewException':
                    return 'Terjadi kesalahan saat memuat halaman. Silakan coba lagi.';
                case 'CodeIgniter\Security\Exceptions\SecurityException':
                    return 'Terjadi kesalahan keamanan. Silakan refresh halaman dan coba lagi.';
                default:
                    return 'Terjadi kesalahan sistem. Silakan coba lagi nanti.';
            }
        }

        // In development, show actual error message
        return $exception->getMessage();
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, array $details = [], string $level = 'warning')
    {
        $securityData = [
            'event_type' => 'security',
            'event' => $event,
            'details' => $details,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'request_uri' => service('request')->getUri()->getPath(),
            'user_id' => session()->get('user_id') ?? 'guest',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->log($level, 'Security Event: ' . json_encode($securityData));
    }

    /**
     * Log performance metrics
     */
    public function logPerformanceMetric(string $operation, float $duration, array $context = [])
    {
        $performanceData = [
            'event_type' => 'performance',
            'operation' => $operation,
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('Performance Metric: ' . json_encode($performanceData));
    }

    /**
     * Handle validation errors
     */
    public function handleValidationErrors(array $errors, string $context = '')
    {
        $validationData = [
            'event_type' => 'validation',
            'context' => $context,
            'errors' => $errors,
            'request_data' => service('request')->getPost(),
            'ip_address' => service('request')->getIPAddress(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('Validation Error: ' . json_encode($validationData));

        if (service('request')->isAJAX()) {
            return service('response')
                ->setStatusCode(422)
                ->setJSON([
                    'error' => true,
                    'message' => 'Data yang dikirim tidak valid',
                    'errors' => $errors
                ]);
        }

        return redirect()->back()->withInput()->with('errors', $errors);
    }
}