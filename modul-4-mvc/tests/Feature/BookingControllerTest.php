<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'student']);
        Role::create(['name' => 'admin']);
    }

    public function test_student_can_create_booking()
    {
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');

        $room = Room::create([
            'name' => 'Room A',
            'location' => 'Block A',
            'capacity' => 20,
            'description' => 'Test Room',
        ]);

        $group = Group::create([
            'name' => 'Group A',
            'leader_id' => $student->id,
        ]);
        $group->users()->attach($student->id);

        $this->actingAs($student);

        $response = $this->post(route('bookings.store'), [
            'group_id' => $group->id,
            'room_id' => $room->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'group_id' => $group->id,
            'room_id' => $room->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_booking()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $student = User::create([
            'name' => 'Student 1',
            'email' => 'student1@test.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');

        $room = Room::create([
            'name' => 'Room B',
            'location' => 'Block B',
            'capacity' => 30,
            'description' => 'Another Room',
        ]);

        $group = Group::create([
            'name' => 'Group B',
            'leader_id' => $student->id,
        ]);
        $group->users()->attach($student->id);

        $booking = Booking::create([
            'group_id' => $group->id,
            'room_id' => $room->id,
            'date' => now()->addDays(1)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'status' => 'pending',
        ]);

        $this->actingAs($admin);

        $response = $this->put(route('bookings.update', $booking->id), [
            'status' => 'approved',
        ]);

        $response->assertRedirect(route('bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'approved',
        ]);
    }
}
