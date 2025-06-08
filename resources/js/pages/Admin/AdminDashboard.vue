<template>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="flex min-h-screen flex-col md:flex-row">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-gray-800 text-white p-4 flex flex-col justify-between">
      <div>
        <h2 class="text-xl font-semibold mb-4">Admin Panel</h2>
        <nav class="flex flex-col gap-2">
          <button @click="showSection = 'users'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Show Users
          </button>
          <button @click="showSection = 'topics'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Manage Topics
          </button>
          <button @click="showSection = 'quizzes'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Manage Quizzes
          </button>
        </nav>
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
      <!-- User Table -->
      <div v-if="showSection === 'users'" class="mb-6 p-4 bg-white rounded shadow overflow-auto">
        <h3 class="text-2xl font-bold mb-4">Registered Users</h3>
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
      <div v-if="showSection === 'topics'" class="p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Topic Management</h2>
        <div v-for="course in courses" :key="course.id" class="mb-6 border rounded shadow">
          <div
            class="flex justify-between items-center bg-gray-100 px-4 py-2 cursor-pointer"
            @click="toggleCourse(course.id)"
          >
            <div>
              <h3 class="text-xl font-semibold">{{ course.name }}</h3>
              <p class="text-gray-600 text-sm">{{ course.description }}</p>
            </div>
            <span class="text-blue-600 font-medium">
              {{ expandedCourses[course.id] ? '▲ Hide' : '▼ Add/View Topics' }}
            </span>
          </div>
          <div v-if="expandedCourses[course.id]" class="p-4">
            <form @submit.prevent="createStandaloneTopicForCourse(course.id)" class="mb-4 w-full space-y-4">
              <div class="flex flex-col">
                <label class="font-medium text-sm mb-1">Title<span class="text-red-500">*</span></label>
                <input v-model="newTopics[course.id].title" placeholder="Enter topic title" class="border p-2 rounded-md" required />
              </div>
              <div class="flex flex-col">
                <label class="font-medium text-sm mb-1">Module Name<span class="text-red-500">*</span></label>
                <input v-model="newTopics[course.id].module_name" placeholder="Enter module name" class="border p-2 rounded-md" required />
              </div>
              <div class="flex flex-col">
                <label class="font-medium text-sm mb-1">Content<span class="text-red-500">*</span></label>
                <textarea v-model="newTopics[course.id].content" placeholder="Describe the topic..." class="border p-2 rounded-md" rows="6" required></textarea>
              </div>
              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-md">
                <span v-if="newTopics[course.id].loading">Adding...</span>
                <span v-else>Add Topic</span>
              </button>
            </form>

            <div v-if="errorMessages[course.id]" class="text-red-500 mb-2">{{ errorMessages[course.id] }}</div>

            <ul>
              <li v-for="topic in standaloneTopics[course.id] || []" :key="topic.id" class="border rounded p-3 mb-3 bg-gray-50">
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="font-semibold text-lg">{{ topic.title }}</h4>
                    <p class="text-sm text-gray-600">{{ topic.module_name }}</p>
                  </div>
                  <div class="flex gap-2">
                    <button @click="editingTopicId = editingTopicId === topic.id ? null : topic.id" class="text-blue-600">
                      {{ editingTopicId === topic.id ? 'Cancel' : 'Edit' }}
                    </button>
                    <button @click="deleteStandaloneTopic(topic.id, course.id)" class="text-red-600">Delete</button>
                  </div>
                </div>
                <div v-if="editingTopicId === topic.id" class="mt-4 space-y-4">
                  <div class="flex flex-col">
                    <label class="text-sm font-medium mb-1">Title</label>
                    <input v-model="topic.title" class="border p-2 rounded" />
                  </div>
                  <div class="flex flex-col">
                    <label class="text-sm font-medium mb-1">Module Name</label>
                    <input v-model="topic.module_name" class="border p-2 rounded" />
                  </div>
                  <div class="flex flex-col">
                    <label class="text-sm font-medium mb-1">Content</label>
                    <textarea v-model="topic.content" rows="4" class="border p-2 rounded resize-y"></textarea>
                  </div>
                  <div class="flex gap-2">
                    <button @click="updateStandaloneTopic(topic)" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
                    <button @click="editingTopicId = null" class="text-gray-600 underline">Cancel</button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Quizzes CRUD -->
      <div v-if="showSection === 'quizzes'" class="p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Quiz Management</h2>
        <form @submit.prevent="createQuiz" class="mb-6 space-y-4">
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Title</label>
            <input v-model="newQuiz.title" class="border p-2 rounded" required />
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Description</label>
            <textarea v-model="newQuiz.description" class="border p-2 rounded"></textarea>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Course</label>
            <select v-model="newQuiz.course_id" class="border p-2 rounded" required>
              <option disabled value="">-- Select Course --</option>
              <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.name }}</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Topic</label>
            <select v-model="newQuiz.topic_id" class="border p-2 rounded" required>
              <option disabled value="">-- Select Topic --</option>
              <option v-for="topic in allTopics" :key="topic.id" :value="topic.id">{{ topic.title }}</option>
            </select>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="newQuiz.is_published" />
            <label class="text-sm">Publish immediately</label>
          </div>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create Quiz</button>
        </form>

        <ul>
          <li v-for="quiz in quizzes" :key="quiz.id" class="border p-3 mb-2 rounded bg-gray-50 flex justify-between">
            <div>
              <h4 class="font-semibold">{{ quiz.title }}</h4>
              <p class="text-sm text-gray-600">{{ quiz.description }}</p>
              <p class="text-xs text-gray-500">Course ID: {{ quiz.course_id }} | Topic ID: {{ quiz.topic_id }} | Published: {{ quiz.is_published }}</p>
            </div>
            <button @click="deleteQuiz(quiz.id)" class="text-red-600 hover:underline">Delete</button>
          </li>
        </ul>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const users = ref([])
