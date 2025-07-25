<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
      <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
      <p v-if="user" class="pl-24 pt-16 text-base font-normal text-zinc-800">{{ user.name }}</p>

      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
        <a href="/test" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Course</a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
        <a href="/sandpit" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Sandpit</a>
        <a href="/badges" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Badge</a>
        <a href="/settings" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Profiles</a>
      </nav>

      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a href="/logout" @click.prevent="showLogoutModal = true" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
      </nav>
    </aside>

    <main class="flex-1 p-12">
      <h2 class="text-2xl font-bold mb-4 text-white border-b-2 pb-2">All Badges</h2>
      <div class="rounded shadow">
        <div v-if="badges.length">
          <ul class="grid grid-cols-4 gap-4">
            <li v-for="badge in badges" :key="badge.id" class="p-3 flex items-center gap-4 flex-col">
              <div class="text-center w-full flex flex-col justify-center items-center">
                <img :src="badge.image" alt="Badge Image" class="w-20 h-20 object-cover rounded-full" />
                <h4 class="font-semibold text-white">{{ badge.title }}</h4>
                <p class="text-sm text-gray-50">{{ badge.description }}</p>

                <div class="mt-2">
                  <div class="w-full h-3 bg-gray-200 rounded-full">
                    <div
                      class="h-3 bg-green-500 rounded-full transition-all duration-300"
                      :style="{ width: badge.progress + '%' }"
                    ></div>
                  </div>
                  <p class="text-sm mt-1 text-gray-50">{{ badge.progress }}% progress</p>
                </div>

                <button
                  :disabled="badge.claimed || !badge.claimable"
                  @click="!badge.claimed && badge.claimable && claimBadge(badge.id)"
                  class="mt-5 px-3 py-1 rounded-md text-lg w-full text-white"
                  :class="badge.claimed ? 'bg-gray-400 cursor-not-allowed' : (badge.claimable ? 'bg-blue-500 hover:bg-blue-600' : 'bg-red-600 cursor-not-allowed')"
                >
                  {{ badge.claimed ? 'Claimed' : (badge.claimable ? 'Claim Badge' : 'Incomplete') }}
                </button>
              </div>
            </li>
          </ul>
        </div>
        <div v-else>
          <p>No badges available at the moment.</p>
        </div>
      </div>
      <h2 class="text-2xl font-bold mt-10 mb-4 text-white border-b-2 pb-2">Claimed Badges</h2>
      <div class="rounded shadow">
        <div v-if="badges.some(b => b.claimed)">
          <ul class="flex gap-4">
            <li
              v-for="badge in badges.filter(b => b.claimed)"
              :key="badge.id"
              class="flex items-center gap-2 flex-col"
            >
              <img :src="badge.image" alt="Badge Image" class="w-16 h-16 object-cover rounded-full" />
              <div class="text-center">
                <h4 class="font-semibold text-white">{{ badge.title }}</h4>
              </div>
            </li>
          </ul>
        </div>
        <div v-else>
          <p class="text-gray-600">No claimed badges yet.</p>
        </div>
      </div>
    </main>
  </div>

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
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import axios from 'axios'

const { auth } = usePage().props
const user = auth.user
const badges = ref([])
const showLogoutModal = ref(false)

const fetchBadges = async () => {
  try {
    const [allBadges, claimedBadges] = await Promise.all([
      axios.get(`/api/validateBadge/${user.id}`),
      axios.get(`/api/badges/claimed/${user.id}`)
    ])

    const claimedIds = claimedBadges.data.map(b => b.id)

    badges.value = allBadges.data.badges.map(badge => ({
      ...badge,
      claimed: claimedIds.includes(badge.id),
      progress: allBadges.data.progressData[badge.id]?.progress || 0,
      claimable: allBadges.data.progressData[badge.id]?.claimable || false
    }))
  } catch (error) {
    console.error('Failed to fetch badges:', error)
  }
}

const claimBadge = async (badgeId) => {
  try {
    const res = await axios.post(`/api/badges/claim/${user.id}`, {
      badge_id: badgeId
    })
    alert(res.data.message)
    await fetchBadges()
  } catch (error) {
    alert(error.response?.data?.message || 'Failed to claim badge.')
  }
}

const confirmLogout = () => {
  router.post('/logout')
}

onMounted(fetchBadges)
</script>

