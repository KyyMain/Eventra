<?php

namespace App\Repositories;

use App\Models\PaymentModel;
use CodeIgniter\Database\BaseBuilder;

class PaymentRepository
{
    protected $model;
    protected $db;

    public function __construct()
    {
        $this->model = new PaymentModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Find payment by ID with related data
     */
    public function findWithRelations(int $id): ?array
    {
        return $this->model
            ->select('payments.*, payment_methods.name as method_name, payment_methods.type as method_type,
                     events.title as event_title, events.price as event_price,
                     users.name as user_name, users.email as user_email')
            ->join('event_registrations', 'event_registrations.id = payments.registration_id')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('payment_methods', 'payment_methods.id = payments.payment_method_id')
            ->where('payments.id', $id)
            ->first();
    }

    /**
     * Get user payments with pagination
     */
    public function getUserPayments(int $userId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $payments = $this->model
            ->select('payments.*, payment_methods.name as method_name, events.title as event_title')
            ->join('event_registrations', 'event_registrations.id = payments.registration_id')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('payment_methods', 'payment_methods.id = payments.payment_method_id')
            ->where('event_registrations.user_id', $userId)
            ->orderBy('payments.created_at', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        $total = $this->model
            ->join('event_registrations', 'event_registrations.id = payments.registration_id')
            ->where('event_registrations.user_id', $userId)
            ->countAllResults();

        return [
            'data' => $payments,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get expired payments
     */
    public function getExpiredPayments(): array
    {
        return $this->model
            ->where('status', 'pending')
            ->where('expired_at <', date('Y-m-d H:i:s'))
            ->findAll();
    }

    /**
     * Update payment status in bulk
     */
    public function bulkUpdateStatus(array $paymentIds, string $status): bool
    {
        try {
            $this->db->transStart();
            
            $updateData = ['status' => $status];
            if ($status === 'expired') {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
            }

            $this->model->whereIn('id', $paymentIds)->set($updateData)->update();
            
            $this->db->transComplete();
            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Bulk update payment status error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats(string $period = '30 days'): array
    {
        $dateFrom = date('Y-m-d H:i:s', strtotime("-{$period}"));
        
        $stats = $this->db->table('payments')
            ->select('status, COUNT(*) as count, SUM(total_amount) as total_amount')
            ->where('created_at >=', $dateFrom)
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $result = [
            'pending' => ['count' => 0, 'amount' => 0],
            'paid' => ['count' => 0, 'amount' => 0],
            'expired' => ['count' => 0, 'amount' => 0],
            'cancelled' => ['count' => 0, 'amount' => 0]
        ];

        foreach ($stats as $stat) {
            $result[$stat['status']] = [
                'count' => intval($stat['count']),
                'amount' => floatval($stat['total_amount'])
            ];
        }

        return $result;
    }

    /**
     * Create payment with transaction
     */
    public function createWithTransaction(array $data): int|false
    {
        try {
            $this->db->transStart();
            
            $paymentId = $this->model->insert($data);
            
            if (!$paymentId) {
                $this->db->transRollback();
                return false;
            }

            // Log payment creation
            $this->logPaymentActivity($paymentId, 'created', $data);
            
            $this->db->transComplete();
            
            return $this->db->transStatus() ? $paymentId : false;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Payment creation transaction error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Log payment activity
     */
    private function logPaymentActivity(int $paymentId, string $action, array $data = []): void
    {
        try {
            $this->db->table('payment_logs')->insert([
                'payment_id' => $paymentId,
                'action' => $action,
                'data' => json_encode($data),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Payment log error: ' . $e->getMessage());
        }
    }
}