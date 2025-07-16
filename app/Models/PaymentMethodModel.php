<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table            = 'payment_methods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'code', 'type', 'icon', 'is_active', 'admin_fee', 'admin_fee_type'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => 'required|max_length[100]',
        'code' => 'required|max_length[50]|is_unique[payment_methods.code]',
        'type' => 'required|in_list[ewallet,bank_transfer,qris,credit_card]',
        'admin_fee' => 'permit_empty|decimal',
        'admin_fee_type' => 'permit_empty|in_list[fixed,percentage]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get active payment methods
     */
    public function getActivePaymentMethods()
    {
        return $this->where('is_active', true)
                   ->orderBy('type', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Get payment methods by type
     */
    public function getByType($type)
    {
        return $this->where('type', $type)
                   ->where('is_active', true)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Calculate admin fee
     */
    public function calculateAdminFee($paymentMethodId, $amount)
    {
        $paymentMethod = $this->find($paymentMethodId);
        
        if (!$paymentMethod || !$paymentMethod['admin_fee']) {
            return 0;
        }

        if ($paymentMethod['admin_fee_type'] === 'percentage') {
            return round(($amount * floatval($paymentMethod['admin_fee'])) / 100);
        }

        return floatval($paymentMethod['admin_fee']);
    }
}