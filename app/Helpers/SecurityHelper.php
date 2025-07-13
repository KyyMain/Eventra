<?php

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input to prevent XSS and other attacks
     */
    function sanitize_input($input, $type = 'string')
    {
        if (is_array($input)) {
            return array_map(function($item) use ($type) {
                return sanitize_input($item, $type);
            }, $input);
        }

        switch ($type) {
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var(trim($input), FILTER_SANITIZE_URL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'html':
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
            default:
                return strip_tags(trim($input));
        }
    }
}

if (!function_exists('validate_and_sanitize')) {
    /**
     * Validate and sanitize input data
     */
    function validate_and_sanitize($data, $rules)
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        
        if (!$validation->run($data)) {
            return [
                'success' => false,
                'errors' => $validation->getErrors()
            ];
        }
        
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = sanitize_input($value);
        }
        
        return [
            'success' => true,
            'data' => $sanitized
        ];
    }
}