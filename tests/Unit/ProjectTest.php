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

        $task = $project->addTask('Test Task');

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test **/
    public function a_task_requires_a_body()
    {
        $this->signIn();

        // $project = auth()->user()->projects()->create(..data..);
        $project = Project::factory()->create();


        // $task = $project->addTask('');

        $attributes = Task::factory(['body' => ''])->make()->toArray();

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
