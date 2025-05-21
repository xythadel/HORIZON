<template>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-6 flex flex-col">
      <h2 class="text-xl font-semibold mb-6">Admin Panel</h2>
      <button @click="toggleUsers" class="mb-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
        {{ showUsers ? 'Hide' : 'Show' }} Users
      </button>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-8 bg-gray-100">
      <h2 class="text-2xl font-bold mb-4">Course Management</h2>

      <!-- Show User List -->
      <div v-if="showUsers" class="mb-6 p-4 bg-white rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Registered Users</h3>
        <ul class="list-disc pl-6">
          <li v-for="user in users" :key="user.id">
            {{ user.name }} - {{ user.email }}
          </li>
        </ul>
      </div>

      <!-- Course Form -->
      <form @submit.prevent="createCourse" class="mb-6 flex gap-4">
        <input v-model="form.name" placeholder="Course Name" class="border p-2 rounded" required />
        <input v-model="form.description" placeholder="Description" class="border p-2 rounded" />
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Course</button>
      </form>

      <!-- Courses Table -->
      <div v-for="course in courses" :key="course.id" class="mb-8 border rounded p-4 bg-white shadow">
        <div class="flex justify-between items-center">
          <div class="flex flex-col gap-1">
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
          <form @submit.prevent="createTopic(course.id, course.newTopic)" class="flex gap-2 mb-2">
            <input v-model="course.newTopic.title" placeholder="New Topic Title" class="border p-1 flex-1" />
            <input v-model="course.newTopic.content" placeholder="New Topic Content" class="border p-1 flex-1" />
            <button class="bg-green-500 text-white px-2 rounded">Add</button>
          </form>

          <ul class="list-disc pl-5">
            <li v-for="topic in course.topics" :key="topic.id" class="mb-1 flex items-center gap-2">
              <span class="text-sm text-gray-600">#{{ topic.id }}</span>
              <input v-model="topic.title" placeholder="Title" class="border p-1 flex-1" />
              <input v-model="topic.content" placeholder="Content" class="border p-1 flex-1" />
              <button @click="updateTopic(topic)" class="text-blue-600">Update</button>
              <button @click="deleteTopic(topic.id, course.id)" class="text-red-600">Delete</button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const courses = ref([])
const users = ref([])
const showUsers = ref(false)

const form = ref({
  name: '',
  description: ''
})

// Fetch all courses and their topics
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

// Toggle visibility of users
const toggleUsers = async () => {
  showUsers.value = !showUsers.value
  if (showUsers.value && users.value.length === 0) {
    await fetchUsers()
  }
}

// Course CRUD
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

// Topic CRUD
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

onMounted(fetchCourses)
</script>
