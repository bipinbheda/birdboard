<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test **/
    public function a_project_has_a_path()
    {
        $project = Project::factory()->make();
        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    /** @test **/
    public function it_belongs_to_an_user()
    {
        $project = Project::factory()->make();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test **/
    public function it_can_add_a_task()
    {
        $project = Project::factory()->create();

        $task = $project->addTask(['body' => 'Test Task']);

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test **/
    public function it_can_invite_a_user()
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $project->invite($user);
        $this->assertTrue($project->members->contains($user));
    }

}
