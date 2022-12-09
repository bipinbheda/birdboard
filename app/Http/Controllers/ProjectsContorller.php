<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsContorller extends Controller
{
    public function index()
    {
        // $projects = Project::all();
        $projects = Auth::user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        // $attributes['owner_id'] = Auth::id();
        Auth::user()->projects()->create($attributes);

        // Project::create($attributes);

        return redirect('/projects');
    }

    public function show( Project $project )
    {
        if ( Auth::user()->isNot($project->owner) ) {
            abort(403);
        }

        // $project = Project::findOrFail($project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }
}


// Next Episode 7