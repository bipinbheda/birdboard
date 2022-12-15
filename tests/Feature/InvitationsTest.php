<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function a_project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);
        $this->post($project->path().'/tasks',['body' => 'foo Task']);
        $this->assertDatabaseHas('tasks', ['Body' => 'foo Task']);
    }
}
