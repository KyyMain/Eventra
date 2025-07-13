<?php

namespace App\Helpers;

class DatabaseOptimizationHelper
{
    /**
     * Optimize query with proper indexing hints
     */
    public static function optimizeQuery(string $query, array $params = []): array
    {
        $optimizations = [];
        
        // Analyze query patterns
        if (preg_match('/SELECT.*FROM\s+(\w+)/i', $query, $matches)) {
            $table = $matches[1];
            $optimizations['table'] = $table;
            
            // Suggest indexes based on WHERE clauses
            if (preg_match_all('/WHERE.*?(\w+)\s*[=<>]/i', $query, $whereMatches)) {
                $optimizations['suggested_indexes'] = array_unique($whereMatches[1]);
            }
            
            // Check for JOIN optimizations
            if (preg_match_all('/JOIN\s+(\w+)\s+ON\s+(\w+\.\w+)\s*=\s*(\w+\.\w+)/i', $query, $joinMatches)) {
                $optimizations['join_columns'] = array_unique(array_merge($joinMatches[2], $joinMatches[3]));
            }
        }
        
        return [
            'query' => $query,
            'params' => $params,
            'optimizations' => $optimizations
        ];
    }
    
    /**
     * Generate pagination query with optimization
     */
    public static function paginateQuery(string $baseQuery, int $page, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        
        // Add LIMIT and OFFSET
        $paginatedQuery = $baseQuery . " LIMIT {$perPage} OFFSET {$offset}";
        
        // Generate count query
        $countQuery = preg_replace('/SELECT.*?FROM/i', 'SELECT COUNT(*) as total FROM', $baseQuery);
        $countQuery = preg_replace('/ORDER BY.*$/i', '', $countQuery);
        
        return [
            'data_query' => $paginatedQuery,
            'count_query' => $countQuery,
            'page' => $page,
            'per_page' => $perPage,
            'offset' => $offset
        ];
    }
    
    /**
     * Batch insert optimization
     */
    public static function optimizeBatchInsert(string $table, array $data, int $batchSize = 100): array
    {
        $batches = array_chunk($data, $batchSize);
        $queries = [];
        
        foreach ($batches as $batch) {
            if (empty($batch)) continue;
            
            $columns = array_keys($batch[0]);
            $placeholders = [];
            $values = [];
            
            foreach ($batch as $row) {
                $rowPlaceholders = [];
                foreach ($columns as $column) {
                    $rowPlaceholders[] = '?';
                    $values[] = $row[$column] ?? null;
                }
                $placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
            }
            
            $query = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES " . implode(', ', $placeholders);
            
            $queries[] = [
                'query' => $query,
                'values' => $values
            ];
        }
        
        return $queries;
    }
    
