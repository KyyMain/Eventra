<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'registration_id', 'payment_method_id', 'amount', 'admin_fee', 'total_amount',
        'payment_code', 'external_id', 'status', 'payment_url', 'qr_code', 
        'virtual_account', 'payment_instructions', 'paid_at', 'expired_at', 'callback_data'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        // Remove JSON casting for callback_data to avoid issues
        // 'callback_data' => 'json'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'registration_id' => 'required|integer',
        'payment_method_id' => 'required|integer',
        'amount' => 'required|decimal',
        'total_amount' => 'required|decimal',
        'payment_code' => 'required|max_length[100]|is_unique[payments.payment_code]',
        'status' => 'permit_empty|in_list[pending,processing,paid,failed,expired,cancelled]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultCallbackData'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Set default callback_data if not provided
     */
    protected function setDefaultCallbackData(array $data)
    {
        if (!isset($data['data']['callback_data'])) {
            $data['data']['callback_data'] = null;
        }
        return $data;
    }

    /**
     * Set callback data as JSON string
     */
    public function setCallbackData($paymentId, $data)
    {
        return $this->update($paymentId, [
            'callback_data' => $data ? json_encode($data) : null
        ]);
    }

    /**
     * Get callback data as array
     */
    public function getCallbackData($payment)
    {
        if (empty($payment['callback_data'])) {
            return null;
        }
        
        return json_decode($payment['callback_data'], true);
    }

    /**
     * Get payment with related data
     */
    public function getPaymentWithDetails($paymentId)
    {
        return $this->select('payments.*, payment_methods.name as payment_method_name, 
                             payment_methods.code as payment_method_code, payment_methods.type as payment_method_type,
                             event_registrations.event_id, event_registrations.user_id,
                             events.title as event_title, users.full_name as user_name, users.email as user_email')
                   ->join('payment_methods', 'payment_methods.id = payments.payment_method_id')
                   ->join('event_registrations', 'event_registrations.id = payments.registration_id')
                   ->join('events', 'events.id = event_registrations.event_id')
                   ->join('users', 'users.id = event_registrations.user_id')
                   ->where('payments.id', $paymentId)
                   ->first();
    }

    /**
     * Get payments by registration
     */
    public function getByRegistration($registrationId)
    {
        return $this->select('payments.*, payment_methods.name as payment_method_name, 
                             payment_methods.code as payment_method_code')
                   ->join('payment_methods', 'payment_methods.id = payments.payment_method_id')
                   ->where('registration_id', $registrationId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get payment by code
     */
    public function getByPaymentCode($paymentCode)
    {
        return $this->where('payment_code', $paymentCode)->first();
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($paymentId, $status, $additionalData = [])
    {
        $updateData = array_merge(['status' => $status], $additionalData);
        
        if ($status === 'paid') {
            $updateData['paid_at'] = date('Y-m-d H:i:s');
        }

        return $this->update($paymentId, $updateData);
    }

    /**
     * Generate unique payment code
     */
    public function generatePaymentCode($prefix = 'PAY')
    {
        do {
            $code = $prefix . date('Ymd') . rand(100000, 999999);
        } while ($this->where('payment_code', $code)->first());

        return $code;
    }

    /**
     * Get expired payments
     */
    public function getExpiredPayments()
    {
        return $this->where('status', 'pending')
                   ->where('expired_at <', date('Y-m-d H:i:s'))
                   ->findAll();
    }
}