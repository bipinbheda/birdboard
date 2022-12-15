@extends ('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-500 text-sm font-normal">
                <a href="/projects" class="text-gray text-sm font-normal no-underline hover:underline">My Projects</a> / {{ $project->title }}
            </p>

            <div class="flex items-center">
                @foreach ($project->members as $member)
                    <img src="{{ gravatar_url($member->email) }}" alt="{{ $member->email }}'s avatar" class="rounded-full w-8 mr-2">
                @endforeach
                    <img src="{{ gravatar_url($project->owner->email) }}" alt="{{ $project->owner->email }}'s avatar" class="rounded-full w-8 mr-2">
                <a href="{{ $project->path(). '/edit' }}" class="button">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-gray font-normal mb-3">Tasks</h2>

                    {{-- tasks --}}
                   @include('projects.errors')

                    @forelse($project->tasks as $task)
                    <div class="card mb-3">
                        <form action="{{ $project->path().'/tasks/'.$task->id }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <div class="flex">
                            <input name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-gray-500' : '' }}" >
                            <input name="completed" type="checkbox" value="1" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }} />
                            </div>
                        </form>
                    </div>
                    @empty
                    @endforelse
                    <form action="{{ $project->path(). '/tasks' }}" method="post">
                        @csrf
                        <input class="card mb-3 w-full" placeholder="Add a new task." name="body">
                    </form>
                </div>

                <div>
                    <h2 class="text-lg text-gray font-normal mb-3">General Notes</h2>

                    {{-- general notes --}}
                    <form method="POST" action="{{ $project->path() }}">
                        @csrf
                        @method('PATCH')
                        <textarea class="card w-full pb-2" name="notes" style="min-height: 200px">{{ $project->notes }}</textarea>
                        <button class="button">Save</button>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 px-3 lg:py-8">
                @include ('projects.card')

                @include ('projects.activity.card')

                @include('projects.invite')
            </div>
        </div>
    </main>
@endsection
