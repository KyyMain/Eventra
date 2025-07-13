<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupLogs extends BaseCommand
{
    protected $group       = 'maintenance';
    protected $name        = 'logs:cleanup';
    protected $description = 'Clean up old log files to save disk space';
    
    public function run(array $params)
    {
        $days = $params[0] ?? 30; // Default to 30 days
        
        CLI::write("Cleaning up log files older than {$days} days...", 'yellow');
        
        $logPath = WRITEPATH . 'logs/';
        $files = glob($logPath . 'log-*.log');
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $fileTime = filemtime($file);
            $cutoffTime = time() - ($days * 24 * 60 * 60);
            
            if ($fileTime < $cutoffTime) {
                if (unlink($file)) {
                    $deletedCount++;
                    CLI::write("Deleted: " . basename($file), 'green');
                }
            }
        }
        
        CLI::write("Cleanup complete. Deleted {$deletedCount} log files.", 'green');
    }
}