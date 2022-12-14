<?php

use App\Http\Controllers\ProjectTasksContorller;
use App\Http\Controllers\ProjectsContorller;

use App\Models\Project;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $project = Project::find(9);
    // dump();
    // dump(Arr::except($project->getAttributes(),'updated_at'));
    // dd(array_diff($project->getAttributes(), $project->getRawOriginal()));
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function() {
    Route::post('projects',  [ProjectsContorller::class, 'store']);
    Route::get('projects', [ProjectsContorller::class, 'index']);
    Route::get('projects/create', [ProjectsContorller::class, 'create']);
    Route::get('projects/{project}', [ProjectsContorller::class, 'show']);
    Route::get('/projects/{project}/edit', [ProjectsContorller::class, 'edit']);
    Route::patch('/projects/{project}', [ProjectsContorller::class, 'update']);
    Route::post('projects/{project}/tasks', [ProjectTasksContorller::class, 'store']);
    Route::patch('/projects/{project}/tasks/{task}', [ProjectTasksContorller::class, 'update']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Continue on Lesson 18 - From the Beginning