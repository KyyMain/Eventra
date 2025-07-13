<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Eventra extends BaseConfig
{
    /**
     * Application Settings
     */
    public array $app = [
        'name' => 'Eventra',
        'version' => '1.0.0',
        'timezone' => 'Asia/Jakarta',
        'locale' => 'id',
        'maintenance_mode' => false
    ];

    /**
     * Event Settings
     */
    public array $events = [
        'max_image_size' => 5 * 1024 * 1024, // 5MB
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'default_max_participants' => 100,
        'registration_deadline_hours' => 2, // Hours before event starts
        'certificate_template_path' => FCPATH . 'assets/templates/certificate.html'
    ];

    /**
     * Security Settings
     */
    public array $security = [
        'rate_limit_requests_per_minute' => 60,
        'max_login_attempts' => 5,
        'login_attempt_timeout' => 900, // 15 minutes
        'session_timeout' => 7200, // 2 hours
        'password_min_length' => 8,
        'require_email_verification' => false
    ];

    /**
     * Email Settings
     */
    public array $email = [
        'from_email' => 'noreply@eventra.com',
        'from_name' => 'Eventra Platform',
        'registration_confirmation' => true,
        'event_reminders' => true,
        'certificate_notifications' => true
    ];

    /**
     * File Upload Settings
     */
    public array $uploads = [
        'max_file_size' => 10 * 1024 * 1024, // 10MB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'],
        'upload_path' => FCPATH . 'uploads/',
        'create_thumbs' => true,
        'thumb_width' => 300,
        'thumb_height' => 200
    ];

    /**
     * Cache Settings
     */
    public array $cache = [
        'events_ttl' => 3600, // 1 hour
        'user_stats_ttl' => 1800, // 30 minutes
        'reports_ttl' => 7200, // 2 hours
        'enable_query_cache' => true
    ];

    /**
     * Pagination Settings
     */
    public array $pagination = [
        'events_per_page' => 12,
        'users_per_page' => 20,
        'registrations_per_page' => 25,
        'reports_per_page' => 50
    ];

    /**
     * API Settings
     */
    public array $api = [
        'version' => 'v1',
        'rate_limit' => 1000, // requests per hour
        'enable_cors' => true,
        'allowed_origins' => ['*'],
        'require_api_key' => false
    ];
}