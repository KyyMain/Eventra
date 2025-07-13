<?php

namespace App\Models;

use CodeIgniter\Model;

class EventRegistrationModel extends Model
{
    protected $table            = 'event_registrations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_id', 'user_id', 'registration_date', 'status', 'payment_status',
        'certificate_issued', 'certificate_code'
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
        'event_id' => 'required|integer',
        'user_id'  => 'required|integer',
        'status'   => 'required|in_list[registered,attended,cancelled]',
        'payment_status' => 'required|in_list[pending,paid,refunded]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setRegistrationDate', 'generateCertificateCode'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setRegistrationDate(array $data)
    {
        if (!isset($data['data']['registration_date'])) {
            $data['data']['registration_date'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    protected function generateCertificateCode(array $data)
    {
        if (!isset($data['data']['certificate_code'])) {
            $data['data']['certificate_code'] = 'CERT-' . strtoupper(uniqid());
        }
        return $data;
    }

    public function getUserRegistrations($userId)
    {
        return $this->select('event_registrations.*, events.title, events.type, events.start_date, events.end_date, events.location, events.speaker')
                    ->join('events', 'events.id = event_registrations.event_id')
                    ->where('event_registrations.user_id', $userId)
                    ->orderBy('event_registrations.registration_date', 'DESC')
                    ->findAll();
    }

    public function getEventRegistrations($eventId)
    {
        return $this->select('event_registrations.*, users.full_name, users.email, users.phone')
                    ->join('users', 'users.id = event_registrations.user_id')
                    ->where('event_registrations.event_id', $eventId)
                    ->orderBy('event_registrations.registration_date', 'ASC')
                    ->findAll();
    }

    public function isUserRegistered($eventId, $userId)
    {
        return $this->where('event_id', $eventId)
                    ->where('user_id', $userId)
                    ->where('status !=', 'cancelled')
                    ->first() !== null;
    }

    public function getUserRegistration($eventId, $userId)
    {
        return $this->where('event_id', $eventId)
                    ->where('user_id', $userId)
                    ->first();
    }

    public function getRegistrationStats()
    {
        $stats = [];
        $stats['total_registrations'] = $this->countAll();
        $stats['active_registrations'] = $this->where('status', 'registered')->countAllResults();
        $stats['attended'] = $this->where('status', 'attended')->countAllResults();
        $stats['cancelled'] = $this->where('status', 'cancelled')->countAllResults();
        $stats['certificates_issued'] = $this->where('certificate_issued', true)->countAllResults();
        
        return $stats;
    }

    public function getAttendedRegistrations($userId)
    {
        return $this->select('event_registrations.*, events.title, events.type, events.start_date, events.end_date')
                    ->join('events', 'events.id = event_registrations.event_id')
                    ->where('event_registrations.user_id', $userId)
                    ->where('event_registrations.status', 'attended')
                    ->orderBy('events.end_date', 'DESC')
                    ->findAll();
    }

    public function issueCertificate($registrationId)
    {
        return $this->update($registrationId, [
            'certificate_issued' => true
        ]);
    }

    public function getRegistrationByCertificateCode($code)
    {
        return $this->select('event_registrations.*, events.title, events.type, events.start_date, events.end_date, events.speaker, users.full_name')
                    ->join('events', 'events.id = event_registrations.event_id')
                    ->join('users', 'users.id = event_registrations.user_id')
                    ->where('event_registrations.certificate_code', $code)
                    ->where('event_registrations.certificate_issued', true)
                    ->first();
    }
}