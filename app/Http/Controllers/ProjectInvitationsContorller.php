<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectInvitationsContorller extends Controller
{
    public function store(ProjectInvitationRequest $request, Project $project)
    {
        // $this->authorize('update', $project);
        // Lesson 33 to make it as a custom request method
        /*$validate = request()->validate([
            'email' => 'required|exists:users'
        ],[
            'email.exists' => 'The user you are inviting must be a birdboard account.'
        ]);*/
        $user = User::whereEmail(request('email'))->first();
        $project->invite($user);

        return redirect($project->path());
    }
}
