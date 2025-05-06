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
