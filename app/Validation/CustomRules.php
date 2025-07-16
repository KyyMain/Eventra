<?php

namespace App\Validation;

use App\Models\EventRegistrationModel;
use App\Models\PaymentMethodModel;
use App\Models\PaymentModel;

class CustomRules
{
    /**
     * Validate if registration belongs to user
     */
    public function valid_user_registration(string $registrationId, string $params, array $data): bool
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return false;
        }

        $registrationModel = new EventRegistrationModel();
        $registration = $registrationModel
            ->where('id', $registrationId)
            ->where('user_id', $userId)
            ->first();

        return $registration !== null;
    }

    /**
     * Validate if payment method is active
     */
    public function active_payment_method(string $paymentMethodId, string $params, array $data): bool
    {
        $paymentMethodModel = new PaymentMethodModel();
        $method = $paymentMethodModel
            ->where('id', $paymentMethodId)
            ->where('is_active', 1)
            ->first();

        return $method !== null;
    }

    /**
     * Validate if registration doesn't have pending payment
     */
    public function no_pending_payment(string $registrationId, string $params, array $data): bool
    {
        $paymentModel = new PaymentModel();
        $pendingPayment = $paymentModel
            ->where('registration_id', $registrationId)
            ->where('status', 'pending')
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->first();

        return $pendingPayment === null;
    }

    /**
     * Validate payment code format
     */
    public function valid_payment_code(string $paymentCode, string $params, array $data): bool
    {
        // Payment code should be: PAY + YYYYMMDD + 6 digits
        $pattern = '/^PAY\d{8}\d{6}$/';
        return preg_match($pattern, $paymentCode) === 1;
    }

    /**
     * Validate if payment amount matches event price
     */
    public function valid_payment_amount(string $amount, string $params, array $data): bool
    {
        if (!isset($data['registration_id'])) {
            return false;
        }

        $registrationModel = new EventRegistrationModel();
        $registration = $registrationModel
            ->select('events.price')
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.id', $data['registration_id'])
            ->first();

        if (!$registration) {
            return false;
        }

        return floatval($amount) === floatval($registration['price']);
    }

    /**
     * Validate virtual account format
     */
    public function valid_virtual_account(string $virtualAccount, string $params, array $data): bool
    {
        // Virtual account should be numeric and between 10-16 digits
        return preg_match('/^\d{10,16}$/', $virtualAccount) === 1;
    }

    /**
     * Validate QR code format
     */
    public function valid_qr_code(string $qrCode, string $params, array $data): bool
    {
        // QR code should be a valid URL or base64 encoded string
        return filter_var($qrCode, FILTER_VALIDATE_URL) !== false || 
               base64_decode($qrCode, true) !== false;
    }

    /**
     * Validate payment expiration time
     */
    public function valid_expiration(string $expiredAt, string $params, array $data): bool
    {
        $expiredTime = strtotime($expiredAt);
        $currentTime = time();
        $maxExpiration = strtotime('+7 days'); // Maximum 7 days

        return $expiredTime > $currentTime && $expiredTime <= $maxExpiration;
    }

    /**
     * Validate callback data JSON format
     */
    public function valid_callback_data(?string $callbackData, string $params, array $data): bool
    {
        if ($callbackData === null || $callbackData === '') {
            return true; // Null/empty is allowed
        }

        json_decode($callbackData);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate payment status
     */
    public function valid_payment_status(string $status, string $params, array $data): bool
    {
        $validStatuses = ['pending', 'paid', 'expired', 'cancelled', 'failed'];
        return in_array($status, $validStatuses);
    }

    /**
     * Error messages for custom rules
     */
    public function valid_user_registration_errors(): string
    {
        return 'Pendaftaran tidak valid atau tidak ditemukan.';
    }

    public function active_payment_method_errors(): string
    {
        return 'Metode pembayaran tidak aktif atau tidak valid.';
    }

    public function no_pending_payment_errors(): string
    {
        return 'Sudah ada pembayaran yang sedang menunggu untuk pendaftaran ini.';
    }

    public function valid_payment_code_errors(): string
    {
        return 'Format kode pembayaran tidak valid.';
    }

    public function valid_payment_amount_errors(): string
    {
        return 'Jumlah pembayaran tidak sesuai dengan harga event.';
    }

    public function valid_virtual_account_errors(): string
    {
        return 'Format virtual account tidak valid.';
    }

    public function valid_qr_code_errors(): string
    {
        return 'Format QR code tidak valid.';
    }

    public function valid_expiration_errors(): string
    {
        return 'Waktu kadaluarsa tidak valid.';
    }

    public function valid_callback_data_errors(): string
    {
        return 'Format callback data harus berupa JSON yang valid.';
    }

    public function valid_payment_status_errors(): string
    {
        return 'Status pembayaran tidak valid.';
    }
}