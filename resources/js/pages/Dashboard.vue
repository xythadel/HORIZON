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
       {{ user.firstname }} {{ user.lastname }}
      </p>


      <!-- Navigation Panel -->
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
          </svg>
          Dashboard
        </a>
        <a href="/test" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Course</a> 
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a> 
        <a href="/sandpit" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Sandpit</a> 
        <a href="/badges" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Badge</a> 

        <a href="/settings" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Profiles</a>
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
      <div class="w-full rounded flex gap-3">
        <div class="w-2/3 rounded-md">
          <div class="bg-white p-5 rounded-md shadow mb-6">
            <h2 class="text-xl font-semibold text-zinc-800 mb-4">Latest Score per Topic</h2>
            <canvas ref="chartRefScore" height="100"></canvas>
          </div>

          <div class="bg-white p-5 rounded-md shadow mb-6">
            <h2 class="text-xl font-semibold text-zinc-800 mb-4">Total Time per Topic</h2>
            <canvas ref="chartRefTime" height="100"></canvas>
          </div>

        </div>
        <div class="w-1/3 flex flex-col">
          <div class=" rounded-md text-white p-5 border max-h-[450px] min-h-[450px]">
            <h1 class="text-2xl border-b-2 pb-3 mb-3">Leaderboards</h1>
            <div v-if="leaderboard?.length">
              <div
                v-for="(entry, index) in leaderboard"
                :key="entry.id"
                class="bg-white text-zinc-800 mb-3 px-4 py-2 rounded-2xl shadow"
              >
                <div class="flex justify-between items-center">
                  <h2 class="text-lg font-semibold">
                    {{ entry.name }}
                  </h2>
                  <span class="text-sm text-gray-500">#{{ index + 1 }}</span>
                </div>
                <p class="text-sm mt-1">Topics Completed: <strong>{{ entry.topics_completed }}</strong></p>
              </div>
            </div>
            <div v-else class="text-sm text-gray-300 mt-4">No data yet.</div>
          </div>
          <div class="border mt-6 p-5 rounded-md text-white">
            <h2 class="text-2xl border-b-2 pb-3 mb-3">Your Course Progress</h2>

            <div v-if="progressData?.length">
              <div
                v-for="(course, idx) in progressData"
                :key="idx"
                class="mb-4 bg-white text-zinc-800 p-4 rounded shadow"
              >
                <h3 class="text-lg font-semibold">{{ course.course_name }}</h3>
                <p class="text-sm">
                  Topics Completed: <strong>{{ course.topics_completed }}</strong> /
                  {{ course.topics_total }}
                </p>
                <p class="text-sm">Progress: <strong>{{ course.overall_progress }}%</strong></p>
              </div>
            </div>

            <div v-else class="text-sm text-gray-300 mt-4">No course progress yet.</div>
          </div>

        </div>
      </div>
    </main>
  </div>

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

<script setup>
import { ref, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const { auth } = usePage().props
const user = auth.user

const chartRefScore = ref(null)
const chartRefTime = ref(null)
let chartScore = null
let chartTime = null

const leaderboard = ref([])
const progressData = ref([])
const showLogoutModal = ref(false)

onMounted(async () => {
  // Load leaderboard
  const lbRes = await fetch('/api/leaderboard')
  leaderboard.value = await lbRes.json()

  // Load progress from displayRatings
  const progRes = await fetch(`/api/rating/${user.id}`)
  const progressJson = await progRes.json()
  progressData.value = progressJson.progress_by_course

  // Prepare chart data
  const topicLabels = []
  const scores = []
  const times = []

  progressData.value.forEach(course => {
    course.topics.forEach(topic => {
      topicLabels.push(topic.title)
      scores.push(topic.score)
      times.push(topic.time_taken)
    })
  })

  // Destroy if already exists
  if (chartScore) chartScore.destroy()
  if (chartTime) chartTime.destroy()

  // Chart: Score
  chartScore = new Chart(chartRefScore.value, {
    type: 'line',
    data: {
      labels: topicLabels,
      datasets: [{
        label: 'Score per Topic',
        data: scores,
        borderColor: '#288feb',
        backgroundColor: '#288feb',
        tension: 0.3,
        fill: false,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false }},
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Score' }
        }
      }
    }
  })

  // Chart: Time
  chartTime = new Chart(chartRefTime.value, {
    type: 'line',
    data: {
      labels: topicLabels,
      datasets: [{
        label: 'Total Time Spent per Topic (mins)',
        data: times,
        borderColor: '#f97316',
        backgroundColor: '#f97316',
        tension: 0.3,
        fill: false,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false }},
      scales: {
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Time (mins)' }
        }
      }
    }
  })
})

const confirmLogout = () => {
  router.post('/logout')
}
</script>
