// File: resources/js/admin.js
import { createApp } from 'vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';

const app = createApp({});
app.component('AdminDashboard', AdminDashboard);
app.mount('#admin-app');

// File: resources/views/admin/dashboard.blade.php
@extends('layouts.app')

@section('content')
<div id="admin-app">
    <admin-dashboard></admin-dashboard>
</div>
@endsection

@vite('resources/js/admin.js')

// File: resources/js/components/admin/AdminDashboard.vue
<template>
  <div class="flex h-screen bg-gray-100 text-white">
    <Sidebar />
    <div class="flex-1 p-6 overflow-y-auto bg-gradient-to-b from-gray-900 to-gray-800">
      <CourseCards />
    </div>
  </div>
</template>

<script>
import Sidebar from './Sidebar.vue';
import CourseCards from './CourseCards.vue';

export default {
  components: { Sidebar, CourseCards },
};
</script>

// File: resources/js/components/admin/Sidebar.vue
<template>
  <aside class="w-64 bg-gray-900 p-4">
    <div class="mb-8 text-xl font-bold">&lt;horizon/&gt;</div>
    <ul>
      <li class="mb-4"><a href="#" class="hover:underline">📊 Dashboard</a></li>
      <li class="mb-4"><a href="#" class="hover:underline">📚 My Learning</a></li>
      <li class="mb-4"><a href="#" class="hover:underline">⚙️ Settings</a></li>
    </ul>
    <div class="absolute bottom-6">
      <a href="/logout" class="text-red-400 hover:underline">🚪 Logout</a>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'Sidebar'
}
</script>

// File: resources/js/components/admin/CourseCards.vue
<template>
  <div class="text-white">
    <h2 class="text-xl font-semibold mb-4">Choose your course and start coding</h2>
    <div class="grid grid-cols-2 gap-6">
      <div class="bg-white text-black p-4 rounded-xl shadow-md">
        <h3 class="text-lg font-bold">Course: Vue.js</h3>
        <p class="text-sm mb-2">Learn how to build dynamic interfaces with Vue.js</p>
        <a href="#" class="text-blue-600 hover:underline">Start →</a>
      </div>
      <div class="bg-white text-black p-4 rounded-xl shadow-md">
        <h3 class="text-lg font-bold">Course: Laravel</h3>
        <p class="text-sm mb-2">Master backend development with Laravel</p>
        <a href="#" class="text-blue-600 hover:underline">Start →</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseCards'
}
</script>
