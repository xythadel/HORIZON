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

      <!-- Username (Dynamic) -->
      <p v-if="user" class="pl-24 pt-16 text-base font-normal text-zinc-800">
        {{ user.name }}
      </p>

      <!-- Navigation Panel -->
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
        </svg>
        Dashboard
        </a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a> 
        <a href="#" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Settings</a>
        <!-- Admin Panel (Visible Only to Admins) -->
        <a
          v-if="isAdmin"
          href="/admin"
          class="text-base font-semibold text-red-600 hover:text-red-800"
        >
          Admin Panel
        </a>
      </nav>

      <!-- Logout -->
      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a
          href="#"
          @click.prevent="showLogoutModal = true"
          class="text-base font-normal text-zinc-800 hover:text-indigo-600"
        >
          Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-12">
      <!-- Hero Section with Gradient Background -->
      <div class="mb-12 rounded-2xl bg-gradient-to-r from-indigo-800 to-amber-500 p-8 text-white">
        <h2 class="mb-4 text-2xl font-semibold">Ready to code? Start learning Vue.js or Laravel today and track your growth on our Progress Board!</h2>
        <p class="mb-2 text-lg">Choose your course and start your coding adventure.</p>
        <p class="text-lg">Your journey begins now!</p>
      </div>

      <!-- Course Cards -->
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Vue.js Course Card -->
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-lg">
          <div class="mb-2 text-sm text-gray-500">Course</div>
          <h3 class="mb-4 text-xl font-semibold text-zinc-800">Vue.js</h3>
          <p class="mb-8 text-gray-500">Learn to build dynamic web interfaces with Vue.js, a progressive JavaScript framework.</p>
          <div class="flex justify-end">
            <a href="#" class="flex items-center text-zinc-800 hover:text-indigo-600">
              Start
              <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>
          </div>
        </div>

        <!-- Laravel Course Card -->
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-lg">
          <div class="mb-2 text-sm text-gray-500">Course</div>
          <h3 class="mb-4 text-xl font-semibold text-zinc-800">Laravel</h3>
          <p class="mb-8 text-gray-500">Master PHP web development with Laravel, the powerful framework for modern applications.</p>
          <div class="flex justify-end">
            <a href="#" class="flex items-center text-zinc-800 hover:text-indigo-600">
              Start
              <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Logout Confirmation Modal -->
  <div
    v-if="showLogoutModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="w-full max-w-sm rounded-lg bg-white p-6 text-center text-gray-800 shadow-lg">
      <h2 class="mb-4 text-xl font-semibold">Are you sure?</h2>
      <p class="mb-6">Do you really want to logout from your account?</p>
      <div class="flex justify-center space-x-4">
        <button
          @click="confirmLogout"
          class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600"
        >
          Yes, Logout
        </button>
        <button
          @click="showLogoutModal = false"
          class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400"
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
  props: {
    user: Object,
    isAdmin: Boolean,
    courses: Array
  },
  data() {
    return {
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
