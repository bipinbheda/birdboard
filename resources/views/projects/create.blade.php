@extends ('layouts.app')
@inject('project', 'App\Models\Project')

@section('content')
<form method="POST" action="/projects" class="lg:w-1/2 lg:mx-auto bg-white py-12 px-16 rounded shadow">
    <h1 class="text-2xl font-normal mb-10 text-center">Let’s start something new</h1>
    @include('projects.form', [
        $project,
        'button_text' => 'Create'
        ]);
    </form>
    @endsection
