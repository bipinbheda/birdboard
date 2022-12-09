@extends('layouts.app')

@section('content')
    <h1>Create a Project</h1>
    <form method="POST" action="/projects" >
        @csrf
        <div class="field">
            <label class="label" for="title">Title</label>

            <div class="control">
                <input type="text" class="input" name="title" placeholder="Title">
            </div>
        </div>

        <div class="field">
            <label class="label" for="description">Title</label>

            <div class="control">
                <textarea class="textarea" name="description" placeholder="description"></textarea>
            </div>
        </div>

        <div class="fild">
            <div class="control">
                <button type="submit" class="bttton is-link">Create Project</button>
                <a href="/projects">Cancel</a>
            </div>
        </div>

    </form>
@endsection