@extends('layouts.app')

@section('content')
<div class="flex items-center">
	<h1 class="text-3xl text-blue-400 mr-auto">Birdboard</h1>
	<a href="/projects/create">New Project</a>
</div>

<div class="flex">
	@forelse( $projects as $project )
	<div class="bg-white mr-4 rounded shadow">
		<h3><a href="{{$project->path()}}">{{ $project->title }}</a></h3>
		<div>{{ $project->description }}</div>
	</div>
	@empty
	<div class="text-2xl text-red-600">No projects yet!</div>
	@endforelse
</div>

@endsection