<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function guests_cannot_manage_pojects()
    {
        // $this->withoutExceptionHandling();

        $project = Project::factory()->create();
        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
    }


    public function guests_cannot_view_projects()
    {
        // $this->withoutExceptionHandling();

        $this->get('/projects')->assertRedirect('login');
    }

    public function guests_cannot_view_a_single_projects()
    {
        // $this->withoutExceptionHandling();
        // $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('login');

        $this->get('/projects')->assertRedirect('login');
    }

    /** @test **/
    public function a_user_can_create_a_poject()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $this->get('projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test **/
    public function a_user_can_view_thier_porject()
    {
        // $this->withoutExceptionHandling();

        $user = $this->actingAs(User::factory()->create());

        $project = Project::factory()->create(['owner_id' => Auth::id()]);

        $this->get($project->path())
        ->assertSee($project->title)
        ->assertSee($project->description);
    }

    /** @test **/
    public function an_authenticate_user_cannot_view_the_projects_of_other()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);

    }
    /** @test **/
    public function a_porject_requires_a_title()
    {
        $this->actingAs(User::factory()->make());

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test **/
    public function a_porject_requires_a_description()
    {
        $this->actingAs(User::factory()->make());

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
