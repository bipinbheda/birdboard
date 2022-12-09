<?php

namespace Tests\Unit;

use App\Models\Project;
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
}
