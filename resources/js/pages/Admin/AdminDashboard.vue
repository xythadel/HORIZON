<template>
  <div class="flex flex-col min-h-screen md:flex-row">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-gray-800 text-white p-4">
      <h2 class="text-xl font-semibold mb-4">Admin Panel</h2>
      <button @click="toggleUsers" class="w-full text-left mb-2 bg-gray-700 p-2 rounded hover:bg-gray-600">
        Show Users
      </button>
      <button @click="logout" class="w-full text-left bg-red-600 p-2 rounded hover:bg-red-500">
        Logout
      </button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-8">
      <h2 class="text-2xl font-bold mb-4">Course Management</h2>

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

      <!-- Course Form -->
      <form @submit.prevent="createCourse" class="mb-6 flex flex-col md:flex-row gap-4">
        <input v-model="form.name" placeholder="Course Name" class="border p-2 rounded flex-1" required />
        <input v-model="form.description" placeholder="Description" class="border p-2 rounded flex-1" />
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Course</button>
      </form>

      <!-- Courses Table -->
      <div v-for="course in courses" :key="course.id" class="mb-8 border rounded p-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
          <div class="flex flex-col gap-1 w-full md:w-3/4">
            <input v-model="course.name" class="border p-1" />
            <input v-model="course.description" class="border p-1" />
          </div>
          <div class="flex gap-2 mt-2 md:mt-0">
            <button @click="updateCourse(course)" class="text-blue-600">Update</button>
            <button @click="deleteCourse(course.id)" class="text-red-600">Delete</button>
          </div>
        </div>

        <!-- Topics Section -->
        <div class="mt-4 ml-4">
          <h2 class="font-semibold mb-2">Topics</h2>
          <form @submit.prevent="createTopic(course.id, course.newTopic)" class="flex flex-col md:flex-row gap-2 mb-2">
            <input v-model="course.newTopic.title" placeholder="New Topic Title" class="border p-1 flex-1" />
            <input v-model="course.newTopic.content" placeholder="New Topic Content" class="border p-1 flex-1" />
            <button class="bg-green-500 text-white px-2 py-1 rounded">Add</button>
          </form>

          <ul class="list-disc pl-5">
            <li v-for="topic in course.topics" :key="topic.id" class="mb-1 flex flex-col md:flex-row items-start md:items-center gap-2">
              <span class="text-sm text-gray-600">#{{ topic.id }}</span>
              <input v-model="topic.title" placeholder="Title" class="border p-1 flex-1" />
              <input v-model="topic.content" placeholder="Content" class="border p-1 flex-1" />
              <button @click="updateTopic(topic)" class="text-blue-600">Update</button>
              <button @click="deleteTopic(topic.id, course.id)" class="text-red-600">Delete</button>
            </li>
          </ul>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const courses = ref([])
const users = ref([])
const form = ref({ name: '', description: '' })
const showUsers = ref(false)
const router = useRouter()

const fetchCourses = async () => {
  const res = await axios.get('/api/courses')
  courses.value = res.data.map(course => ({
    ...course,
    newTopic: { title: '', content: '' },
    topics: course.topics || []
  }))
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

const createCourse = async () => {
  await axios.post('/api/courses', form.value)
  form.value.name = ''
  form.value.description = ''
  fetchCourses()
}

const updateCourse = async (course) => {
  await axios.put(`/api/courses/${course.id}`, {
    name: course.name,
    description: course.description
  })
  fetchCourses()
}

const deleteCourse = async (id) => {
  await axios.delete(`/api/courses/${id}`)
  fetchCourses()
}

const createTopic = async (courseId, topicData) => {
  await axios.post(`/api/courses/${courseId}/topics`, {
    title: topicData.title,
    content: topicData.content
  })
  fetchCourses()
}

const updateTopic = async (topic) => {
  await axios.put(`/api/topics/${topic.id}`, {
    title: topic.title,
    content: topic.content
  })
  fetchCourses()
}

const deleteTopic = async (topicId, courseId) => {
  await axios.delete(`/api/topics/${topicId}`)
  fetchCourses()
}

const logout = async () => {
  await axios.post('/logout')
  router.push('/')
}

onMounted(fetchCourses)
</script>

<style scoped>
@media (max-width: 768px) {
  aside {
    width: 100%;
  }
}
</style>
