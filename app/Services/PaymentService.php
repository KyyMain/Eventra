<?php

namespace App\Services;

use App\Models\PaymentModel;
use App\Models\PaymentMethodModel;
use App\Models\EventRegistrationModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PaymentService
{
    protected $paymentModel;
    protected $paymentMethodModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
        $this->paymentMethodModel = new PaymentMethodModel();
        $this->registrationModel = new EventRegistrationModel();
    }

    /**
     * Create a new payment with proper validation and error handling
     */
    public function createPayment(array $data): array
    {
        try {
            // Validate required fields
            $validation = $this->validatePaymentData($data);
            if (!$validation['success']) {
                return $validation;
            }

            // Get registration details
            $registration = $this->getRegistrationDetails($data['registration_id'], $data['user_id']);
            if (!$registration) {
                return ['success' => false, 'message' => 'Pendaftaran tidak ditemukan'];
            }

            // Get payment method
            $paymentMethod = $this->paymentMethodModel->find($data['payment_method_id']);
            if (!$paymentMethod) {
                return ['success' => false, 'message' => 'Metode pembayaran tidak valid'];
            }

            // Calculate payment amounts
            $amounts = $this->calculatePaymentAmounts($registration, $paymentMethod);

            // Generate payment data
            $paymentData = $this->generatePaymentData($data, $amounts, $paymentMethod);

            // Create payment record
            $paymentId = $this->paymentModel->insert($paymentData);

            if ($paymentId) {
                return [
                    'success' => true,
                    'payment_id' => $paymentId,
                    'message' => 'Pembayaran berhasil dibuat'
                ];
            }

            return ['success' => false, 'message' => 'Gagal membuat pembayaran'];

        } catch (DatabaseException $e) {
            log_message('error', 'Database error in PaymentService: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan database'];
        } catch (\Exception $e) {
            log_message('error', 'Error in PaymentService: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    /**
     * Validate payment data
     */
    private function validatePaymentData(array $data): array
    {
        $required = ['registration_id', 'payment_method_id', 'user_id'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return [
                    'success' => false,
                    'message' => "Field {$field} wajib diisi"
                ];
            }
        }

        return ['success' => true];
    }

    /**
     * Get registration details with event information
     */
    private function getRegistrationDetails(int $registrationId, int $userId): ?array
    {
        return $this->registrationModel
            ->select('event_registrations.*, events.price as event_price, events.title as event_title')
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.id', $registrationId)
            ->where('event_registrations.user_id', $userId)
            ->first();
    }

    /**
     * Calculate payment amounts including fees
     */
    private function calculatePaymentAmounts(array $registration, array $paymentMethod): array
    {
        $amount = floatval($registration['event_price']);
        $adminFee = $this->paymentMethodModel->calculateAdminFee($paymentMethod['id'], $amount);
        
        return [
            'amount' => $amount,
            'admin_fee' => $adminFee,
            'total_amount' => $amount + $adminFee
        ];
    }

    /**
     * Generate payment data array
     */
    private function generatePaymentData(array $inputData, array $amounts, array $paymentMethod): array
    {
        return [
            'registration_id' => intval($inputData['registration_id']),
            'payment_method_id' => intval($inputData['payment_method_id']),
            'amount' => $amounts['amount'],
            'admin_fee' => $amounts['admin_fee'],
            'total_amount' => $amounts['total_amount'],
            'payment_code' => $this->paymentModel->generatePaymentCode(),
            'status' => 'pending',
            'expired_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'callback_data' => null,
            'external_id' => null,
            'payment_url' => null,
            'qr_code' => null,
            'virtual_account' => $this->generateVirtualAccount($paymentMethod),
            'payment_instructions' => $this->generatePaymentInstructions($paymentMethod),
            'paid_at' => null
        ];
    }

    /**
     * Generate virtual account number for bank transfers
     */
    private function generateVirtualAccount(array $paymentMethod): ?string
    {
        if ($paymentMethod['type'] === 'bank_transfer') {
            return $paymentMethod['code'] . time() . rand(1000, 9999);
        }
        return null;
    }

    /**
     * Generate payment instructions based on method type
     */
    private function generatePaymentInstructions(array $paymentMethod): ?string
    {
        $instructions = [
            'bank_transfer' => "Transfer ke rekening {$paymentMethod['name']} dengan nomor virtual account yang tertera",
            'e_wallet' => "Scan QR Code atau gunakan aplikasi {$paymentMethod['name']}",
            'credit_card' => "Gunakan kartu kredit/debit untuk pembayaran online"
        ];

        return $instructions[$paymentMethod['type']] ?? null;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $paymentId, string $status, array $additionalData = []): bool
    {
        try {
            $updateData = ['status' => $status];
            
            if ($status === 'paid') {
                $updateData['paid_at'] = date('Y-m-d H:i:s');
            }

            if (!empty($additionalData)) {
                $updateData = array_merge($updateData, $additionalData);
            }

            return $this->paymentModel->update($paymentId, $updateData);
        } catch (\Exception $e) {
            log_message('error', 'Error updating payment status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment details with related information
     */
    public function getPaymentDetails(int $paymentId, int $userId): ?array
    {
        return $this->paymentModel
            ->select('payments.*, payment_methods.name as method_name, payment_methods.type as method_type, 
                     events.title as event_title, users.name as user_name')
            ->join('event_registrations', 'event_registrations.id = payments.registration_id')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('payment_methods', 'payment_methods.id = payments.payment_method_id')
            ->where('payments.id', $paymentId)
            ->where('users.id', $userId)
            ->first();
    }
}