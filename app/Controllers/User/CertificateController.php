<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\EventRegistrationModel;
use App\Models\EventModel;

class CertificateController extends BaseController
{
    protected $registrationModel;
    protected $eventModel;

    public function __construct()
    {
        $this->registrationModel = new EventRegistrationModel();
        $this->eventModel = new EventModel();
    }

    public function index()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get user's certificates (registrations with attended status)
        $certificates = $this->registrationModel
            ->select('event_registrations.*, 
                     events.title as event_title, 
                     events.start_date as event_start_date, 
                     events.end_date as event_end_date, 
                     events.speaker as event_speaker, 
                     events.type as event_type')
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.user_id', $userId)
            ->orderBy('event_registrations.created_at', 'DESC')
            ->findAll();

        // Generate certificate codes for attended events if not exists
        foreach ($certificates as &$cert) {
            if ($cert['status'] === 'attended' && empty($cert['certificate_code'])) {
                $cert['certificate_code'] = $this->generateCertificateCode($cert['id'], $cert['event_id']);
                
                // Update the database with the certificate code
                $this->registrationModel->update($cert['id'], [
                    'certificate_code' => $cert['certificate_code'],
                    'certificate_issued' => true
                ]);
            }
        }

        $data = [
            'title' => 'Sertifikat Saya',
            'certificates' => $certificates
        ];

        return view('user/certificates/index', $data);
    }

    public function view($registrationId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get registration details
        $registration = $this->registrationModel
            ->select('event_registrations.*, 
                     events.title as event_title, 
                     events.start_date as event_start_date, 
                     events.end_date as event_end_date, 
                     events.speaker as event_speaker, 
                     events.type as event_type, 
                     events.description as event_description, 
                     users.full_name')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.id', $registrationId)
            ->where('event_registrations.user_id', $userId)
            ->first();

        if (!$registration) {
            return redirect()->to('/user/certificates')->with('error', 'Sertifikat tidak ditemukan');
        }

        if ($registration['status'] !== 'attended') {
            return redirect()->to('/user/certificates')->with('error', 'Sertifikat belum tersedia. Anda harus hadir di event untuk mendapatkan sertifikat.');
        }

        // Generate certificate code if not exists
        if (empty($registration['certificate_code'])) {
            $registration['certificate_code'] = $this->generateCertificateCode($registration['id'], $registration['event_id']);
            
            // Update the database
            $this->registrationModel->update($registration['id'], [
                'certificate_code' => $registration['certificate_code'],
                'certificate_issued' => true
            ]);
        }

        $data = [
            'title' => 'Sertifikat - ' . $registration['event_title'],
            'registration' => $registration
        ];

        return view('user/certificates/certificate', $data);
    }

    public function download($registrationId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = session()->get('user_id');
        
        // Get registration details
        $registration = $this->registrationModel
            ->select('event_registrations.*, 
                     events.title as event_title, 
                     events.start_date as event_start_date, 
                     events.end_date as event_end_date, 
                     events.speaker as event_speaker, 
                     events.type as event_type, 
                     users.full_name')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.id', $registrationId)
            ->where('event_registrations.user_id', $userId)
            ->first();

        if (!$registration) {
            return redirect()->to('/user/certificates')->with('error', 'Sertifikat tidak ditemukan');
        }

        if ($registration['status'] !== 'attended') {
            return redirect()->to('/user/certificates')->with('error', 'Sertifikat belum tersedia. Anda harus hadir di event untuk mendapatkan sertifikat.');
        }

        // Generate certificate code if not exists
        if (empty($registration['certificate_code'])) {
            $registration['certificate_code'] = $this->generateCertificateCode($registration['id'], $registration['event_id']);
            
            // Update the database
            $this->registrationModel->update($registration['id'], [
                'certificate_code' => $registration['certificate_code'],
                'certificate_issued' => true
            ]);
        }

        // For now, redirect to view (in a real app, you might generate a PDF)
        return $this->view($registrationId);
    }

    public function verify()
    {
        $certificateCode = $this->request->getPost('certificate_code');
        
        if (!$certificateCode) {
            // If no code provided, show the verification form
            $data = [
                'title' => 'Verifikasi Sertifikat',
                'certificate' => null
            ];
            return view('user/certificates/verify', $data);
        }

        // Search for certificate by code
        $certificate = $this->registrationModel
            ->select('event_registrations.*, 
                     events.title as event_title, 
                     events.start_date as event_start_date, 
                     events.end_date as event_end_date, 
                     events.speaker as event_speaker, 
                     events.type as event_type, 
                     users.full_name as user_name')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.certificate_code', $certificateCode)
            ->where('event_registrations.status', 'attended')
            ->first();

        if (!$certificate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode sertifikat tidak ditemukan atau tidak valid. Pastikan kode yang Anda masukkan benar.');
        }

        $data = [
            'title' => 'Verifikasi Sertifikat',
            'certificate' => $certificate,
            'code' => $certificateCode
        ];

        return view('user/certificates/verify', $data);
    }

    private function generateCertificateCode($registrationId, $eventId)
    {
        // Generate a unique certificate code
        $prefix = 'CERT';
        $eventCode = str_pad($eventId, 4, '0', STR_PAD_LEFT);
        $regCode = str_pad($registrationId, 6, '0', STR_PAD_LEFT);
        $timestamp = date('ymd');
        
        return $prefix . '-' . $eventCode . '-' . $regCode . '-' . $timestamp;
    }
}