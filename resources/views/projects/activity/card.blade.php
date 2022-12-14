<div class="card mt-4">
    <ul class="text-xs list-none">
        @foreach ($project->activity as $activity)
        <li class="{{ $loop->last ? '' : 'mb-1' }}">
            @includeif('projects.activity.'.$activity->description)
            <span class="text-gray-500">{{ $activity->created_at->diffForHumans(null, true) }}</span>
        </li>
        @endforeach
    </ul>
</div>