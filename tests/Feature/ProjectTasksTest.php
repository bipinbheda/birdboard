<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function a_project_can_have_tasks()
    {
        $this->signIn();

        $project = Project::factory(['owner_id' => auth()->id()])->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())->assertSee('Test Task');
    }

    /** @test **/
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Task'])
        ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
    }

    /** @test **/
    public function only_the_owner_of_a_project_may_update_a_tasks()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $task = $project->addTask(['body' => 'New task']);

        $this->patch($project->path().'/tasks/'.$task->id, [
            'body' => 'Changed',
            'completed' => 1,
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'body' => 'Changed',
            'completed' => 1,
        ]);
    }

    /** @test **/
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory(['owner_id' => auth()->id()])->create();

        $task = $project->addTask(['body' => 'New task']);

        $this->patch($project->path().'/tasks/'.$task->id, [
            'body' => 'Changed'
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed'
        ]);
    }

    /** @test **/
    public function a_task_can_be_completed()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory(['owner_id' => auth()->id()])->create();

        $task = $project->addTask(['body' => 'New task']);

        $this->patch($project->path().'/tasks/'.$task->id, [
            'body' => 'Changed',
            'completed' => 1,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed',
            'completed' => 1,
        ]);
    }

    /** @test **/
    public function a_task_can_be_incomplete()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = Project::factory(['owner_id' => auth()->id()])->create();

        $task = $project->addTask(['body' => 'New task']);

        $this->patch($project->path().'/tasks/'.$task->id, [
            'body' => 'Changed',
            'completed' => false,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed',
            'completed' => false,
        ]);
    }

    /** @test **/
    public function a_task_requires_a_body()
    {
        $this->signIn();

        // $project = auth()->user()->projects()->create(..data..);
        $project = Project::factory(['owner_id' => auth()->id()])->create();

        $attributes = Task::factory(['body' => ''])->make()->toArray();

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }

    /** @test **/
    public function a_task_custom_class_test()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->path().'/tasks/'.$project->tasks->first()->id, [
            'body' => 'Changed',
            'completed' => 1,
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'Changed',
            'completed' => 1,
        ]);
    }

}
