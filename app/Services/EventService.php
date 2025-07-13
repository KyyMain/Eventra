<?php

namespace App\Services;

use App\Models\EventModel;
use App\Models\EventRegistrationModel;
use App\Models\UserModel;
use App\Helpers\ValidationHelper;

class EventService
{
    protected $eventModel;
    protected $registrationModel;
    protected $userModel;
    protected $cacheService;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->registrationModel = new EventRegistrationModel();
        $this->userModel = new UserModel();
        $this->cacheService = new CacheService();
    }

    /**
     * Create a new event with validation
     */
    public function createEvent(array $data, $imageFile = null): array
    {
        // Validate input data
        $validatedData = ValidationHelper::validateEventData($data);

        // Handle image upload
        if ($imageFile && ValidationHelper::validateImageUpload($imageFile)) {
            $filename = ValidationHelper::generateSecureFilename($imageFile->getName());
            $imageFile->move(FCPATH . 'uploads/events', $filename);
            $validatedData['image'] = $filename;
        }

        // Create event
        $eventId = $this->eventModel->insert($validatedData);
        
        if (!$eventId) {
            throw new \RuntimeException('Failed to create event');
        }

        // Clear cache
        $this->cacheService->clearStatsCache();
        
        // Log activity
        $errorHandler = new \App\Libraries\ErrorHandler();
        $errorHandler->logActivity('event_created', [
            'event_id' => $eventId,
            'title' => $validatedData['title']
        ]);

        return $this->eventModel->find($eventId);
    }

    /**
     * Register user for event with business logic
     */
    public function registerUserForEvent(int $userId, int $eventId): bool
    {
        // Check if event exists and is available
        $event = $this->eventModel->find($eventId);
        if (!$event || $event['status'] !== 'published') {
            throw new \InvalidArgumentException('Event not available for registration');
        }

        // Check if event has started
        if (strtotime($event['start_date']) <= time()) {
            throw new \InvalidArgumentException('Cannot register for event that has already started');
        }

        // Check if user is already registered
        $existingRegistration = $this->registrationModel
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();

        if ($existingRegistration) {
            throw new \InvalidArgumentException('User already registered for this event');
        }

        // Check capacity
        $currentParticipants = $this->registrationModel
            ->where('event_id', $eventId)
            ->where('status', 'confirmed')
            ->countAllResults();

        if ($currentParticipants >= $event['max_participants']) {
            throw new \InvalidArgumentException('Event is full');
        }

        // Register user
        $registrationData = [
            'user_id' => $userId,
            'event_id' => $eventId,
            'status' => 'confirmed',
            'payment_status' => $event['price'] > 0 ? 'pending' : 'free'
        ];

        $registrationId = $this->registrationModel->insert($registrationData);
        
        if ($registrationId) {
            // Log activity
            $errorHandler = new \App\Libraries\ErrorHandler();
            $errorHandler->logActivity('event_registration', [
                'event_id' => $eventId,
                'registration_id' => $registrationId
            ]);
        }

        return $registrationId;
    }

    /**
     * Cancel event registration
     */
    public function cancelRegistration($eventId, $userId)
    {
        $registration = $this->registrationModel
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();
            
        if (!$registration) {
            return [
                'success' => false,
                'message' => 'Registration not found'
            ];
        }
        
        // Check if event hasn't started yet
        $event = $this->eventModel->find($eventId);
        if (strtotime($event['start_date']) <= time()) {
            return [
                'success' => false,
                'message' => 'Cannot cancel registration for events that have already started'
            ];
        }
        
        if ($this->registrationModel->delete($registration['id'])) {
            // Log activity
            $errorHandler = new \App\Libraries\ErrorHandler();
            $errorHandler->logActivity('registration_cancelled', [
                'event_id' => $eventId,
                'registration_id' => $registration['id']
            ]);
            
            return [
                'success' => true,
                'message' => 'Registration cancelled successfully'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to cancel registration'
        ];
    }

    /**
     * Get event statistics
     */
    public function getEventStatistics(): array
    {
        return $this->cacheService->remember('event_stats', function() {
            $totalEvents = $this->eventModel->countAll();
            $publishedEvents = $this->eventModel->where('status', 'published')->countAllResults();
            $totalRegistrations = $this->registrationModel->countAll();
            $upcomingEvents = $this->eventModel->where('start_date >', date('Y-m-d H:i:s'))->countAllResults();

            return [
                'total_events' => $totalEvents,
                'published_events' => $publishedEvents,
                'draft_events' => $totalEvents - $publishedEvents,
                'total_registrations' => $totalRegistrations,
                'upcoming_events' => $upcomingEvents
            ];
        }, 600); // Cache for 10 minutes
    }
    
    /**
     * Get popular events
     */
    public function getPopularEvents($limit = 5)
    {
        return $this->eventModel
            ->select('events.*, COUNT(event_registrations.id) as registration_count')
            ->join('event_registrations', 'event_registrations.event_id = events.id', 'left')
            ->where('events.status', 'published')
            ->where('events.start_date >', date('Y-m-d H:i:s'))
            ->groupBy('events.id')
            ->orderBy('registration_count', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Send event reminders
     */
    public function sendEventReminders()
    {
        // Get events happening in next 24 hours
        $tomorrow = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $today = date('Y-m-d H:i:s');
        
        $upcomingEvents = $this->eventModel
            ->where('start_date >=', $today)
            ->where('start_date <=', $tomorrow)
            ->where('status', 'published')
            ->findAll();
            
        foreach ($upcomingEvents as $event) {
            $registrations = $this->registrationModel
                ->select('event_registrations.*, users.email, users.full_name')
                ->join('users', 'users.id = event_registrations.user_id')
                ->where('event_id', $event['id'])
                ->where('status', 'confirmed')
                ->findAll();
                
            foreach ($registrations as $registration) {
                // Send reminder email (implement email service)
                $this->sendReminderEmail($registration, $event);
            }
        }
    }
    
    /**
     * Send reminder email (placeholder for email service)
     */
    private function sendReminderEmail($registration, $event)
    {
        // Implement email sending logic here
        // This is a placeholder for the actual email service
        log_message('info', "Reminder email sent to {$registration['email']} for event {$event['title']}");
    }
}