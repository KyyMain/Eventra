<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\EventService;
use App\Models\EventModel;

class EventServiceTest extends CIUnitTestCase
{
    protected $eventService;
    protected $eventModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventService = new EventService();
        $this->eventModel = new EventModel();
    }

    public function testCreateEventWithValidData()
    {
        $eventData = [
            'title' => 'Test Event',
            'type' => 'seminar',
            'speaker' => 'John Doe',
            'description' => 'This is a test event description',
            'start_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
            'end_date' => date('Y-m-d H:i:s', strtotime('+1 week +2 hours')),
            'location' => 'Test Location',
            'max_participants' => 100,
            'price' => 50000,
            'status' => 'published'
        ];

        $result = $this->eventService->createEvent($eventData);
        
        $this->assertIsArray($result);
        $this->assertEquals($eventData['title'], $result['title']);
        $this->assertEquals($eventData['type'], $result['type']);
    }

    public function testCreateEventWithInvalidData()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $eventData = [
            'title' => '', // Invalid: empty title
            'type' => 'invalid_type', // Invalid: not in allowed list
        ];

        $this->eventService->createEvent($eventData);
    }

    public function testGetEventStatistics()
    {
        $stats = $this->eventService->getEventStatistics();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_events', $stats);
        $this->assertArrayHasKey('published_events', $stats);
        $this->assertArrayHasKey('draft_events', $stats);
        $this->assertArrayHasKey('total_registrations', $stats);
    }

    public function testRegisterUserForFullEvent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Event is full');
        
        // This would need a mock event that's full
        // Implementation depends on your testing strategy
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up test data if needed
    }
}