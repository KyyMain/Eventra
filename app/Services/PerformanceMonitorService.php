<?php

namespace App\Services;

use App\Libraries\ErrorHandler;

class PerformanceMonitorService
{
    private ErrorHandler $errorHandler;
    private array $timers = [];
    private array $metrics = [];
    
    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }
    
    /**
     * Start timing an operation
     */
    public function startTimer(string $operation): void
    {
        $this->timers[$operation] = [
            'start' => microtime(true),
            'memory_start' => memory_get_usage(true)
        ];
    }
    
    /**
     * End timing an operation and log if slow
     */
    public function endTimer(string $operation, array $metadata = []): float
    {
        if (!isset($this->timers[$operation])) {
            return 0.0;
        }
        
        $timer = $this->timers[$operation];
        $duration = microtime(true) - $timer['start'];
        $memoryUsed = memory_get_usage(true) - $timer['memory_start'];
        
        $metadata['memory_used'] = $memoryUsed;
        
        // Log performance metrics
        $this->errorHandler->logPerformanceMetric($operation, $duration, $metadata);
        
        // Store for analysis
        $this->metrics[$operation] = [
            'duration' => $duration,
            'memory_used' => $memoryUsed,
            'metadata' => $metadata,
            'timestamp' => time()
        ];
        
        unset($this->timers[$operation]);
        
        return $duration;
    }
    
    /**
     * Monitor database query performance
     */
    public function monitorQuery(string $query, callable $callback, array $params = [])
    {
        $queryHash = md5($query);
        $this->startTimer("db_query_{$queryHash}");
        
        try {
            $result = $callback();
            
            $this->endTimer("db_query_{$queryHash}", [
                'query' => $this->sanitizeQuery($query),
                'params_count' => count($params),
                'type' => $this->getQueryType($query)
            ]);
            
            return $result;
        } catch (\Throwable $e) {
            $this->endTimer("db_query_{$queryHash}", [
                'query' => $this->sanitizeQuery($query),
                'params_count' => count($params),
                'type' => $this->getQueryType($query),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Monitor cache operations
     */
    public function monitorCache(string $operation, string $key, callable $callback)
    {
        $this->startTimer("cache_{$operation}");
        
        try {
            $result = $callback();
            
            $this->endTimer("cache_{$operation}", [
                'key' => $key,
                'hit' => $result !== null
            ]);
            
            return $result;
        } catch (\Throwable $e) {
            $this->endTimer("cache_{$operation}", [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Get performance summary
     */
    public function getPerformanceSummary(): array
    {
        $summary = [
            'total_operations' => count($this->metrics),
            'slow_operations' => 0,
            'average_duration' => 0,
            'total_memory_used' => 0,
            'operations' => []
        ];
        
        if (empty($this->metrics)) {
            return $summary;
        }
        
        $totalDuration = 0;
        
        foreach ($this->metrics as $operation => $data) {
            $totalDuration += $data['duration'];
            $summary['total_memory_used'] += $data['memory_used'];
            
            if ($data['duration'] > 1.0) {
                $summary['slow_operations']++;
            }
            
            $summary['operations'][$operation] = [
                'duration' => round($data['duration'], 4),
                'memory_used' => $data['memory_used'],
                'is_slow' => $data['duration'] > 1.0
            ];
        }
        
        $summary['average_duration'] = round($totalDuration / count($this->metrics), 4);
        
        return $summary;
    }
    
    /**
     * Clear metrics (useful for long-running processes)
     */
    public function clearMetrics(): void
    {
        $this->metrics = [];
        $this->timers = [];
    }
    
    /**
     * Sanitize SQL query for logging
     */
    private function sanitizeQuery(string $query): string
    {
        // Remove potential sensitive data from queries
        $query = preg_replace('/\b(password|token|secret|key)\s*=\s*[\'"][^\'"]*[\'"]/i', '$1 = ***', $query);
        
        // Limit query length for logging
        if (strlen($query) > 500) {
            $query = substr($query, 0, 500) . '... [TRUNCATED]';
        }
        
        return $query;
    }
    
    /**
     * Determine query type for categorization
     */
    private function getQueryType(string $query): string
    {
        $query = trim(strtoupper($query));
        
        if (strpos($query, 'SELECT') === 0) return 'SELECT';
        if (strpos($query, 'INSERT') === 0) return 'INSERT';
        if (strpos($query, 'UPDATE') === 0) return 'UPDATE';
        if (strpos($query, 'DELETE') === 0) return 'DELETE';
        if (strpos($query, 'CREATE') === 0) return 'CREATE';
        if (strpos($query, 'ALTER') === 0) return 'ALTER';
        if (strpos($query, 'DROP') === 0) return 'DROP';
        
        return 'OTHER';
    }
}