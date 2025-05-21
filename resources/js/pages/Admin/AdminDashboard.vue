<template>
  <div class="flex min-h-screen flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-gray-800 text-white p-4 flex flex-col justify-between">
      <div>
        <h2 class="text-xl font-semibold mb-4">Admin Panel</h2>
        <button @click="toggleUsers" class="w-full text-left mb-2 bg-gray-700 p-2 rounded hover:bg-gray-600">
          Show Users
        </button>
      </div>
      <button
        @click="logout"
        class="w-full mt-4 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition duration-200"
      >
        Logout
      </button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8">
      <h2 class="text-2xl font-bold mb-4">Topic Management</h2>

      <!-- User Table -->
      <div v-if="showUsers" class="mb-6 p-4 bg-white rounded shadow overflow-auto">
        <h3 class="text-lg font-semibold mb-2">Registered Users</h3>
        <table class="w-full text-left border border-gray-300 text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 border">ID</th>
              <th class="p-2 border">Name</th>
              <th class="p-2 border">Email</th>
              <th class="p-2 border">Role</th>
              <th class="p-2 border">Created At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
              <td class="p-2 border">{{ user.id }}</td>
              <td class="p-2 border">{{ user.name }}</td>
              <td class="p-2 border">{{ user.email }}</td>
              <td class="p-2 border">{{ user.role }}</td>
              <td class="p-2 border">{{ new Date(user.created_at).toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Topics CRUD -->
      <div class="p-4 bg-white rounded shadow">
        <form @submit.prevent="createStandaloneTopic" class="mb-4 flex flex-wrap gap-2">
          <input v-model="newStandaloneTopic.title" placeholder="Topic Title" class="border p-2 rounded flex-1 responsive-min-w" required />
          <input v-model="newStandaloneTopic.content" placeholder="Topic Content" class="border p-2 rounded flex-1 responsive-min-w" required />
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add Topic</button>
        </form>

        <ul>
          <li v-for="topic in standaloneTopics" :key="topic.id" class="flex flex-wrap items-center gap-2 mb-2">
            <input v-model="topic.title" class="border p-1 rounded flex-1 responsive-min-w" />
            <input v-model="topic.content" class="border p-1 rounded flex-1 responsive-min-w" />
            <button @click="updateStandaloneTopic(topic)" class="text-blue-600">Update</button>
            <button @click="deleteStandaloneTopic(topic.id)" class="text-red-600">Delete</button>
          </li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref([])
const showUsers = ref(false)

const standaloneTopics = ref([])
const newStandaloneTopic = ref({ title: '', content: '' })

const fetchStandaloneTopics = async () => {
  const res = await axios.get('/api/topics')
  standaloneTopics.value = res.data
}

const createStandaloneTopic = async () => {
  const res = await axios.post('/api/topics', newStandaloneTopic.value)
  standaloneTopics.value.push(res.data)
  newStandaloneTopic.value = { title: '', content: '' }
}

const updateStandaloneTopic = async (topic) => {
  await axios.put(`/api/topics/${topic.id}`, {
    title: topic.title,
    content: topic.content
  })
  fetchStandaloneTopics()
}

const deleteStandaloneTopic = async (id) => {
  await axios.delete(`/api/topics/${id}`)
  fetchStandaloneTopics()
}

const fetchUsers = async () => {
  const res = await axios.get('/api/users')
  users.value = res.data
}

const toggleUsers = async () => {
  showUsers.value = !showUsers.value
  if (showUsers.value && users.value.length === 0) {
    await fetchUsers()
  }
}

const logout = async () => {
  try {
    await axios.post('/logout')
    window.location.href = 'Welcome'
  } catch (error) {
    console.error('Logout failed:', error)
  }
}

onMounted(fetchStandaloneTopics)
</script>

<style scoped>
@media (max-width: 768px) {
  .responsive-min-w {
    min-width: 100%;
  }
}
</style>
<style></style>