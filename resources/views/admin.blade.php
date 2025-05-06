@extends('layouts.app') {{-- Or use your base layout --}}

@section('content')
<div id="admin-app">
    <admin-dashboard></admin-dashboard>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/admin.js') }}"></script>
@endpush
