<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\EventRegistrationModel;

class AdminController extends BaseController
{
    protected $eventModel;
    protected $userModel;
    protected $registrationModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->userModel = new UserModel();
        $this->registrationModel = new EventRegistrationModel();
        helper(['form', 'url']);
    }

    private function checkAdminAuth()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Akses ditolak. Silakan login sebagai admin.');
        }
        return null;
    }

    public function dashboard()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        try {
            $eventStats = $this->eventModel->getEventStats();
            $registrationStats = $this->registrationModel->getRegistrationStats();
            $userStats = [
                'total_users' => $this->userModel->countAll(),
                'active_users' => $this->userModel->where('is_active', true)->countAllResults(),
                'admin_users' => $this->userModel->where('role', 'admin')->countAllResults()
            ];

            $recentEvents = $this->eventModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
            $recentRegistrations = $this->registrationModel
                ->select('event_registrations.*, events.title, users.full_name')
                ->join('events', 'events.id = event_registrations.event_id')
                ->join('users', 'users.id = event_registrations.user_id')
                ->orderBy('event_registrations.created_at', 'DESC')
                ->limit(5)
                ->findAll();

            $data = [
                'title' => 'Admin Dashboard - Eventra',
                'eventStats' => $eventStats,
                'registrationStats' => $registrationStats,
                'userStats' => $userStats,
                'recentEvents' => $recentEvents,
                'recentRegistrations' => $recentRegistrations
            ];

            return view('admin/dashboard', $data);
        } catch (Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            
            // Fallback data
            $data = [
                'title' => 'Admin Dashboard - Eventra',
                'eventStats' => ['total_events' => 0, 'published_events' => 0, 'upcoming_events' => 0, 'completed_events' => 0],
                'registrationStats' => ['total_registrations' => 0, 'active_registrations' => 0, 'attended' => 0, 'cancelled' => 0, 'certificates_issued' => 0],
                'userStats' => ['total_users' => 0, 'active_users' => 0, 'admin_users' => 0],
                'recentEvents' => [],
                'recentRegistrations' => [],
                'error' => 'Terjadi kesalahan saat memuat data dashboard: ' . $e->getMessage()
            ];

            return view('admin/dashboard', $data);
        }
    }

    // Event Management
    public function events()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $events = $this->eventModel
            ->select('events.*, users.full_name as creator_name')
            ->join('users', 'users.id = events.created_by')
            ->orderBy('events.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Event - Eventra',
            'events' => $events
        ];

        return view('admin/events/index', $data);
    }

    public function createEvent()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Buat Event Baru - Eventra'
        ];

        return view('admin/events/create', $data);
    }

    public function storeEvent()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'type' => 'required|in_list[seminar,workshop,conference,training]',
            'speaker' => 'required|min_length[3]|max_length[255]',
            'location' => 'required|min_length[3]|max_length[255]',
            'start_date' => 'required',
            'end_date' => 'required',
            'max_participants' => 'required|integer|greater_than[0]',
            'price' => 'required|decimal',
            'status' => 'required|in_list[draft,published,cancelled,completed]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $eventData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type'),
            'speaker' => $this->request->getPost('speaker'),
            'location' => $this->request->getPost('location'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'max_participants' => $this->request->getPost('max_participants'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status'),
            'created_by' => session()->get('user_id')
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/events', $newName);
            $eventData['image'] = $newName;
        }

        if ($this->eventModel->insert($eventData)) {
            return redirect()->to('/admin/events')->with('success', 'Event berhasil dibuat.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat membuat event.');
        }
    }

    public function editEvent($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Event - Eventra',
            'event' => $event
        ];

        return view('admin/events/edit', $data);
    }

    public function updateEvent($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'description' => 'required',
            'type' => 'required|in_list[seminar,workshop,conference,training]',
            'speaker' => 'required|min_length[3]|max_length[255]',
            'location' => 'required|min_length[3]|max_length[255]',
            'start_date' => 'required',
            'end_date' => 'required',
            'max_participants' => 'required|integer|greater_than[0]',
            'price' => 'required|decimal',
            'status' => 'required|in_list[draft,published,cancelled,completed]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $eventData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'type' => $this->request->getPost('type'),
            'speaker' => $this->request->getPost('speaker'),
            'location' => $this->request->getPost('location'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'max_participants' => $this->request->getPost('max_participants'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status')
        ];

        // Handle image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Delete old image if exists
            if ($event['image'] && file_exists(ROOTPATH . 'public/uploads/events/' . $event['image'])) {
                unlink(ROOTPATH . 'public/uploads/events/' . $event['image']);
            }
            
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads/events', $newName);
            $eventData['image'] = $newName;
        }

        if ($this->eventModel->update($id, $eventData)) {
            return redirect()->to('/admin/events')->with('success', 'Event berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat update event.');
        }
    }

    public function deleteEvent($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        // Delete image if exists
        if ($event['image'] && file_exists(ROOTPATH . 'public/uploads/events/' . $event['image'])) {
            unlink(ROOTPATH . 'public/uploads/events/' . $event['image']);
        }

        if ($this->eventModel->delete($id)) {
            return redirect()->to('/admin/events')->with('success', 'Event berhasil dihapus.');
        } else {
            return redirect()->to('/admin/events')->with('error', 'Terjadi kesalahan saat menghapus event.');
        }
    }

    // User Management
    public function users()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $cacheService = new \App\Services\CacheService();
        
        // Get users with additional data
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        
        // Add registration counts for each user
        foreach ($users as &$user) {
            $user['total_registrations'] = $this->registrationModel
                ->where('user_id', $user['id'])
                ->countAllResults();
            $user['certificates_count'] = 0; // Placeholder for certificates
        }
        
        $stats = $cacheService->getUserStats(); // Use cached stats
        
        // Get recent registrations
        $recent_registrations = $this->registrationModel
            ->select('event_registrations.*, users.full_name as user_name, events.title as event_title')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('events', 'events.id = event_registrations.event_id')
            ->orderBy('event_registrations.created_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        // Get user growth data (last 30 days)
        $user_growth = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $count = $this->userModel
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            $user_growth[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        $data = [
            'title' => 'Manajemen User - Eventra',
            'users' => $users,
            'stats' => $stats,
            'recent_registrations' => $recent_registrations,
            'user_growth' => $user_growth
        ];

        return view('admin/users', $data);
    }

    public function toggleUserStatus($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $newStatus = !$user['is_active'];
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'User berhasil diaktifkan.' : 'User berhasil dinonaktifkan.';
            return redirect()->to('/admin/users')->with('success', $message);
        } else {
            return redirect()->to('/admin/users')->with('error', 'Terjadi kesalahan saat mengubah status user.');
        }
    }

    // Reports
    public function reports()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        // Calculate comprehensive stats
        $stats = [
            'total_events' => $this->eventModel->countAllResults(),
            'total_registrations' => $this->registrationModel->countAllResults(),
            'total_users' => $this->userModel->countAllResults(),
            'total_certificates' => 0, // Placeholder
            'events_growth' => 12.5, // Placeholder - calculate actual growth
            'registrations_growth' => 8.3, // Placeholder - calculate actual growth
            'users_growth' => 15.2, // Placeholder - calculate actual growth
            'certificates_growth' => 5.7 // Placeholder - calculate actual growth
        ];
        
        // Monthly registration data for chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-{$i} months"));
            $count = $this->registrationModel
                ->where("DATE_FORMAT(created_at, '%Y-%m')", $date)
                ->countAllResults();
            $monthlyData[] = [
                'month' => date('M Y', strtotime($date . '-01')),
                'count' => $count
            ];
        }

        // Event type distribution
        $eventTypeData = $this->eventModel
            ->select('type as event_type, COUNT(*) as count')
            ->groupBy('type')
            ->findAll();
            
        // If no event types, provide default data
        if (empty($eventTypeData)) {
            $eventTypeData = [
                ['event_type' => 'seminar', 'count' => 0],
                ['event_type' => 'workshop', 'count' => 0],
                ['event_type' => 'conference', 'count' => 0]
            ];
        }
            
        // Top events by registration count
        $top_events = $this->eventModel
            ->select('events.id, events.title, events.type, COUNT(event_registrations.id) as participants')
            ->join('event_registrations', 'event_registrations.event_id = events.id', 'left')
            ->groupBy('events.id')
            ->orderBy('participants', 'DESC')
            ->limit(5)
            ->findAll();
            
        // Recent activities
        $recent_activities = $this->registrationModel
            ->select('event_registrations.created_at, users.full_name as user_name, events.title as event_title')
            ->join('users', 'users.id = event_registrations.user_id')
            ->join('events', 'events.id = event_registrations.event_id')
            ->orderBy('event_registrations.created_at', 'DESC')
            ->limit(10)
            ->findAll();
            
        // Revenue summary
        $totalRevenue = $this->registrationModel
            ->selectSum('events.price', 'total')
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.payment_status', 'paid')
            ->first()['total'] ?? 0;
            
        $monthlyRevenue = $this->registrationModel
            ->selectSum('events.price', 'total')
            ->join('events', 'events.id = event_registrations.event_id')
            ->where('event_registrations.payment_status', 'paid')
            ->where("DATE_FORMAT(event_registrations.created_at, '%Y-%m')", date('Y-m'))
            ->first()['total'] ?? 0;
            
        $paidEventsCount = $this->eventModel->where('price >', 0)->countAllResults();
        $freeEventsCount = $this->eventModel->where('price', 0)->countAllResults();
        $avgRevenue = $paidEventsCount > 0 ? $totalRevenue / $paidEventsCount : 0;
        
        $revenue_summary = [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'avg_revenue' => $avgRevenue,
            'paid_events' => $paidEventsCount,
            'free_events' => $freeEventsCount
        ];

        $data = [
            'title' => 'Laporan - Eventra',
            'stats' => $stats,
            'monthly_registrations' => $monthlyData,
            'event_type_distribution' => $eventTypeData,
            'top_events' => $top_events,
            'recent_activities' => $recent_activities,
            'revenue_summary' => $revenue_summary
        ];

        return view('admin/reports', $data);
    }

    // Event Registrations
    public function eventRegistrations($eventId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $event = $this->eventModel->find($eventId);
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        $registrations = $this->registrationModel->getEventRegistrations($eventId);

        $data = [
            'title' => 'Peserta Event - Eventra',
            'event' => $event,
            'registrations' => $registrations
        ];

        return view('admin/events/registrations', $data);
    }

    public function updateRegistrationStatus($registrationId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $status = $this->request->getPost('status');
        $paymentStatus = $this->request->getPost('payment_status');

        $updateData = [];
        if ($status) $updateData['status'] = $status;
        if ($paymentStatus) $updateData['payment_status'] = $paymentStatus;

        if ($this->registrationModel->update($registrationId, $updateData)) {
            return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat update status.');
        }
    }
}