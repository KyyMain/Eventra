<?php

if (!function_exists('validate_input')) {
    /**
     * Enhanced input validation with multiple sanitization options
     */
    function validate_input($input, string $type = 'string', array $options = []): mixed
    {
        if ($input === null || $input === '') {
            return $options['allow_empty'] ?? false ? $input : null;
        }

        switch ($type) {
            case 'string':
                return validate_string($input, $options);
            case 'email':
                return validate_email($input, $options);
            case 'url':
                return validate_url($input, $options);
            case 'int':
            case 'integer':
                return validate_integer($input, $options);
            case 'float':
                return validate_float($input, $options);
            case 'boolean':
                return validate_boolean($input);
            case 'array':
                return validate_array($input, $options);
            case 'json':
                return validate_json($input, $options);
            case 'date':
                return validate_date($input, $options);
            case 'phone':
                return validate_phone($input, $options);
            case 'slug':
                return validate_slug($input, $options);
            default:
                return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
}

if (!function_exists('validate_string')) {
    function validate_string($input, array $options = []): ?string
    {
        $input = trim($input);
        
        // Length validation
        $minLength = $options['min_length'] ?? 0;
        $maxLength = $options['max_length'] ?? 1000;
        
        if (strlen($input) < $minLength || strlen($input) > $maxLength) {
            return null;
        }
        
        // Pattern validation
        if (isset($options['pattern']) && !preg_match($options['pattern'], $input)) {
            return null;
        }
        
        // Sanitization level
        $sanitizeLevel = $options['sanitize'] ?? 'basic';
        
        switch ($sanitizeLevel) {
            case 'none':
                return $input;
            case 'basic':
                return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            case 'strict':
                return preg_replace('/[^a-zA-Z0-9\s\-_.,!?]/', '', $input);
            case 'alphanumeric':
                return preg_replace('/[^a-zA-Z0-9]/', '', $input);
            default:
                return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }
}

if (!function_exists('validate_email')) {
    function validate_email($input, array $options = []): ?string
    {
        $email = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        
        // Domain validation
        if (isset($options['allowed_domains'])) {
            $domain = substr(strrchr($email, "@"), 1);
            if (!in_array($domain, $options['allowed_domains'])) {
                return null;
            }
        }
        
        // Blocked domains
        if (isset($options['blocked_domains'])) {
            $domain = substr(strrchr($email, "@"), 1);
            if (in_array($domain, $options['blocked_domains'])) {
                return null;
            }
        }
        
        return $email;
    }
}

if (!function_exists('validate_url')) {
    function validate_url($input, array $options = []): ?string
    {
        $url = filter_var(trim($input), FILTER_SANITIZE_URL);
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        
        // Protocol validation
        $allowedProtocols = $options['protocols'] ?? ['http', 'https'];
        $protocol = parse_url($url, PHP_URL_SCHEME);
        
        if (!in_array($protocol, $allowedProtocols)) {
            return null;
        }
        
        return $url;
    }
}

if (!function_exists('validate_integer')) {
    function validate_integer($input, array $options = []): ?int
    {
        $int = filter_var($input, FILTER_VALIDATE_INT);
        
        if ($int === false) {
            return null;
        }
        
        $min = $options['min'] ?? PHP_INT_MIN;
        $max = $options['max'] ?? PHP_INT_MAX;
        
        if ($int < $min || $int > $max) {
            return null;
        }
        
        return $int;
    }
}

if (!function_exists('validate_float')) {
    function validate_float($input, array $options = []): ?float
    {
        $float = filter_var($input, FILTER_VALIDATE_FLOAT);
        
        if ($float === false) {
            return null;
        }
        
        $min = $options['min'] ?? -PHP_FLOAT_MAX;
        $max = $options['max'] ?? PHP_FLOAT_MAX;
        
        if ($float < $min || $float > $max) {
            return null;
        }
        
        return $float;
    }
}

if (!function_exists('validate_boolean')) {
    function validate_boolean($input): bool
    {
        return filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }
}

if (!function_exists('validate_array')) {
    function validate_array($input, array $options = []): ?array
    {
        if (!is_array($input)) {
            return null;
        }
        
        $maxItems = $options['max_items'] ?? 1000;
        $minItems = $options['min_items'] ?? 0;
        
        if (count($input) < $minItems || count($input) > $maxItems) {
            return null;
        }
        
        // Validate each item if type specified
        if (isset($options['item_type'])) {
            foreach ($input as $key => $value) {
                $validated = validate_input($value, $options['item_type'], $options['item_options'] ?? []);
                if ($validated === null) {
                    return null;
                }
                $input[$key] = $validated;
            }
        }
        
        return $input;
    }
}

if (!function_exists('validate_json')) {
    function validate_json($input, array $options = []): ?array
    {
        if (is_array($input)) {
            return $input;
        }
        
        $decoded = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        $maxDepth = $options['max_depth'] ?? 10;
        if (json_encode($decoded) !== json_encode($decoded, 0, $maxDepth)) {
            return null;
        }
        
        return $decoded;
    }
}

if (!function_exists('validate_date')) {
    function validate_date($input, array $options = []): ?string
    {
        $format = $options['format'] ?? 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $input);
        
        if (!$date || $date->format($format) !== $input) {
            return null;
        }
        
        // Range validation
        if (isset($options['min_date'])) {
            $minDate = new DateTime($options['min_date']);
            if ($date < $minDate) {
                return null;
            }
        }
        
        if (isset($options['max_date'])) {
            $maxDate = new DateTime($options['max_date']);
            if ($date > $maxDate) {
                return null;
            }
        }
        
        return $date->format($format);
    }
}

if (!function_exists('validate_phone')) {
    function validate_phone($input, array $options = []): ?string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $input);
        
        $minLength = $options['min_length'] ?? 10;
        $maxLength = $options['max_length'] ?? 15;
        
        if (strlen($phone) < $minLength || strlen($phone) > $maxLength) {
            return null;
        }
        
        // Format phone number
        $format = $options['format'] ?? 'raw';
        
        switch ($format) {
            case 'international':
                return '+' . $phone;
            case 'formatted':
                // Basic US format
                if (strlen($phone) === 10) {
                    return sprintf('(%s) %s-%s', 
                        substr($phone, 0, 3),
                        substr($phone, 3, 3),
                        substr($phone, 6, 4)
                    );
                }
                return $phone;
            default:
                return $phone;
        }
    }
}

if (!function_exists('validate_slug')) {
    function validate_slug($input, array $options = []): ?string
    {
        $slug = strtolower(trim($input));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        $maxLength = $options['max_length'] ?? 100;
        $minLength = $options['min_length'] ?? 1;
        
        if (strlen($slug) < $minLength || strlen($slug) > $maxLength) {
            return null;
        }
        
        return $slug;
    }
}

if (!function_exists('sanitize_filename')) {
    function sanitize_filename(string $filename): string
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Prevent hidden files
        $filename = ltrim($filename, '.');
        
        // Ensure it's not empty
        if (empty($filename)) {
            $filename = 'file_' . uniqid();
        }
        
        return $filename;
    }
}

if (!function_exists('validate_csrf_token')) {
    function validate_csrf_token(?string $token = null): bool
    {
        $token = $token ?? service('request')->getPost('csrf_token_name');
        
        if (!$token) {
            return false;
        }
        
        $session = session();
        $sessionToken = $session->get('csrf_token_name');
        
        return $token && $sessionToken && hash_equals($sessionToken, $token);
    }
}