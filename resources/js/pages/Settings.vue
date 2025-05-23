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
      <p v-if="user" class="pl-24 pt-16 text-base font-normal text-zinc-800">
        {{ user.name }}
      </p>

      <!-- Navigation -->
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="#" @click.prevent="goTo('dashboard')" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
        <a href="#" @click.prevent="goTo('learning')" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
        <a href="#" @click.prevent="goTo('settings')" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Settings</a>
      </nav>

      <!-- Logout -->
      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a href="#" @click.prevent="showLogoutModal = true" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-12">
      <!-- Dashboard View -->
      <div v-if="activeSection === 'dashboard'">
        <div class="mb-12 rounded-2xl bg-gradient-to-r from-indigo-800 to-amber-500 p-8 text-white">
          <h2 class="mb-4 text-2xl font-semibold">Ready to code? Start learning Vue.js or Laravel today and track your growth on our Progress Board!</h2>
          <p class="mb-2 text-lg">Choose your course and start your coding adventure.</p>
          <p class="text-lg">Your journey begins now!</p>
        </div>
        <!-- Courses can stay here if needed -->
      </div>

      <!-- Learning View -->
      <div v-else-if="activeSection === 'learning'">
        <div class="text-white text-2xl">My Learning section coming soon.</div>
      </div>

      <!-- Settings View -->
      <div v-else-if="activeSection === 'settings'" class="max-w-xl">
        <h2 class="mb-6 text-2xl font-semibold text-white">Account Settings</h2>
        <form @submit.prevent="updateProfile" class="space-y-6 bg-white p-6 rounded-xl shadow">
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="w-full rounded border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
            <input v-model="form.email" type="email" class="w-full rounded border-gray-300 p-2 focus:border-indigo-500 focus:ring-indigo-500" />
          </div>
          <div class="flex justify-end">
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Update</button>
          </div>
        </form>
      </div>
    </main>

    <!-- Logout Confirmation Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="w-full max-w-sm rounded-lg bg-white p-6 text-center text-gray-800 shadow-lg">
        <h2 class="mb-4 text-xl font-semibold">Are you sure?</h2>
        <p class="mb-6">Do you really want to logout from your account?</p>
        <div class="flex justify-center space-x-4">
          <button @click="confirmLogout" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">Yes, Logout</button>
          <button @click="showLogoutModal = false" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
  props: {
    user: Object,
  },
  data() {
    return {
      showLogoutModal: false,
      activeSection: 'dashboard',
      form: {
        name: this.user?.name || '',
        email: this.user?.email || ''
      }
    };
  },
  methods: {
    confirmLogout() {
      router.post('/logout');
    },
    goTo(section) {
      this.activeSection = section;
    },
    updateProfile() {
      router.put('/settings/update', this.form);
    }
  }
};
</script>
