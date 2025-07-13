<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@eventra.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'  => 'Administrator',
                'phone'      => '081234567890',
                'role'       => 'admin',
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'user',
                'email'      => 'user@eventra.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'full_name'  => 'Demo User',
                'phone'      => '081234567891',
                'role'       => 'user',
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'user2',
                'email'      => 'user2@example.com',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'full_name'  => 'Jane Smith',
                'phone'      => '081234567892',
                'role'       => 'user',
                'is_active'  => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Check if users already exist before inserting
        foreach ($data as $user) {
            $existingUser = $this->db->table('users')
                ->where('username', $user['username'])
                ->orWhere('email', $user['email'])
                ->get()
                ->getRow();

            if (!$existingUser) {
                $this->db->table('users')->insert($user);
                echo "User '{$user['username']}' created successfully.\n";
            } else {
                echo "User '{$user['username']}' already exists, skipping.\n";
            }
        }
    }
}