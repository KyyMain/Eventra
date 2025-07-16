<?php

namespace App\DTOs;

class PaymentDTO
{
    public int $registrationId;
    public int $paymentMethodId;
    public int $userId;
    public float $amount;
    public float $adminFee;
    public float $totalAmount;
    public string $paymentCode;
    public string $status;
    public string $expiredAt;
    public ?string $callbackData;
    public ?string $externalId;
    public ?string $paymentUrl;
    public ?string $qrCode;
    public ?string $virtualAccount;
    public ?string $paymentInstructions;
    public ?string $paidAt;

    public function __construct(array $data)
    {
        $this->registrationId = intval($data['registration_id']);
        $this->paymentMethodId = intval($data['payment_method_id']);
        $this->userId = intval($data['user_id']);
        $this->amount = floatval($data['amount']);
        $this->adminFee = floatval($data['admin_fee'] ?? 0);
        $this->totalAmount = floatval($data['total_amount']);
        $this->paymentCode = $data['payment_code'];
        $this->status = $data['status'] ?? 'pending';
        $this->expiredAt = $data['expired_at'];
        $this->callbackData = $data['callback_data'] ?? null;
        $this->externalId = $data['external_id'] ?? null;
        $this->paymentUrl = $data['payment_url'] ?? null;
        $this->qrCode = $data['qr_code'] ?? null;
        $this->virtualAccount = $data['virtual_account'] ?? null;
        $this->paymentInstructions = $data['payment_instructions'] ?? null;
        $this->paidAt = $data['paid_at'] ?? null;
    }

    /**
     * Convert to array for database insertion
     */
    public function toArray(): array
    {
        return [
            'registration_id' => $this->registrationId,
            'payment_method_id' => $this->paymentMethodId,
            'amount' => $this->amount,
            'admin_fee' => $this->adminFee,
            'total_amount' => $this->totalAmount,
            'payment_code' => $this->paymentCode,
            'status' => $this->status,
            'expired_at' => $this->expiredAt,
            'callback_data' => $this->callbackData,
            'external_id' => $this->externalId,
            'payment_url' => $this->paymentUrl,
            'qr_code' => $this->qrCode,
            'virtual_account' => $this->virtualAccount,
            'payment_instructions' => $this->paymentInstructions,
            'paid_at' => $this->paidAt
        ];
    }

    /**
     * Validate payment data
     */
    public function validate(): array
    {
        $errors = [];

        if ($this->registrationId <= 0) {
            $errors[] = 'Registration ID harus valid';
        }

        if ($this->paymentMethodId <= 0) {
            $errors[] = 'Payment Method ID harus valid';
        }

        if ($this->userId <= 0) {
            $errors[] = 'User ID harus valid';
        }

        if ($this->amount <= 0) {
            $errors[] = 'Amount harus lebih dari 0';
        }

        if ($this->totalAmount <= 0) {
            $errors[] = 'Total amount harus lebih dari 0';
        }

        if (empty($this->paymentCode)) {
            $errors[] = 'Payment code tidak boleh kosong';
        }

        if (!in_array($this->status, ['pending', 'paid', 'expired', 'cancelled'])) {
            $errors[] = 'Status tidak valid';
        }

        return $errors;
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return strtotime($this->expiredAt) < time();
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAmount(): string
    {
        return 'Rp ' . number_format($this->totalAmount, 0, ',', '.');
    }

    /**
     * Get time remaining until expiration
     */
    public function getTimeRemaining(): array
    {
        $remaining = strtotime($this->expiredAt) - time();
        
        if ($remaining <= 0) {
            return ['expired' => true];
        }

        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        $seconds = $remaining % 60;

        return [
            'expired' => false,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'total_seconds' => $remaining
        ];
    }
}