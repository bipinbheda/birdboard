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
    public function non_owner_may_not_invite_users()
    {
        $project = ProjectFactory::create();
        $user = User::factory()->create();


        $assertInvitationsForbidden = function() use ($user, $project) {
            $this->actingAs($user)->post($project->path().'/invitations',[
                'email' => $user->email,
            ])->assertForbidden();
        };

        $assertInvitationsForbidden();
        $project->invite($user);
        $assertInvitationsForbidden();
    }

    /** @test **/
    public function a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();
        $userToInvite = User::factory()->create();
        $this->actingAs($project->owner)->post($project->path().'/invitations',[
            'email' => $userToInvite->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test **/
    public function the_email_must_be_associated_with_a_valid_birdboard_account()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();
        $this->actingAs($project->owner)->post($project->path().'/invitations',[
            'email' => 'notauser@example.com',
        ])->assertSessionHasErrors([
            'email' => 'The user you are inviting must be a birdboard account.'
        ], null, 'invitation');
    }

    /** @test **/
    public function invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = User::factory()->create());

        $this->signIn($newUser);
        $this->post($project->path().'/tasks',['body' => 'foo Task']);
        $this->assertDatabaseHas('tasks', ['Body' => 'foo Task']);
    }
}
