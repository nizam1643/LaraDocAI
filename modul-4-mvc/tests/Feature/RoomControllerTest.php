<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
    }

    public function test_admin_can_create_room()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $response = $this->post(route('rooms.store'), [
            'name' => 'Room A',
            'location' => 'Block A',
            'capacity' => 30,
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseHas('rooms', ['name' => 'Room A']);
    }

    public function test_admin_can_update_room()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $room = Room::create([
            'name' => 'Old Room',
            'location' => 'Old Location',
            'capacity' => 20,
            'description' => 'Old Description',
        ]);

        $this->actingAs($admin);

        $response = $this->put(route('rooms.update', $room->id), [
            'name' => 'New Room',
            'location' => 'New Location',
            'capacity' => 40,
            'description' => 'Updated',
        ]);

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseHas('rooms', ['name' => 'New Room']);
    }
}
