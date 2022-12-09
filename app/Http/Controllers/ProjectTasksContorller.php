<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectTasksContorller extends Controller
{
    public function store(Request $request, Project $project) {

        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $project->addTask($attributes);

    }
}
