<template>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white p-4 flex flex-col justify-between">
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
    <main class="flex-1 p-8">
      <h2 class="text-2xl font-bold mb-4">Course Management</h2>

      <!-- User Table -->
      <div v-if="showUsers" class="mb-6 p-4 bg-white rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Registered Users</h3>
        <table class="w-full text-left border border-gray-300">
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
      <form @submit.prevent="createCourse" class="mb-6 flex gap-4 flex-wrap">
        <input v-model="form.name" placeholder="Course Name" class="border p-2 rounded w-full md:w-auto" required />
        <input v-model="form.description" placeholder="Description" class="border p-2 rounded w-full md:w-auto" />
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Course</button>
      </form>

      <!-- Courses Table -->
      <div v-for="course in courses" :key="course.id" class="mb-8 border rounded p-4">
        <div class="flex justify-between items-center flex-wrap gap-4">
          <div class="flex flex-col gap-1 flex-grow">
            <input v-model="course.name" class="border p-1" />
            <input v-model="course.description" class="border p-1" />
          </div>
          <div class="flex gap-2">
            <button @click="updateCourse(course)" class="text-blue-600">Update</button>
            <button @click="deleteCourse(course.id)" class="text-red-600">Delete</button>
          </div>
        </div>

        <!-- Topics Section -->
        <div class="mt-4 ml-4">
          <h2 class="font-semibold mb-2">Topics</h2>
          <form @submit.prevent="createTopic(course.id, course.newTopic)" class="flex gap-2 flex-wrap mb-2">
            <input v-model="course.newTopic.title" placeholder="New Topic Title" class="border p-1 flex-1" />
            <input v-model="course.newTopic.content" placeholder="New Topic Content" class="border p-1 flex-1" />
            <button class="bg-green-500 text-white px-2 rounded">Add</button>
          </form>

          <ul class="list-disc pl-5">
            <li v-for="(topic, i) in course.topics" :key="topic.id" class="mb-1 flex items-center gap-2 flex-wrap">
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

const courses = ref([])
const users = ref([])
const form = ref({ name: '', description: '' })
const showUsers = ref(false)

const fetchCourses = async () => {
  const res = await axios.get('/api/courses')
  courses.value = res.data.map(course => {
    const topics = course.topics || []
    if (topics.length > 0) topics[0].unlocked = true // Auto unlock first topic
    return {
      ...course,
      newTopic: { title: '', content: '' },
      topics: topics.map((t, i) => ({
        ...t,
        unlocked: i === 0,
        completed: false
      }))
    }
  })
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
  if (!topicData.title || !topicData.content) return
  try {
    const response = await axios.post(`/api/courses/${courseId}/topics`, {
      title: topicData.title,
      content: topicData.content
    })

    const course = courses.value.find(c => c.id === courseId)
    if (course) {
      const newTopic = {
        ...response.data,
        unlocked: course.topics.length === 0,
        completed: false
      }
      course.topics.push(newTopic)
      course.newTopic = { title: '', content: '' }
    }
  } catch (error) {
    console.error('Error adding topic:', error)
  }
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
  try {
    await axios.post('/logout')
    window.location.href = 'Welcome'
  } catch (error) {
    console.error('Logout failed:', error)
  }
}

onMounted(fetchCourses)
</script>
