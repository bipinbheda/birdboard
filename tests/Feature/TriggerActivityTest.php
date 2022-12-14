<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function createing_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        $this->assertEquals('created', $project->activity[0]->description);
    }

    /** @test **/
    public function updating_a_project()
    {
        $project = ProjectFactory::create();
        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test **/
    public function createing_a_task()
    {
        $project = ProjectFactory::create();

        $project->addTask(['body' => 'Some task']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    /** @test **/
    public function completing_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(),[
            'body' => 'Message',
            'completed' => true,
        ]);

        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }

    /** @test **/
    public function incompleting_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(),[
            'body' => 'Message',
            'completed' => true,
        ]);
        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks->first()->path(),[
            'body' => 'Message',
            'completed' => false,
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);

        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test **/
    public function deleteing_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $project->tasks[0]->delete();
        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}
