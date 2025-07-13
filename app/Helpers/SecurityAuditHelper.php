<?php

namespace App\Helpers;

class SecurityAuditHelper
{
    /**
     * Audit input data for security vulnerabilities
     */
    public static function auditInput(array $data, array $rules = []): array
    {
        $issues = [];
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            $fieldIssues = [];
            
            // Check for XSS attempts
            if (is_string($value)) {
                if (self::containsXSS($value)) {
                    $fieldIssues[] = 'Potential XSS attempt detected';
                }
                
                // Check for SQL injection patterns
                if (self::containsSQLInjection($value)) {
                    $fieldIssues[] = 'Potential SQL injection attempt detected';
                }
                
                // Check for path traversal
                if (self::containsPathTraversal($value)) {
                    $fieldIssues[] = 'Potential path traversal attempt detected';
                }
                
                // Check for command injection
                if (self::containsCommandInjection($value)) {
                    $fieldIssues[] = 'Potential command injection attempt detected';
                }
                
                // Sanitize the value
                $sanitized[$key] = self::sanitizeInput($value, $rules[$key] ?? []);
            } else {
                $sanitized[$key] = $value;
            }
            
            if (!empty($fieldIssues)) {
                $issues[$key] = $fieldIssues;
            }
        }
        
        return [
            'issues' => $issues,
            'sanitized' => $sanitized,
            'risk_level' => self::calculateRiskLevel($issues)
        ];
    }
    
    /**
     * Check for XSS patterns
     */
    private static function containsXSS(string $input): bool
    {
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>/i',
            '/<object[^>]*>/i',
            '/<embed[^>]*>/i',
            '/expression\s*\(/i',
            '/vbscript:/i',
            '/<meta[^>]*>/i'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check for SQL injection patterns
     */
    private static function containsSQLInjection(string $input): bool
    {
        $sqlPatterns = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bUPDATE\b|\bDELETE\b|\bDROP\b|\bCREATE\b|\bALTER\b)/i',
            '/(\bOR\b|\bAND\b)\s+\d+\s*=\s*\d+/i',
            '/[\'";]\s*(OR|AND)\s+[\'"]?\w+[\'"]?\s*=\s*[\'"]?\w+[\'"]?/i',
            '/\b(EXEC|EXECUTE)\b/i',
            '/\b(SP_|XP_)\w+/i',
            '/--\s*$/m',
            '/\/\*.*?\*\//s'
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check for path traversal patterns
     */
    private static function containsPathTraversal(string $input): bool
    {
        $pathPatterns = [
            '/\.\.\//',
            '/\.\.\\\\/',
            '/%2e%2e%2f/i',
            '/%2e%2e%5c/i',
            '/\.\.\%2f/i',
            '/\.\.\%5c/i'
        ];
        
        foreach ($pathPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check for command injection patterns
     */
    private static function containsCommandInjection(string $input): bool
    {
        $commandPatterns = [
            '/[;&|`$(){}[\]]/i',
            '/\b(cat|ls|dir|type|copy|move|del|rm|mkdir|rmdir|cd|pwd|whoami|id|ps|kill|wget|curl|nc|netcat)\b/i',
            '/\$\{.*\}/',
            '/\$\(.*\)/',
            '/`.*`/'
        ];
        
        foreach ($commandPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Sanitize input based on rules
     */
    private static function sanitizeInput(string $input, array $rules = []): string
    {
        $sanitized = $input;
        
        // Default sanitization
        $sanitized = htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
        
        // Apply specific rules
        if (isset($rules['strip_tags']) && $rules['strip_tags']) {
            $allowedTags = $rules['allowed_tags'] ?? '';
            $sanitized = strip_tags($sanitized, $allowedTags);
        }
        
        if (isset($rules['trim']) && $rules['trim']) {
            $sanitized = trim($sanitized);
        }
        
        if (isset($rules['max_length'])) {
            $sanitized = substr($sanitized, 0, $rules['max_length']);
        }
        
        if (isset($rules['pattern']) && !preg_match($rules['pattern'], $sanitized)) {
            $sanitized = preg_replace('/[^' . preg_quote($rules['allowed_chars'] ?? 'a-zA-Z0-9\s', '/') . ']/', '', $sanitized);
        }
        
        return $sanitized;
    }
    
    /**
     * Calculate risk level based on issues
     */
    private static function calculateRiskLevel(array $issues): string
    {
        if (empty($issues)) {
            return 'low';
        }
        
        $riskScore = 0;
        foreach ($issues as $fieldIssues) {
            foreach ($fieldIssues as $issue) {
                if (strpos($issue, 'XSS') !== false) {
                    $riskScore += 3;
                } elseif (strpos($issue, 'SQL injection') !== false) {
                    $riskScore += 4;
                } elseif (strpos($issue, 'command injection') !== false) {
                    $riskScore += 4;
                } elseif (strpos($issue, 'path traversal') !== false) {
                    $riskScore += 2;
                } else {
                    $riskScore += 1;
                }
            }
        }
        
        if ($riskScore >= 8) {
            return 'critical';
        } elseif ($riskScore >= 5) {
            return 'high';
        } elseif ($riskScore >= 2) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Audit file upload security
     */
    public static function auditFileUpload(array $file): array
    {
        $issues = [];
        
        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedExtensions)) {
            $issues[] = 'File extension not allowed';
        }
        
        // Check MIME type
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];
        
        if (!in_array($file['type'], $allowedMimeTypes)) {
            $issues[] = 'MIME type not allowed';
        }
        
        // Check file size (10MB limit)
        if ($file['size'] > 10 * 1024 * 1024) {
            $issues[] = 'File size exceeds limit';
        }
        
        // Check for executable files
        $executableExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'php', 'asp', 'jsp'];
        if (in_array($extension, $executableExtensions)) {
            $issues[] = 'Executable file detected';
        }
        
        // Check filename for suspicious patterns
        if (preg_match('/[<>:"|?*]/', $file['name'])) {
            $issues[] = 'Filename contains suspicious characters';
        }
        
        return [
            'issues' => $issues,
            'risk_level' => empty($issues) ? 'low' : (count($issues) > 2 ? 'high' : 'medium'),
            'safe_filename' => self::generateSafeFilename($file['name'])
        ];
    }
    
    /**
     * Generate safe filename
     */
    private static function generateSafeFilename(string $filename): string
    {
        $info = pathinfo($filename);
        $name = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $info['filename']);
        $extension = isset($info['extension']) ? '.' . $info['extension'] : '';
        
        return $name . '_' . time() . $extension;
    }
    
    /**
     * Audit session security
     */
    public static function auditSession(): array
    {
        $issues = [];
        
        // Check session configuration
        if (!ini_get('session.use_strict_mode')) {
            $issues[] = 'Session strict mode not enabled';
        }
        
        if (!ini_get('session.cookie_httponly')) {
            $issues[] = 'Session cookies not HTTP-only';
        }
        
        if (!ini_get('session.cookie_secure') && isset($_SERVER['HTTPS'])) {
            $issues[] = 'Session cookies not secure over HTTPS';
        }
        
        if (ini_get('session.cookie_samesite') !== 'Strict') {
            $issues[] = 'Session SameSite attribute not set to Strict';
        }
        
        // Check session timeout
        $maxLifetime = ini_get('session.gc_maxlifetime');
        if ($maxLifetime > 3600) { // 1 hour
            $issues[] = 'Session timeout too long';
        }
        
        return [
            'issues' => $issues,
            'risk_level' => empty($issues) ? 'low' : (count($issues) > 3 ? 'high' : 'medium')
        ];
    }
    
    /**
     * Generate security report
     */
    public static function generateSecurityReport(array $audits): array
    {
        $totalIssues = 0;
        $criticalIssues = 0;
        $highIssues = 0;
        $mediumIssues = 0;
        $lowIssues = 0;
        
        foreach ($audits as $audit) {
            $issueCount = is_array($audit['issues']) ? count($audit['issues']) : 0;
            $totalIssues += $issueCount;
            
            switch ($audit['risk_level']) {
                case 'critical':
                    $criticalIssues += $issueCount;
                    break;
                case 'high':
                    $highIssues += $issueCount;
                    break;
                case 'medium':
                    $mediumIssues += $issueCount;
                    break;
                case 'low':
                    $lowIssues += $issueCount;
                    break;
            }
        }
        
        $overallRisk = 'low';
        if ($criticalIssues > 0) {
            $overallRisk = 'critical';
        } elseif ($highIssues > 0) {
            $overallRisk = 'high';
        } elseif ($mediumIssues > 0) {
            $overallRisk = 'medium';
        }
        
        return [
            'total_issues' => $totalIssues,
            'critical_issues' => $criticalIssues,
            'high_issues' => $highIssues,
            'medium_issues' => $mediumIssues,
            'low_issues' => $lowIssues,
            'overall_risk' => $overallRisk,
            'recommendations' => self::generateRecommendations($audits),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Generate security recommendations
     */
    private static function generateRecommendations(array $audits): array
    {
        $recommendations = [];
        
        foreach ($audits as $auditType => $audit) {
            if (!empty($audit['issues'])) {
                switch ($auditType) {
                    case 'input':
                        $recommendations[] = 'Implement stronger input validation and sanitization';
                        $recommendations[] = 'Use parameterized queries to prevent SQL injection';
                        $recommendations[] = 'Implement Content Security Policy (CSP) headers';
                        break;
                    case 'file_upload':
                        $recommendations[] = 'Restrict file upload types and sizes';
                        $recommendations[] = 'Scan uploaded files for malware';
                        $recommendations[] = 'Store uploaded files outside web root';
                        break;
                    case 'session':
                        $recommendations[] = 'Configure secure session settings';
                        $recommendations[] = 'Implement session timeout mechanisms';
                        $recommendations[] = 'Use HTTPS for all authenticated pages';
                        break;
                }
            }
        }
        
        return array_unique($recommendations);
    }
}