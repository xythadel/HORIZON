
@extends('layouts.app')

@section('content')
<div id="app" class="p-6">
    <course-manager></course-manager>
</div>
@endsection

@push('scripts')
    @vite('resources/js/admin.js')
@endpush

