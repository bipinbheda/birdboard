<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksContorller extends Controller
{
    public function store(Request $request, Project $project) {
        /*if ( auth()->user()->isNot($project->owner) ) {
             abort('403');
         }*/
         $this->authorize('update', $project);

         $attributes = $request->validate([
            'body' => 'required',
        ]);

         $project->addTask($attributes);

         return redirect($project->path());

     }

     public function update(Request $request, Project $project, Task $task) {
        $this->authorize('update', $task->project);

        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $task->update($attributes);

        $method = $request->get('completed') ? 'complete' : 'incomplete';
        $task->$method();

        /*if ( $request->get('completed') ) {
            $task->complete();
        } else {
            $task->incomplete();
        }*/

        return redirect($project->path());
    }
}
