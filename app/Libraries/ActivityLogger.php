<?php

namespace App\Libraries;

use CodeIgniter\Log\Logger;

class ActivityLogger
{
    protected $logger;

    public function __construct()
    {
        $this->logger = service('logger');
    }

    /**
     * Log user activity
     */
    public function logUserActivity(int $userId, string $action, array $details = []): void
    {
        $logData = [
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('User Activity: ' . $action, $logData);
    }

    /**
     * Log system events
     */
    public function logSystemEvent(string $event, array $data = []): void
    {
        $logData = [
            'event' => $event,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('System Event: ' . $event, $logData);
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, array $details = []): void
    {
        $logData = [
            'security_event' => $event,
            'details' => $details,
            'ip_address' => service('request')->getIPAddress(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->warning('Security Event: ' . $event, $logData);
    }

    /**
     * Log performance metrics
     */
    public function logPerformance(string $operation, float $executionTime, array $metadata = []): void
    {
        $logData = [
            'operation' => $operation,
            'execution_time' => $executionTime,
            'metadata' => $metadata,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        if ($executionTime > 2.0) { // Log slow operations
            $this->logger->warning('Slow Operation: ' . $operation, $logData);
        } else {
            $this->logger->info('Performance: ' . $operation, $logData);
        }
    }
}