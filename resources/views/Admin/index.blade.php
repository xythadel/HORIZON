@extends('layouts.app')

@section('content')
<div id="admin-app" class="p-6">
    <course-manager></course-manager>
</div>
@endsection

@push('scripts')
    @vite('resources/js/admin.js')
@endpush