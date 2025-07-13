<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Seminar Digital Marketing 2024',
                'description' => 'Pelajari strategi digital marketing terbaru untuk meningkatkan bisnis Anda di era digital.',
                'type' => 'seminar',
                'speaker' => 'Dr. Ahmad Wijaya',
                'location' => 'Hotel Grand Indonesia, Jakarta',
                'start_date' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+7 days +4 hours')),
                'max_participants' => 150,
                'current_participants' => 0,
                'price' => 250000.00,
                'status' => 'published',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Workshop Web Development dengan Laravel',
                'description' => 'Workshop intensif untuk mempelajari pengembangan web menggunakan framework Laravel.',
                'type' => 'workshop',
                'speaker' => 'Budi Santoso, S.Kom',
                'location' => 'Universitas Indonesia, Depok',
                'start_date' => date('Y-m-d H:i:s', strtotime('+14 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+16 days')),
                'max_participants' => 50,
                'current_participants' => 0,
                'price' => 500000.00,
                'status' => 'published',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Conference AI & Machine Learning',
                'description' => 'Konferensi internasional tentang perkembangan terbaru dalam bidang AI dan Machine Learning.',
                'type' => 'conference',
                'speaker' => 'Prof. Sarah Johnson',
                'location' => 'Jakarta Convention Center',
                'start_date' => date('Y-m-d H:i:s', strtotime('+21 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+23 days')),
                'max_participants' => 500,
                'current_participants' => 0,
                'price' => 750000.00,
                'status' => 'published',
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Check if events already exist before inserting
        foreach ($data as $event) {
            $existingEvent = $this->db->table('events')
                ->where('title', $event['title'])
                ->get()
                ->getRow();

            if (!$existingEvent) {
                $this->db->table('events')->insert($event);
                echo "Event '{$event['title']}' created successfully.\n";
            } else {
                echo "Event '{$event['title']}' already exists, skipping.\n";
            }
        }
    }
}