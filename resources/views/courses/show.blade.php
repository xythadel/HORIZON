@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $course->name }}</h1>
    <p>{{ $course->description }}</p>

    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">Edit</a>

    <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</button>
    </form>

    <a href="{{ route('courses.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
