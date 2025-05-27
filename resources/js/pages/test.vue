<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
      <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
      <p v-if="user" class="pl-24 pt-16 text-base font-normal text-zinc-800">
        {{ user.name }}
      </p>

      <!-- Navigation -->
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="/test" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
        <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Settings</a>
      </nav>

      <!-- Logout -->
      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a
          href="#"
          @click.prevent="showLogoutModal = true"
          class="text-base font-normal text-zinc-800 hover:text-indigo-600"
        >Logout</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="flex h-screen flex-1">
      <h2 class="pl-10 pt-5 text-3xl font-bold text-white">Keep Learning</h2>

      <!-- Vue Course Card -->
      <div class="absolute left-[280px] top-[80px] h-60 w-[400px] rounded-[30px] bg-white"></div>
      <h3 class="absolute left-[330px] top-[120px] text-xl font-normal text-stone-900">Course</h3>
      <h4 class="absolute left-[330px] top-[150px] text-2xl font-semibold text-stone-900">Vue.js</h4>
      <p class="absolute left-[330px] top-[200px] w-80 text-xs font-normal text-stone-900">
        An open-source model–view–viewmodel front-end JavaScript framework for building user interfaces and single-page applications.
      </p>
      <a href="/modulevue">
        <button class="absolute left-[550px] top-[270px] text-xl font-medium text-stone-900 hover:text-green-500">Start</button>
      </a>

      <!-- Vue Progress Circle -->
      <div class="absolute left-[700px] top-[100px] flex flex-col items-center">
        <CircleProgress
          :progress="vueProgress"
          :size="150"
          :strokeWidth="12"
          color="#48C9B0"
        />
        <p class="mt-2 text-white">Vue Progress {{ vueProgress }}%</p>
      </div>

      <!-- Laravel Course Card -->
      <div class="absolute left-[280px] top-[400px] h-60 w-[400px] rounded-[30px] bg-white"></div>
      <h5 class="absolute left-[330px] top-[440px] text-xl font-normal text-stone-900">Course</h5>
      <h6 class="absolute left-[330px] top-[470px] text-2xl font-semibold text-stone-900">Laravel</h6>
      <p class="absolute left-[330px] top-[520px] w-80 text-xs font-normal text-stone-900">
        A free and open-source PHP-based web framework for building web applications.
      </p>
      <a href="/modulelara">
        <button class="absolute left-[550px] top-[590px] text-xl font-medium text-stone-900 hover:text-green-500">Start</button>
      </a>

      <!-- Laravel Progress Circle -->
      <div class="absolute left-[700px] top-[420px] flex flex-col items-center">
        <CircleProgress
          :progress="laravelProgress"
          :size="150"
          :strokeWidth="12"
          color="#E74C3C"
        />
        <p class="mt-2 text-white">Laravel Progress {{ laravelProgress }}%</p>
      </div>
    </main>

    <!-- Logout Modal -->
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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import CircleProgress from 'vue3-circle-progress'

// Access authenticated user from Inertia
const { auth } = usePage().props
const user = auth?.user

// Reactive state
const vueProgress = ref(0)
const laravelProgress = ref(0)
const showLogoutModal = ref(false)

// Logout function
function confirmLogout() {
  router.post('/logout', {}, {
    onFinish: () => {
      window.location.href = '/login'
    }
  })
}

// Fetch progress
onMounted(async () => {
  try {
    const res = await fetch('/api/user-progress')
    const data = await res.json()

    const vue = data.progress_by_course.find(course => course.course_name === 'Vue.js')
    const laravel = data.progress_by_course.find(course => course.course_name === 'Laravel')

    vueProgress.value = vue ? vue.overall_progress : 0
    laravelProgress.value = laravel ? laravel.overall_progress : 0

  } catch (error) {
    console.error('Failed to fetch course progress:', error)
  }
})
</script>
