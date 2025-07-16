<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // E-Wallet
            [
                'name' => 'DANA',
                'code' => 'dana',
                'type' => 'ewallet',
                'icon' => 'dana-icon.png',
                'is_active' => true,
                'admin_fee' => 2500,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'OVO',
                'code' => 'ovo',
                'type' => 'ewallet',
                'icon' => 'ovo-icon.png',
                'is_active' => true,
                'admin_fee' => 2500,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'GoPay',
                'code' => 'gopay',
                'type' => 'ewallet',
                'icon' => 'gopay-icon.png',
                'is_active' => true,
                'admin_fee' => 2500,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'ShopeePay',
                'code' => 'shopeepay',
                'type' => 'ewallet',
                'icon' => 'shopeepay-icon.png',
                'is_active' => true,
                'admin_fee' => 2500,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // QRIS
            [
                'name' => 'QRIS',
                'code' => 'qris',
                'type' => 'qris',
                'icon' => 'qris-icon.png',
                'is_active' => true,
                'admin_fee' => 0.7,
                'admin_fee_type' => 'percentage',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Bank Transfer
            [
                'name' => 'Bank BCA',
                'code' => 'bca_va',
                'type' => 'bank_transfer',
                'icon' => 'bca-icon.png',
                'is_active' => true,
                'admin_fee' => 4000,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bank Mandiri',
                'code' => 'mandiri_va',
                'type' => 'bank_transfer',
                'icon' => 'mandiri-icon.png',
                'is_active' => true,
                'admin_fee' => 4000,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bank BNI',
                'code' => 'bni_va',
                'type' => 'bank_transfer',
                'icon' => 'bni-icon.png',
                'is_active' => true,
                'admin_fee' => 4000,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bank BRI',
                'code' => 'bri_va',
                'type' => 'bank_transfer',
                'icon' => 'bri-icon.png',
                'is_active' => true,
                'admin_fee' => 4000,
                'admin_fee_type' => 'fixed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('payment_methods')->insertBatch($data);
    }
}