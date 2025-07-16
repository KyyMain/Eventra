<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixCallbackDataType extends Migration
{
    public function up()
    {
        // Change callback_data from JSON to TEXT to avoid compatibility issues
        $this->forge->modifyColumn('payments', [
            'callback_data' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        // Revert back to JSON type
        $this->forge->modifyColumn('payments', [
            'callback_data' => [
                'type' => 'JSON',
                'null' => true,
            ]
        ]);
    }
}