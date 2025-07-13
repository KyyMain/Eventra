<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatabaseIndexes extends Migration
{
    public function up()
    {
        // Add indexes for users table
        $this->forge->addKey('email', false, true, 'idx_users_email');
        $this->forge->addKey('is_active', false, false, 'idx_users_is_active');
        $this->forge->addKey('created_at', false, false, 'idx_users_created_at');
        $this->forge->addKey(['deleted_at'], false, false, 'idx_users_deleted_at');
        
        // Add indexes for events table
        $this->forge->addKey('user_id', false, false, 'idx_events_user_id');
        $this->forge->addKey('status', false, false, 'idx_events_status');
        $this->forge->addKey('event_date', false, false, 'idx_events_event_date');
        $this->forge->addKey('created_at', false, false, 'idx_events_created_at');
        
        // Add indexes for registrations table
        $this->forge->addKey('event_id', false, false, 'idx_registrations_event_id');
        $this->forge->addKey('user_id', false, false, 'idx_registrations_user_id');
        $this->forge->addKey('status', false, false, 'idx_registrations_status');
        $this->forge->addKey('created_at', false, false, 'idx_registrations_created_at');
        
        // Add composite indexes for common queries
        $this->forge->addKey(['user_id', 'status'], false, false, 'idx_events_user_status');
        $this->forge->addKey(['event_id', 'user_id'], false, false, 'idx_registrations_event_user');
    }

    public function down()
    {
        // Drop indexes
        $indexes = [
            'idx_users_email',
            'idx_users_is_active', 
            'idx_users_created_at',
            'idx_users_deleted_at',
            'idx_events_user_id',
            'idx_events_status',
            'idx_events_event_date',
            'idx_events_created_at',
            'idx_registrations_event_id',
            'idx_registrations_user_id',
            'idx_registrations_status',
            'idx_registrations_created_at',
            'idx_events_user_status',
            'idx_registrations_event_user'
        ];
        
        foreach ($indexes as $index) {
            try {
                $this->forge->dropKey('', $index);
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        }
    }
}