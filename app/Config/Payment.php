<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Payment extends BaseConfig
{
    /**
     * Payment gateway configurations
     */
    public array $gateways = [
        'midtrans' => [
            'server_key' => env('MIDTRANS_SERVER_KEY', ''),
            'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
            'is_sanitized' => true,
            'is_3ds' => true
        ],
        'xendit' => [
            'secret_key' => env('XENDIT_SECRET_KEY', ''),
            'public_key' => env('XENDIT_PUBLIC_KEY', ''),
            'webhook_token' => env('XENDIT_WEBHOOK_TOKEN', ''),
            'is_production' => env('XENDIT_IS_PRODUCTION', false)
        ]
    ];

    /**
     * Payment method configurations
     */
    public array $methods = [
        'bank_transfer' => [
            'enabled' => true,
            'admin_fee_type' => 'fixed', // 'fixed' or 'percentage'
            'admin_fee_amount' => 2500,
            'min_amount' => 10000,
            'max_amount' => 50000000,
            'expiry_hours' => 24
        ],
        'e_wallet' => [
            'enabled' => true,
            'admin_fee_type' => 'percentage',
            'admin_fee_amount' => 2.5, // 2.5%
            'min_amount' => 10000,
            'max_amount' => 10000000,
            'expiry_hours' => 1
        ],
        'credit_card' => [
            'enabled' => true,
            'admin_fee_type' => 'percentage',
            'admin_fee_amount' => 2.9, // 2.9%
            'min_amount' => 10000,
            'max_amount' => 100000000,
            'expiry_hours' => 1
        ]
    ];

    /**
     * Payment status configurations
     */
    public array $statuses = [
        'pending' => [
            'label' => 'Menunggu Pembayaran',
            'color' => 'warning',
            'icon' => 'clock'
        ],
        'paid' => [
            'label' => 'Sudah Dibayar',
            'color' => 'success',
            'icon' => 'check-circle'
        ],
        'expired' => [
            'label' => 'Kadaluarsa',
            'color' => 'danger',
            'icon' => 'x-circle'
        ],
        'cancelled' => [
            'label' => 'Dibatalkan',
            'color' => 'secondary',
            'icon' => 'x'
        ],
        'failed' => [
            'label' => 'Gagal',
            'color' => 'danger',
            'icon' => 'alert-circle'
        ]
    ];

    /**
     * Payment code configuration
     */
    public array $paymentCode = [
        'prefix' => 'PAY',
        'length' => 14, // PAY + 8 digits date + 6 random digits
        'date_format' => 'Ymd'
    ];

    /**
     * Virtual account configuration
     */
    public array $virtualAccount = [
        'bca' => [
            'prefix' => '70012',
            'length' => 11
        ],
        'bni' => [
            'prefix' => '8808',
            'length' => 16
        ],
        'bri' => [
            'prefix' => '26215',
            'length' => 16
        ],
        'mandiri' => [
            'prefix' => '70012',
            'length' => 13
        ]
    ];

    /**
     * Notification settings
     */
    public array $notifications = [
        'email' => [
            'enabled' => true,
            'templates' => [
                'payment_created' => 'emails/payment_created',
                'payment_reminder' => 'emails/payment_reminder',
                'payment_success' => 'emails/payment_success',
                'payment_expired' => 'emails/payment_expired'
            ]
        ],
        'sms' => [
            'enabled' => false,
            'provider' => 'twilio', // 'twilio', 'nexmo', etc.
            'templates' => [
                'payment_created' => 'Pembayaran {payment_code} telah dibuat. Bayar sebelum {expired_at}.',
                'payment_success' => 'Pembayaran {payment_code} berhasil. Terima kasih!'
            ]
        ],
        'webhook' => [
            'enabled' => true,
            'endpoints' => [
                'payment_created' => env('WEBHOOK_PAYMENT_CREATED', ''),
                'payment_success' => env('WEBHOOK_PAYMENT_SUCCESS', ''),
                'payment_failed' => env('WEBHOOK_PAYMENT_FAILED', '')
            ],
            'secret' => env('WEBHOOK_SECRET', ''),
            'timeout' => 30
        ]
    ];

    /**
     * Security settings
     */
    public array $security = [
        'encrypt_callback_data' => true,
        'validate_webhook_signature' => true,
        'max_payment_attempts' => 3,
        'rate_limit' => [
            'enabled' => true,
            'max_requests' => 10,
            'time_window' => 60 // seconds
        ]
    ];

    /**
     * Cleanup settings
     */
    public array $cleanup = [
        'auto_expire_enabled' => true,
        'auto_expire_cron' => '0 */6 * * *', // Every 6 hours
        'keep_logs_days' => 90,
        'archive_old_payments' => true,
        'archive_after_days' => 365
    ];

    /**
     * Development settings
     */
    public array $development = [
        'fake_payments' => env('FAKE_PAYMENTS', false),
        'auto_confirm_payments' => env('AUTO_CONFIRM_PAYMENTS', false),
        'log_all_requests' => env('LOG_PAYMENT_REQUESTS', false)
    ];

    /**
     * Get payment method configuration
     */
    public function getMethodConfig(string $methodType): ?array
    {
        return $this->methods[$methodType] ?? null;
    }

    /**
     * Get gateway configuration
     */
    public function getGatewayConfig(string $gateway): ?array
    {
        return $this->gateways[$gateway] ?? null;
    }

    /**
     * Check if payment method is enabled
     */
    public function isMethodEnabled(string $methodType): bool
    {
        return $this->methods[$methodType]['enabled'] ?? false;
    }

    /**
     * Get status configuration
     */
    public function getStatusConfig(string $status): ?array
    {
        return $this->statuses[$status] ?? null;
    }

    /**
     * Calculate admin fee
     */
    public function calculateAdminFee(string $methodType, float $amount): float
    {
        $config = $this->getMethodConfig($methodType);
        
        if (!$config) {
            return 0;
        }

        if ($config['admin_fee_type'] === 'percentage') {
            return ($amount * $config['admin_fee_amount']) / 100;
        }

        return $config['admin_fee_amount'];
    }

    /**
     * Validate payment amount
     */
    public function validateAmount(string $methodType, float $amount): array
    {
        $config = $this->getMethodConfig($methodType);
        
        if (!$config) {
            return ['valid' => false, 'message' => 'Metode pembayaran tidak valid'];
        }

        if ($amount < $config['min_amount']) {
            return [
                'valid' => false, 
                'message' => "Minimum pembayaran Rp " . number_format($config['min_amount'], 0, ',', '.')
            ];
        }

        if ($amount > $config['max_amount']) {
            return [
                'valid' => false, 
                'message' => "Maksimum pembayaran Rp " . number_format($config['max_amount'], 0, ',', '.')
            ];
        }

        return ['valid' => true];
    }
}