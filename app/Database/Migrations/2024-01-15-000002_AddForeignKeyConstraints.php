<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyConstraints extends Migration
{
    public function up()
    {
        // Add foreign key constraints for better data integrity
        
        // Event registrations - event_id references events.id
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE', 'fk_registrations_event_id');
        $this->forge->processIndexes('event_registrations');
        
        // Event registrations - user_id references users.id
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_registrations_user_id');
        $this->forge->processIndexes('event_registrations');
    }

    public function down()
    {
        // Drop foreign key constraints
        $this->forge->dropForeignKey('event_registrations', 'fk_registrations_event_id');
        $this->forge->dropForeignKey('event_registrations', 'fk_registrations_user_id');
    }
}