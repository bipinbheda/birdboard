<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Facades\Tests\Setup\ProjectFactory;
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
        $this->get($project->path(). '/edit')->assertRedirect('login');
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
            'description' => $this->faker->paragraph,
            'notes' => 'This is a project note',
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
        ->assertSee($attributes['title'])
        ->assertSee(\Str::limit($attributes['description']))
        ->assertSee($attributes['notes']);
    }

    /** @test **/
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create(['owner_id' => Auth::id()]);

        $this->get($project->path(). '/edit')->assertOk();

        $attributes = [
            'notes' => 'The task is been updated',
            'description' => 'The task is been updated',
            'title' => 'The task is been updated',
        ];

        $this->patch($project->path(), $attributes)->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test **/
    public function a_user_can_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete($project->path())->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects',$project->only('id'));

    }

    /** @test **/
    public function guests_cannot_delete_a_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())->assertRedirect('login');
        $this->signIn();
        $this->delete($project->path())->assertForbidden();
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
    public function an_authenticate_user_cannot_update_the_projects_of_other()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $this->patch($project->path(), ['notes' => 'Please updated'])->assertStatus(403);

        $this->assertDatabaseMissing('projects', ['notes' => 'Please updated']);

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

    /** @test **/
    public function a_user_can_update_a_projects_general_notes()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->get($project->path(). '/edit')->assertOk();

        $attributes = [
            'notes' => 'The task is been updated',
        ];

        $this->patch($project->path(), $attributes)->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

    }

    /** @test **/
    public function a_user_can_update_a_projects_general_notes_to_empty()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->get($project->path(). '/edit')->assertOk();

        $attributes = [
            'notes' => null,
        ];

        $this->patch($project->path(), $attributes)->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

    }

    /** @test **/
    public function a_project_should_have_activity_list()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        $this->get($project->path())->assertSee('Created the project');
    }

}