    /**
     * Generate efficient search query
     */
    public static function buildSearchQuery(string $table, array $searchFields, string $searchTerm, array $filters = []): array
    {
        $searchConditions = [];
        $params = [];
        
        // Build search conditions
        foreach ($searchFields as $field) {
            $searchConditions[] = "{$field} LIKE ?";
            $params[] = "%{$searchTerm}%";
        }
        
        $whereClause = '(' . implode(' OR ', $searchConditions) . ')';
        
        // Add filters
        $filterConditions = [];
        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $placeholders = str_repeat('?,', count($value) - 1) . '?';
                $filterConditions[] = "{$field} IN ({$placeholders})";
                $params = array_merge($params, $value);
            } else {
                $filterConditions[] = "{$field} = ?";
                $params[] = $value;
            }
        }
        
        if (!empty($filterConditions)) {
            $whereClause .= ' AND ' . implode(' AND ', $filterConditions);
        }
        
        $query = "SELECT * FROM {$table} WHERE {$whereClause}";
        
        return [
            'query' => $query,
            'params' => $params
        ];
    }
    
    /**
     * Analyze query performance
     */
    public static function analyzeQueryPerformance(string $query): array
    {
        $analysis = [
            'complexity' => 'low',
            'warnings' => [],
            'suggestions' => []
        ];
        
        // Check for potential performance issues
        if (preg_match('/SELECT\s+\*/i', $query)) {
            $analysis['warnings'][] = 'Using SELECT * - consider specifying columns';
            $analysis['suggestions'][] = 'Replace SELECT * with specific column names';
        }
        
        if (preg_match('/LIKE\s+[\'"]%.*%[\'"]/i', $query)) {
            $analysis['warnings'][] = 'Using LIKE with leading wildcard';
            $analysis['suggestions'][] = 'Consider full-text search or different indexing strategy';
            $analysis['complexity'] = 'medium';
        }
        
        if (preg_match_all('/JOIN/i', $query) > 3) {
            $analysis['warnings'][] = 'Multiple JOINs detected';
            $analysis['suggestions'][] = 'Consider denormalization or caching for complex joins';
            $analysis['complexity'] = 'high';
        }
        
        if (preg_match('/ORDER BY.*RAND\(\)/i', $query)) {
            $analysis['warnings'][] = 'Using ORDER BY RAND() - very slow on large tables';
            $analysis['suggestions'][] = 'Use alternative random selection methods';
            $analysis['complexity'] = 'high';
        }
        
        if (!preg_match('/LIMIT/i', $query) && preg_match('/SELECT/i', $query)) {
            $analysis['warnings'][] = 'No LIMIT clause - potential for large result sets';
            $analysis['suggestions'][] = 'Add LIMIT clause to prevent memory issues';
        }
        
        return $analysis;
    }
    
    /**
     * Generate cache key for query
     */
    public static function generateCacheKey(string $query, array $params = []): string
    {
        $normalizedQuery = preg_replace('/\s+/', ' ', trim($query));
        $key = md5($normalizedQuery . serialize($params));
        return "query_cache_{$key}";
    }
    
    /**
     * Suggest database indexes
     */
    public static function suggestIndexes(array $queries): array
    {
        $suggestions = [];
        
        foreach ($queries as $query) {
            // Extract table and column information
            if (preg_match('/FROM\s+(\w+)/i', $query, $tableMatch)) {
                $table = $tableMatch[1];
                
                // Find WHERE conditions
                if (preg_match_all('/WHERE.*?(\w+)\s*[=<>]/i', $query, $whereMatches)) {
                    foreach ($whereMatches[1] as $column) {
                        $suggestions[] = [
                            'table' => $table,
                            'column' => $column,
                            'type' => 'single',
                            'reason' => 'Used in WHERE clause'
                        ];
                    }
                }
                
                // Find ORDER BY columns
                if (preg_match_all('/ORDER BY\s+(\w+)/i', $query, $orderMatches)) {
                    foreach ($orderMatches[1] as $column) {
                        $suggestions[] = [
                            'table' => $table,
                            'column' => $column,
                            'type' => 'single',
                            'reason' => 'Used in ORDER BY clause'
                        ];
                    }
                }
                
                // Find JOIN conditions
                if (preg_match_all('/JOIN\s+\w+\s+ON\s+(\w+)\.(\w+)\s*=\s*(\w+)\.(\w+)/i', $query, $joinMatches)) {
                    for ($i = 0; $i < count($joinMatches[0]); $i++) {
                        $suggestions[] = [
                            'table' => $joinMatches[1][$i],
                            'column' => $joinMatches[2][$i],
                            'type' => 'foreign_key',
                            'reason' => 'Used in JOIN condition'
                        ];
                        $suggestions[] = [
                            'table' => $joinMatches[3][$i],
                            'column' => $joinMatches[4][$i],
                            'type' => 'foreign_key',
                            'reason' => 'Used in JOIN condition'
                        ];
                    }
                }
            }
        }
        
        // Remove duplicates and group by table
        $uniqueSuggestions = [];
        foreach ($suggestions as $suggestion) {
            $key = $suggestion['table'] . '.' . $suggestion['column'];
            if (!isset($uniqueSuggestions[$key])) {
                $uniqueSuggestions[$key] = $suggestion;
            }
        }
        
        return array_values($uniqueSuggestions);
    }
}