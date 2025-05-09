@extends('layouts.app')

@section('content')
<div id="admin-app">
    <admin-dashboard></admin-dashboard>
</div>
@endsection

@vite('resources/js/admin.js')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite('resources/js/app.js')
</head>
<body>
    <div id="app" class="p-6">
        <course-manager></course-manager>
    </div>
</body>
</html>
