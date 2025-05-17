<template> 
  <html>
    <head>
      <title>test</title>
    </head>
    <body>
      <div class="flex h-screen w-full flex-wrap bg-zinc-800">
        <!-- Sidebar -->
        <aside class="relative flex h-screen w-60 flex-col bg-white">
          <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
          <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
          <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
          <p class="pl-24 pt-16 text-base font-normal text-zinc-800">Berkan Yuksel</p>
          <nav class="flex flex-col space-y-6 pl-20 pt-14">
            <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
            <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
            <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Settings</a>
          </nav>
          <nav class="flex flex-col space-y-6 pl-20 pt-60">
            <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
          </nav>
        </aside>

        <!-- Main -->
        <main class="flex h-screen flex-1">
          <h2 class="pl-10 pt-5 text-3xl font-bold">Keep Learning</h2>

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
      </div>
    </body>
  </html>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import CircleProgress from 'vue3-circle-progress'

const vueProgress = ref(0)
const laravelProgress = ref(0)

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
