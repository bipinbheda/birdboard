<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsContorller extends Controller
{
    public function index()
    {
        $projects = Auth::user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }

    public function store()
    {

        $project = Auth::user()->projects()->create($this->projectValidate());

        return redirect($project->path());
    }

    public function update(UpdateProjectRequest $request)
    {
        // $project->update($request->validated());

        return redirect($request->save()->path());
    }

    public function show( Project $project )
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function projectValidate()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'min:3',
        ]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect('/projects');
    }
}
