<?php

namespace App\Events;

use CodeIgniter\Events\Events;
use App\Models\PaymentModel;

class PaymentEvents
{
    public static function register()
    {
        // Payment created event
        Events::on('payment_created', function ($paymentData) {
            self::logPaymentActivity($paymentData['id'], 'created', [
                'amount' => $paymentData['total_amount'],
                'method' => $paymentData['payment_method_id'],
                'user_id' => session()->get('user_id')
            ]);
        });

        // Payment status updated event
        Events::on('payment_status_updated', function ($paymentId, $oldStatus, $newStatus, $additionalData = []) {
            self::logPaymentActivity($paymentId, 'status_updated', [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'additional_data' => $additionalData,
                'user_id' => session()->get('user_id')
            ]);

            // Send notification if payment is paid
            if ($newStatus === 'paid') {
                Events::trigger('payment_completed', $paymentId);
            }
        });

        // Payment completed event
        Events::on('payment_completed', function ($paymentId) {
            self::handlePaymentCompleted($paymentId);
        });

        // Payment expired event
        Events::on('payment_expired', function ($paymentId) {
            self::logPaymentActivity($paymentId, 'expired', [
                'expired_at' => date('Y-m-d H:i:s'),
                'auto_expired' => true
            ]);
        });
    }

    /**
     * Log payment activity to database
     */
    private static function logPaymentActivity(int $paymentId, string $action, array $data = []): void
    {
        try {
            $db = \Config\Database::connect();
            
            // Create payment_logs table if not exists
            if (!$db->tableExists('payment_logs')) {
                self::createPaymentLogsTable($db);
            }

            $db->table('payment_logs')->insert([
                'payment_id' => $paymentId,
                'action' => $action,
                'data' => json_encode($data),
                'ip_address' => service('request')->getIPAddress(),
                'user_agent' => service('request')->getUserAgent()->getAgentString(),
                'created_at' => date('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Payment activity logging error: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment completed
     */
    private static function handlePaymentCompleted(int $paymentId): void
    {
        try {
            $paymentModel = new PaymentModel();
            $payment = $paymentModel->find($paymentId);
            
            if (!$payment) {
                return;
            }

            // Update registration status to confirmed
            $registrationModel = new \App\Models\EventRegistrationModel();
            $registrationModel->update($payment['registration_id'], [
                'status' => 'confirmed',
                'confirmed_at' => date('Y-m-d H:i:s')
            ]);

            // Send confirmation email (implement email service)
            self::sendPaymentConfirmationEmail($payment);

            // Generate certificate if needed
            self::generateCertificate($payment);

        } catch (\Exception $e) {
            log_message('error', 'Payment completion handling error: ' . $e->getMessage());
        }
    }

    /**
     * Send payment confirmation email
     */
    private static function sendPaymentConfirmationEmail(array $payment): void
    {
        try {
            // Get user and event details
            $db = \Config\Database::connect();
            $details = $db->table('payments p')
                ->select('p.*, u.name as user_name, u.email as user_email, e.title as event_title, e.date as event_date')
                ->join('event_registrations er', 'er.id = p.registration_id')
                ->join('users u', 'u.id = er.user_id')
                ->join('events e', 'e.id = er.event_id')
                ->where('p.id', $payment['id'])
                ->get()
                ->getRowArray();

            if (!$details) {
                return;
            }

            $email = \Config\Services::email();
            $email->setTo($details['user_email']);
            $email->setSubject('Konfirmasi Pembayaran - ' . $details['event_title']);
            
            $message = view('emails/payment_confirmation', [
                'user_name' => $details['user_name'],
                'event_title' => $details['event_title'],
                'event_date' => $details['event_date'],
                'payment_code' => $details['payment_code'],
                'amount' => number_format($details['total_amount'], 0, ',', '.')
            ]);
            
            $email->setMessage($message);
            $email->send();

        } catch (\Exception $e) {
            log_message('error', 'Payment confirmation email error: ' . $e->getMessage());
        }
    }

    /**
     * Generate certificate for paid events
     */
    private static function generateCertificate(array $payment): void
    {
        try {
            // Implement certificate generation logic here
            // This could involve creating PDF certificates, etc.
            
            log_message('info', "Certificate generation triggered for payment {$payment['id']}");
            
        } catch (\Exception $e) {
            log_message('error', 'Certificate generation error: ' . $e->getMessage());
        }
    }

    /**
     * Create payment logs table
     */
    private static function createPaymentLogsTable($db): void
    {
        $forge = \Config\Database::forge();
        
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'payment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'data' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ];

        $forge->addField($fields);
        $forge->addKey('id', true);
        $forge->addKey('payment_id');
        $forge->addKey('action');
        $forge->addKey('created_at');
        $forge->createTable('payment_logs', true);
    }
}