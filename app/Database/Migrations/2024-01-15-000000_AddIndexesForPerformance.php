<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesForPerformance extends Migration
{
    public function up()
    {
        // Add indexes for better query performance
        
        // Users table indexes (skip email and username as they already have unique constraints)
        $this->forge->addKey(['is_active'], false, false, 'idx_users_is_active');
        $this->forge->addKey(['created_at'], false, false, 'idx_users_created_at');
        $this->forge->addKey(['role'], false, false, 'idx_users_role');
        $this->forge->processIndexes('users');
        
        // Events table indexes
        $this->forge->addKey(['status'], false, false, 'idx_events_status');
        $this->forge->addKey(['start_date'], false, false, 'idx_events_start_date');
        $this->forge->addKey(['created_at'], false, false, 'idx_events_created_at');
        $this->forge->addKey(['type'], false, false, 'idx_events_type');
        $this->forge->processIndexes('events');
        
        // Event registrations table indexes
        $this->forge->addKey(['event_id'], false, false, 'idx_registrations_event_id');
        $this->forge->addKey(['user_id'], false, false, 'idx_registrations_user_id');
        $this->forge->addKey(['status'], false, false, 'idx_registrations_status');
        $this->forge->addKey(['created_at'], false, false, 'idx_registrations_created_at');
        $this->forge->addKey(['event_id', 'user_id'], false, true, 'idx_registrations_event_user');
        $this->forge->addKey(['event_id', 'status'], false, false, 'idx_registrations_event_status');
        $this->forge->processIndexes('event_registrations');
    }

    public function down()
    {
        // Drop indexes
        $this->forge->dropKey('users', 'idx_users_is_active');
        $this->forge->dropKey('users', 'idx_users_created_at');
        $this->forge->dropKey('users', 'idx_users_role');
        
        $this->forge->dropKey('events', 'idx_events_status');
        $this->forge->dropKey('events', 'idx_events_start_date');
        $this->forge->dropKey('events', 'idx_events_created_at');
        $this->forge->dropKey('events', 'idx_events_type');
        
        $this->forge->dropKey('event_registrations', 'idx_registrations_event_id');
        $this->forge->dropKey('event_registrations', 'idx_registrations_user_id');
        $this->forge->dropKey('event_registrations', 'idx_registrations_status');
        $this->forge->dropKey('event_registrations', 'idx_registrations_created_at');
        $this->forge->dropKey('event_registrations', 'idx_registrations_event_user');
        $this->forge->dropKey('event_registrations', 'idx_registrations_event_status');
    }
}