const courses = ref([])
const quizzes = ref([])
const allTopics = ref([])
const standaloneTopics = ref({})
const newTopics = ref({})
const expandedCourses = ref({})
const errorMessages = ref({})
const editingTopicId = ref(null)
const showSection = ref('topics')
const newQuiz = ref({
  title: '',
  description: '',
  course_id: '',
  topic_id: '',
  is_published: false
})

const fetchCourses = async () => {
  const res = await axios.get('/api/courses')
courses.value = res.data
  for (const course of courses.value) {
    newTopics.value[course.id] = { title: '', content: '', module_name: '', loading: false }
    expandedCourses.value[course.id] = false
    const topicUrl = course.name === 'Laravel Frameworks'
      ? `/api/courses/${course.id}/laravel-topics`
      : `/api/courses/${course.id}/topics`
    const topicRes = await axios.get(topicUrl)
    standaloneTopics.value[course.id] = topicRes.data
  }
}

const fetchUsers = async () => {
  const res = await axios.get('/api/users')
  users.value = res.data
}

const fetchQuizzes = async () => {
  const res = await axios.get('/api/quizzes')
  quizzes.value = res.data
}

const fetchAllTopics = async () => {
  const res = await axios.get('/api/topics')
  allTopics.value = res.data
}

const createStandaloneTopicForCourse = async (courseId) => {
  const course = courses.value.find(c => c.id === courseId)
  const isLaravel = course.name === 'Laravel Frameworks'
  errorMessages.value[courseId] = ''
  newTopics.value[courseId].loading = true
  try {
    const postUrl = isLaravel
      ? `/api/courses/${courseId}/laravel-topics`   
      : `/api/courses/${courseId}/topics`
    const response = await axios.post(postUrl, newTopics.value[courseId])
    // Ensure course_id is present in the returned topic
    if (!response.data.course_id) {
      response.data.course_id = courseId
    }
    standaloneTopics.value[courseId].push(response.data)
    newTopics.value[courseId] = { title: '', content: '', module_name: '', loading: false }
    errorMessages.value[courseId] = ''
  } catch (e) {
    errorMessages.value[courseId] = e.response?.data?.message || 'Failed to add topic'
  } finally {
    newTopics.value[courseId].loading = false
  }
}

const updateStandaloneTopic = async (topic) => {
  const course = courses.value.find(c => c.id === topic.course_id)
  const isLaravel = course.name === 'Laravel Frameworks'
  const updateUrl = isLaravel ? `/api/laravel-topics/${topic.id}` : `/api/topics/${topic.id}`
  await axios.put(updateUrl, topic)
  editingTopicId.value = null
}

const deleteStandaloneTopic = async (id, courseId) => {
  const course = courses.value.find(c => c.id === courseId)
  const isLaravel = course.name === 'Laravel Frameworks'
  const deleteUrl = isLaravel ? `/api/laravel-topics/${id}` : `/api/topics/${id}`
  await axios.delete(deleteUrl)
  standaloneTopics.value[courseId] = standaloneTopics.value[courseId].filter(t => t.id !== id)
}

const createQuiz = async () => {
  const res = await axios.post('/api/quizzes', newQuiz.value)
  quizzes.value.push(res.data)
  newQuiz.value = { title: '', description: '', course_id: '', topic_id: '', is_published: false }
}

const deleteQuiz = async (id) => {
  await axios.delete(`/api/quizzes/${id}`)
  quizzes.value = quizzes.value.filter(q => q.id !== id)
}

watch(showSection, async (val) => {
  if (val === 'users') await fetchUsers()
  if (val === 'quizzes') {
    await fetchQuizzes()
    await fetchAllTopics()
  }
})

const toggleCourse = (courseId) => {
  expandedCourses.value[courseId] = !expandedCourses.value[courseId]
}

const logout = async () => {
  await axios.post('/logout')
  window.location.href = '/'
}

onMounted(fetchCourses)
</script>
