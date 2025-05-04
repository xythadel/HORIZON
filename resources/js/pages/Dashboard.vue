<template>
    <div class="flex h-screen w-full flex-wrap bg-zinc-800">
      <!-- Sidebar -->
      <aside class="relative flex h-screen w-60 flex-col bg-white">
        <!-- Logo and User Info -->
        <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon;</h1>
        <!-- Divider -->
        <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
        <!-- Profile Picture -->
        <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md">
          <img src="/path-to-profile-image.jpg" alt="Profile" class="h-full w-full rounded-full object-cover" />
        </div>
        <!-- Username -->
        <p class="pl-24 pt-16 text-base font-normal text-zinc-800">Berkan Yuksel</p>
  
        <!--Navigation Panel-->
        <nav class="flex flex-col space-y-6 pl-20 pt-14">
          <a href="dashboard" class="flex items-center text-base font-normal text-zinc-800 hover:text-indigo-600">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
          </a>
          <a href="/mylearning" class="flex items-center text-base font-normal text-zinc-800 hover:text-indigo-600">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            My Learning
          </a> 
          <a href="#" class="flex items-center text-base font-medium text-zinc-800 hover:text-indigo-600">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Settings
          </a>
        </nav>
  
        <!-- Logout -->
        <nav class="mt-auto mb-10 flex flex-col space-y-6 pl-20">
          <a
            href="#"
            @click.prevent="showLogoutModal = true"
            class="flex items-center text-base font-normal text-zinc-800 hover:text-indigo-600"
          >
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
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
                <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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