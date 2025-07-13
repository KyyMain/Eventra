<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\EventRegistrationModel;

class UserController extends BaseController
{
    protected $eventModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->registrationModel = new EventRegistrationModel();
        helper(['form', 'url']);
    }

    private function checkUserAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        return null;
    }

    public function dashboard()
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        
        // Get upcoming events
        $upcomingEvents = $this->eventModel->getUpcomingEvents(6);
        
        // Get user registrations
        $userRegistrations = $this->registrationModel->getUserRegistrations($userId);
        
        // Get user statistics
        $userStats = [
            'total_registrations' => count($userRegistrations),
            'attended_events' => count(array_filter($userRegistrations, function($reg) {
                return $reg['status'] === 'attended';
            })),
            'upcoming_events' => count(array_filter($userRegistrations, function($reg) {
                $eventStartDate = $reg['event_start_date'] ?? null;
                return $reg['status'] === 'registered' && $eventStartDate && strtotime($eventStartDate) > time();
            })),
            'certificates_earned' => count(array_filter($userRegistrations, function($reg) {
                return $reg['certificate_issued'] == 1;
            }))
        ];

        $data = [
            'title' => 'Dashboard User - Eventra',
            'upcoming_events' => $upcomingEvents,
            'user_registrations' => array_slice($userRegistrations, 0, 5), // Recent 5
            'stats' => $userStats
        ];

        return view('user/dashboard', $data);
    }

    public function events()
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $type = $this->request->getGet('type');
        $search = $this->request->getGet('search');

        $events = $this->eventModel->getPublishedEvents();

        // Filter by type
        if ($type && $type !== 'all') {
            $events = array_filter($events, function($event) use ($type) {
                return $event['type'] === $type;
            });
        }

        // Filter by search
        if ($search) {
            $events = array_filter($events, function($event) use ($search) {
                return stripos($event['title'], $search) !== false || 
                       stripos($event['description'], $search) !== false ||
                       stripos($event['speaker'], $search) !== false;
            });
        }

        $data = [
            'title' => 'Event Tersedia - Eventra',
            'events' => $events,
            'currentType' => $type,
            'currentSearch' => $search
        ];

        return view('user/events', $data);
    }

    public function eventDetail($id)
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $event = $this->eventModel->getEventWithCreator($id);
        if (!$event || $event['status'] !== 'published') {
            return redirect()->to('/user/events')->with('error', 'Event tidak ditemukan atau tidak tersedia.');
        }

        $userId = session()->get('user_id');
        $isRegistered = $this->registrationModel->isUserRegistered($id, $userId);
        $userRegistration = null;
        
        if ($isRegistered) {
            $userRegistration = $this->registrationModel->getUserRegistration($id, $userId);
        }

        $data = [
            'title' => $event['title'] . ' - Eventra',
            'event' => $event,
            'isRegistered' => $isRegistered,
            'userRegistration' => $userRegistration
        ];

        return view('user/event_detail', $data);
    }

    public function registerEvent($eventId)
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        
        // Check if event exists and is published
        $event = $this->eventModel->find($eventId);
        if (!$event || $event['status'] !== 'published') {
            return redirect()->to('/user/events')->with('error', 'Event tidak ditemukan atau tidak tersedia.');
        }

        // Check if event is full
        if ($event['current_participants'] >= $event['max_participants']) {
            return redirect()->back()->with('error', 'Event sudah penuh.');
        }

        // Check if user already registered (including cancelled registrations)
        $existingRegistration = $this->registrationModel->getUserRegistration($eventId, $userId);
        if ($existingRegistration) {
            if ($existingRegistration['status'] === 'cancelled') {
                // Reactivate cancelled registration instead of creating new one
                $updateData = [
                    'status' => 'registered',
                    'payment_status' => $event['price'] > 0 ? 'pending' : 'paid',
                    'registration_date' => date('Y-m-d H:i:s')
                ];
                
                if ($this->registrationModel->update($existingRegistration['id'], $updateData)) {
                    // Increment participant count
                    $this->eventModel->incrementParticipants($eventId);
                    return redirect()->back()->with('success', 'Berhasil mendaftar event! Silakan lakukan pembayaran jika diperlukan.');
                } else {
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar event.');
                }
            } else {
                return redirect()->back()->with('error', 'Anda sudah terdaftar untuk event ini.');
            }
        }

        // Check if event has started - use correct field name
        $startDate = isset($event['start_date']) ? $event['start_date'] : $event['event_start_date'] ?? null;
        if ($startDate && strtotime($startDate) <= time()) {
            return redirect()->back()->with('error', 'Pendaftaran sudah ditutup.');
        }

        $registrationData = [
            'event_id' => $eventId,
            'user_id' => $userId,
            'status' => 'registered',
            'payment_status' => $event['price'] > 0 ? 'pending' : 'paid'
        ];

        try {
            if ($this->registrationModel->insert($registrationData)) {
                // Increment participant count
                $this->eventModel->incrementParticipants($eventId);
                
                return redirect()->back()->with('success', 'Berhasil mendaftar event! Silakan lakukan pembayaran jika diperlukan.');
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar event.');
            }
        } catch (\Exception $e) {
            // Handle duplicate entry error specifically
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'event_id_user_id') !== false) {
                return redirect()->back()->with('error', 'Anda sudah terdaftar untuk event ini.');
            }
            
            // Log the error for debugging
            log_message('error', 'Event registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar event. Silakan coba lagi.');
        }
    }

    public function cancelRegistration($registrationId)
    {
        // Enhanced debug logging
        log_message('info', 'Cancel registration called with ID: ' . $registrationId);
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'Request URI: ' . $this->request->getUri());
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
        
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        log_message('info', 'User ID: ' . $userId);
        
        $registration = $this->registrationModel->find($registrationId);
        log_message('info', 'Registration found: ' . ($registration ? 'Yes' : 'No'));
        
        if (!$registration || $registration['user_id'] != $userId) {
            log_message('error', 'Registration not found or user mismatch');
            return redirect()->to('/user/my-events')->with('error', 'Pendaftaran tidak ditemukan.');
        }

        // Check if already cancelled
        if ($registration['status'] === 'cancelled') {
            return redirect()->back()->with('error', 'Pendaftaran sudah dibatalkan sebelumnya.');
        }

        $event = $this->eventModel->find($registration['event_id']);
        
        // Check if event has started - use correct field name
        $startDate = isset($event['start_date']) ? $event['start_date'] : $event['event_start_date'] ?? null;
        if ($startDate && strtotime($startDate) <= time()) {
            return redirect()->back()->with('error', 'Tidak dapat membatalkan pendaftaran setelah event dimulai.');
        }

        // Update status to cancelled instead of deleting
        if ($this->registrationModel->update($registrationId, ['status' => 'cancelled'])) {
            // Decrement participant count only if status was 'registered'
            if ($registration['status'] === 'registered') {
                $this->eventModel->decrementParticipants($registration['event_id']);
            }
            
            log_message('info', 'Registration cancelled successfully');
            return redirect()->back()->with('success', 'Pendaftaran berhasil dibatalkan.');
        } else {
            log_message('error', 'Failed to update registration status');
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pendaftaran.');
        }
    }

    public function myEvents()
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        $status = $this->request->getGet('status');
        
        // Get all registrations first
        $registrations = $this->registrationModel->getUserRegistrations($userId);
        
        // Filter based on status if provided
        if ($status && $status !== 'all') {
            $registrations = array_filter($registrations, function($registration) use ($status) {
                switch ($status) {
                    case 'upcoming':
                        return $registration['status'] === 'registered' && strtotime($registration['event_start_date']) > time();
                    case 'attended':
                        return $registration['status'] === 'attended';
                    case 'cancelled':
                        return $registration['status'] === 'cancelled';
                    default:
                        return true;
                }
            });
        }

        $data = [
            'title' => 'Event Saya - Eventra',
            'registrations' => $registrations
        ];

        return view('user/my_events', $data);
    }

    public function certificates()
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        $attendedEvents = $this->registrationModel->getAttendedRegistrations($userId);

        $data = [
            'title' => 'Sertifikat Saya - Eventra',
            'certificates' => $attendedEvents
        ];

        return view('user/certificates', $data);
    }

    public function downloadCertificate($registrationId)
    {
        $authCheck = $this->checkUserAuth();
        if ($authCheck) return $authCheck;

        $userId = session()->get('user_id');
        $registration = $this->registrationModel
            ->select('event_registrations.*, 
                     events.title as event_title, 
                     events.type as event_type, 
                     events.start_date as event_start_date, 
                     events.end_date as event_end_date, 
                     events.speaker as event_speaker, 
                     users.full_name')
            ->join('events', 'events.id = event_registrations.event_id')
            ->join('users', 'users.id = event_registrations.user_id')
            ->where('event_registrations.id', $registrationId)
            ->where('event_registrations.user_id', $userId)
            ->where('event_registrations.status', 'attended')
            ->first();

        if (!$registration) {
            return redirect()->to('/user/certificates')->with('error', 'Sertifikat tidak ditemukan.');
        }

        // Mark certificate as issued if not already
        if (!$registration['certificate_issued']) {
            $this->registrationModel->issueCertificate($registrationId);
        }

        $data = [
            'registration' => $registration
        ];

        return view('user/certificates/certificate', $data);
    }

    public function verifyCertificate()
    {
        $code = $this->request->getGet('code');
        
        if (!$code) {
            $data = [
                'title' => 'Verifikasi Sertifikat - Eventra',
                'certificate' => null
            ];
            return view('user/certificates/verify', $data);
        }

        $certificate = $this->registrationModel->getRegistrationByCertificateCode($code);

        $data = [
            'title' => 'Verifikasi Sertifikat - Eventra',
            'certificate' => $certificate,
            'code' => $code
        ];

        return view('user/certificates/verify', $data);
    }
}