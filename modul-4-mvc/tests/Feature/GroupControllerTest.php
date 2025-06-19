<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'student']);
    }

    public function test_student_can_create_group()
    {
        $student = User::create([
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');

        $this->actingAs($student);

        $response = $this->post(route('groups.store'), [
            'name' => 'Test Group',
        ]);

        $response->assertRedirect(route('groups.index'));
        $this->assertDatabaseHas('groups', ['name' => 'Test Group', 'leader_id' => $student->id]);
    }

    public function test_student_can_update_group()
    {
        $student = User::create([
            'name' => 'Student',
            'email' => 'student2@test.com',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('student');

        $group = Group::create([
            'name' => 'Old Name',
            'leader_id' => $student->id,
        ]);

        $group->users()->attach($student->id);

        $this->actingAs($student);

        $response = $this->put(route('groups.update', $group->id), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('groups.index'));
        $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => 'Updated Name']);
    }
}
