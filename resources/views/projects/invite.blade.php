@can('manage', $project)
<div class="card flex flex-col mt-4">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue pl-4">
        Invite a User
    </h3>

    <footer>
        <form action="{{ $project->path() . '/invitations' }}" method="post">
            @csrf
            <input type="email" name="email" class="py-1 border-black border w-full rounded">
            <button type="submit" class="text-xs button mt-4">Invite</button>
        </form>
    </footer>

    @include('projects.errors', ['bag' => 'invitation'])
</div>
@endcan