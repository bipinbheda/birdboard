@if (count($activity->changes['after']) === 1)
	{{ $activity->user->name }} the {{ key($activity->changes['after']) }}
@else
{{ $activity->user->name }} the project
@endif