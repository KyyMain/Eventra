<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'description', 'type', 'speaker', 'location', 'start_date', 'end_date',
        'max_participants', 'current_participants', 'price', 'image', 'status',
        'certificate_template', 'created_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title'       => 'required|min_length[3]|max_length[255]',
        'description' => 'required',
        'type'        => 'required|in_list[seminar,workshop,conference,training]',
        'speaker'     => 'required|min_length[3]|max_length[255]',
        'location'    => 'required|min_length[3]|max_length[255]',
        'start_date'  => 'required|valid_date',
        'end_date'    => 'required|valid_date',
        'max_participants' => 'required|integer|greater_than[0]',
        'price'       => 'required|decimal',
        'status'      => 'required|in_list[draft,published,cancelled,completed]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPublishedEvents()
    {
        return $this->select('events.*, users.full_name as creator_name')
                    ->join('users', 'users.id = events.created_by')
                    ->where('events.status', 'published')
                    ->where('events.start_date >', date('Y-m-d H:i:s'))
                    ->orderBy('events.start_date', 'ASC')
                    ->findAll();
    }

    public function getEventsByType($type)
    {
        return $this->where('type', $type)
                    ->where('status', 'published')
                    ->where('start_date >', date('Y-m-d H:i:s'))
                    ->orderBy('start_date', 'ASC')
                    ->findAll();
    }

    public function getUpcomingEvents($limit = 5)
    {
        return $this->where('status', 'published')
                    ->where('start_date >', date('Y-m-d H:i:s'))
                    ->orderBy('start_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getEventWithCreator($id)
    {
        return $this->select('events.*, users.full_name as creator_name, users.email as creator_email')
                    ->join('users', 'users.id = events.created_by')
                    ->where('events.id', $id)
                    ->first();
    }

    public function getEventStats()
    {
        $stats = [];
        $stats['total_events'] = $this->countAll();
        $stats['published_events'] = $this->where('status', 'published')->countAllResults();
        $stats['upcoming_events'] = $this->where('status', 'published')
                                         ->where('start_date >', date('Y-m-d H:i:s'))
                                         ->countAllResults();
        $stats['completed_events'] = $this->where('status', 'completed')->countAllResults();
        
        return $stats;
    }

    public function incrementParticipants($eventId)
    {
        $event = $this->find($eventId);
        if ($event) {
            $this->update($eventId, [
                'current_participants' => $event['current_participants'] + 1
            ]);
        }
    }

    public function decrementParticipants($eventId)
    {
        $event = $this->find($eventId);
        if ($event && $event['current_participants'] > 0) {
            $this->update($eventId, [
                'current_participants' => $event['current_participants'] - 1
            ]);
        }
    }
}