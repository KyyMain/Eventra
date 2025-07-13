<?php

namespace App\Services;

class CacheService
{
    protected $cache;
    
    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }
    
    /**
     * Cache user statistics for 5 minutes
     */
    public function getUserStats()
    {
        $cacheKey = 'user_stats';
        $stats = $this->cache->get($cacheKey);
        
        if ($stats === null) {
            $userModel = new \App\Models\UserModel();
            $stats = $userModel->getUserStats();
            $this->cache->save($cacheKey, $stats, 300); // 5 minutes
        }
        
        return $stats;
    }
    
    /**
     * Cache event statistics for 10 minutes
     */
    public function getEventStats()
    {
        $cacheKey = 'event_stats';
        $stats = $this->cache->get($cacheKey);
        
        if ($stats === null) {
            $eventModel = new \App\Models\EventModel();
            $stats = $eventModel->getEventStats();
            $this->cache->save($cacheKey, $stats, 600); // 10 minutes
        }
        
        return $stats;
    }
    
    /**
     * Clear all cached statistics
     */
    public function clearStatsCache()
    {
        $this->cache->delete('user_stats');
        $this->cache->delete('event_stats');
        $this->cache->delete('registration_stats');
    }
    
    /**
     * Cache frequently accessed data
     */
    public function remember($key, $callback, $ttl = 300)
    {
        $data = $this->cache->get($key);
        
        if ($data === null) {
            $data = $callback();
            $this->cache->save($key, $data, $ttl);
        }
        
        return $data;
    }
}