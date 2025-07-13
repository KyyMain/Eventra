<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Validate strong password
     */
    public function strong_password(string $str, string &$error = null): bool
    {
        if (strlen($str) < 8) {
            $error = 'Password must be at least 8 characters long.';
            return false;
        }
        
        if (!preg_match('/[A-Z]/', $str)) {
            $error = 'Password must contain at least one uppercase letter.';
            return false;
        }
        
        if (!preg_match('/[a-z]/', $str)) {
            $error = 'Password must contain at least one lowercase letter.';
            return false;
        }
        
        if (!preg_match('/[0-9]/', $str)) {
            $error = 'Password must contain at least one number.';
            return false;
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $str)) {
            $error = 'Password must contain at least one special character.';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate unique email (excluding current user)
     */
    public function unique_email(string $str, string $fields, array $data): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        $builder->where('email', $str);
        
        // If updating, exclude current user
        if (isset($data['id'])) {
            $builder->where('id !=', $data['id']);
        }
        
        return $builder->countAllResults() === 0;
    }
    
    /**
     * Validate unique username (excluding current user)
     */
    public function unique_username(string $str, string $fields, array $data): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        $builder->where('username', $str);
        
        // If updating, exclude current user
        if (isset($data['id'])) {
            $builder->where('id !=', $data['id']);
        }
        
        return $builder->countAllResults() === 0;
    }
    
    /**
     * Validate event date is in future
     */
    public function future_date(string $str, string &$error = null): bool
    {
        $eventDate = strtotime($str);
        $now = time();
        
        if ($eventDate <= $now) {
            $error = 'Event date must be in the future.';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate event capacity
     */
    public function valid_capacity(string $str, string &$error = null): bool
    {
        $capacity = (int) $str;
        
        if ($capacity < 1) {
            $error = 'Event capacity must be at least 1.';
            return false;
        }
        
        if ($capacity > 10000) {
            $error = 'Event capacity cannot exceed 10,000.';
            return false;
        }
        
        return true;
    }
}