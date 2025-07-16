<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PaymentModel;
use App\Models\EventRegistrationModel;

class PaymentCleanup extends BaseCommand
{
    protected $group       = 'payment';
    protected $name        = 'payment:cleanup';
    protected $description = 'Cleanup expired payments and update registration status';

    protected $usage = 'payment:cleanup [options]';
    protected $arguments = [];
    protected $options = [
        '--dry-run' => 'Show what would be cleaned up without making changes',
        '--force'   => 'Force cleanup without confirmation'
    ];

    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run');
        $force = CLI::getOption('force');

        CLI::write('Payment Cleanup Tool', 'yellow');
        CLI::write('==================', 'yellow');

        try {
            $paymentModel = new PaymentModel();
            $registrationModel = new EventRegistrationModel();

            // Get expired payments
            $expiredPayments = $paymentModel
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->findAll();

            if (empty($expiredPayments)) {
                CLI::write('No expired payments found.', 'green');
                return;
            }

            CLI::write('Found ' . count($expiredPayments) . ' expired payments:', 'red');

            foreach ($expiredPayments as $payment) {
                CLI::write("- Payment ID: {$payment['id']}, Code: {$payment['payment_code']}, Expired: {$payment['expired_at']}", 'white');
            }

            if ($dryRun) {
                CLI::write('DRY RUN: No changes made.', 'yellow');
                return;
            }

            if (!$force) {
                $confirm = CLI::prompt('Do you want to proceed with cleanup?', ['y', 'n']);
                if ($confirm !== 'y') {
                    CLI::write('Cleanup cancelled.', 'yellow');
                    return;
                }
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $updatedCount = 0;
            $errorCount = 0;

            foreach ($expiredPayments as $payment) {
                try {
                    // Update payment status to expired
                    $paymentModel->update($payment['id'], [
                        'status' => 'expired',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // Update registration status back to pending
                    $registrationModel->update($payment['registration_id'], [
                        'status' => 'pending',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    $updatedCount++;
                    CLI::write("✓ Updated payment {$payment['payment_code']}", 'green');

                } catch (\Exception $e) {
                    $errorCount++;
                    CLI::write("✗ Error updating payment {$payment['payment_code']}: " . $e->getMessage(), 'red');
                    log_message('error', 'Payment cleanup error: ' . $e->getMessage());
                }
            }

            $db->transComplete();

            if ($db->transStatus()) {
                CLI::write("Cleanup completed successfully!", 'green');
                CLI::write("Updated: {$updatedCount} payments", 'green');
                if ($errorCount > 0) {
                    CLI::write("Errors: {$errorCount} payments", 'red');
                }
            } else {
                CLI::write("Transaction failed. No changes made.", 'red');
            }

        } catch (\Exception $e) {
            CLI::write('Error during cleanup: ' . $e->getMessage(), 'red');
            log_message('error', 'Payment cleanup command error: ' . $e->getMessage());
        }
    }
}