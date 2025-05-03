// MyLearning.vue
<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <!-- Logo and User Info -->
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <!-- Divider -->
      <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
      <!-- Profile Picture -->
      <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
      <!-- Username -->
      <p class="pl-24 pt-16 text-base font-normal text-zinc-800">Berkan Yuksel</p>
      <nav class="mt-8 space-y-4"></nav>
        <!--Navigation Panel-->
        <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a> 
        <a href="#" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Settings</a>
      </nav>
       <!-- logout -->
       <nav class="flex flex-col space-y-6 pl-20 pt-60">
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
            </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-8 text-white">
      <h2 class="text-2xl font-bold">My Learning</h2>
      <div v-if="userProgress.some(course => course.progress > 0)" class="mt-6 space-y-4">
        <div v-for="(course, index) in userProgress" :key="index" v-if="course.progress > 0" class="bg-gray-900 p-4 rounded-lg">
          <h3 class="text-lg font-semibold">{{ course.name }}</h3>
          <div class="mt-2 w-full bg-gray-700 rounded-full h-2">
            <div class="bg-green-500 h-2 rounded-full" :style="{ width: course.progress + '%' }"></div>
          </div>
          <p class="mt-2 text-sm">{{ course.progress }}% completed</p>
        </div>
      </div>
      <p v-else class="mt-6 text-gray-400">You haven't started any courses yet.</p>
    </div>
  </div>

  
        <!-- Logout Confirmation Modal -->
        <div
            v-if="showLogoutModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center text-gray-800">
                <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
                <p class="mb-6">Do you really want to logout from your account?</p>
                <div class="flex justify-center space-x-4">
                    <button
                        @click="confirmLogout"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                    >
                        Yes, Logout
                    </button>
                    <button
                        @click="showLogoutModal = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  data() {
    return {
      userProgress: [
        { name: 'Vue.js', progress: 0 },
        { name: 'Laravel', progress: 0 },
      ],
      showLogoutModal: false,
    };
  },
  methods: {
    confirmLogout() {
      router.post('/logout');
    }
  }
};
</script>
