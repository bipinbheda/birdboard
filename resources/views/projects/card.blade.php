<div class="card flex flex-col" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue pl-4">
        <a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a>
    </h3>

    <div class="text-gray-500/70 mb-2 flex-1">{{ Str::limit($project->description, 100) }}</div>

    <footer>
        <form action="{{ $project->path() }}" method="post" class="text-right">
            @csrf
            @method("DELETE")
            <button type="submit" class="text-xs">Delete</button>
        </form>
    </footer>
</div>
