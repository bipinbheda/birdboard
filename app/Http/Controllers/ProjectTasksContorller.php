<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectTasksContorller extends Controller
{
    public function store(Request $request, Project $project) {

        if ( auth()->user()->isNot($project->owner) ) {
             abort('403');
        }

        $attributes = $request->validate([
            'body' => 'required',
        ]);

        // dd($attributes);

        $project->addTask($attributes);

        return redirect($project->path());

    }
}
