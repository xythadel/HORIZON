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
  <!-- Title Field -->
  <div class="flex flex-col">
    <label class="font-medium text-sm mb-1">
      Title<span class="text-red-500">*</span>
    </label>
    <input 
      v-model="newTopics[course.id].title" 
      placeholder="Enter topic title" 
      class="border p-2 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
      required
      :disabled="newTopics[course.id].loading"
    />
  </div>

  <!-- Content Field -->
  <div class="flex flex-col">
    <label class="font-medium text-sm mb-1">
      Content<span class="text-red-500">*</span>
    </label>
    <textarea 
      v-model="newTopics[course.id].content" 
      placeholder="Describe the topic in detail..."
      class="border p-2 rounded-md bg-white resize-y focus:outline-none focus:ring-2 focus:ring-blue-500"
      rows="6"
      required
      :disabled="newTopics[course.id].loading"
    ></textarea>
    <p class="text-sm text-gray-500 mt-1">Please enter a Guide description.</p>
  </div>

  <!-- Submit Button -->
  <button 
    type="submit" 
    class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-md"
    :disabled="newTopics[course.id].loading"
  >
    <span v-if="newTopics[course.id].loading">Adding...</span>
    <span v-else>Add Topic</span>
  </button>
</form>



              <div v-if="errorMessages[course.id]" class="text-red-500 mb-2">
                {{ errorMessages[course.id] }}
              </div>

              <ul>
                <li 
                  v-for="topic in standaloneTopics[course.id] || []" 
                  :key="topic.id" 
                  class="flex flex-wrap items-center gap-2 mb-2"
                >
                  <input v-model="topic.title" class="border p-1 rounded flex-1 responsive-min-width" />
                  <input v-model="topic.content" class="border p-1 rounded flex-1 responsive-min-width" />
                  <button @click="updateStandaloneTopic(topic)" class="text-blue-600">Update</button>
                  <button @click="deleteStandaloneTopic(topic.id, course.id)" class="text-red-600">Delete</button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>
  </template>

  <script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const users = ref([])
const standaloneTopics = ref({}) // courseId -> array of topics
const courses = ref([])
const showSection = ref('topics')
const errorMessages = ref({})
const newTopics = ref({})
const expandedCourses = ref({})

const fetchCourses = async () => {
  try {
    const res = await axios.get('/api/courses')
    courses.value = res.data.filter(course =>
      course.name === 'Laravel Frameworks' || course.name === 'Vue Frameworks'
    )

    for (const course of courses.value) {
      newTopics.value[course.id] = { title: '', content: '', loading: false }
      expandedCourses.value[course.id] = false

      try {
        const topicUrl = course.name === 'Laravel Frameworks'
          ? `/api/courses/${course.id}/laravel-topics`
          : `/api/courses/${course.id}/topics`

        const topicRes = await axios.get(topicUrl)
        standaloneTopics.value[course.id] = topicRes.data
      } catch (topicErr) {
        console.error(`Failed to fetch topics for course ${course.id}`, topicErr)
      }
    }
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  }
}

const createStandaloneTopicForCourse = async (courseId) => {
  try {
    const course = courses.value.find(c => c.id === courseId)
    const isLaravel = course.name === 'Laravel Frameworks'

    errorMessages.value[courseId] = ''
    newTopics.value[courseId].loading = true

    const postUrl = isLaravel
      ? `/api/courses/${courseId}/laravel-topics`
      : `/api/courses/${courseId}/topics`

    const response = await axios.post(postUrl, {
      title: newTopics.value[courseId].title,
      content: newTopics.value[courseId].content
    })

    standaloneTopics.value[courseId].push(response.data)
    newTopics.value[courseId] = { title: '', content: '', loading: false }
  } catch (error) {
    errorMessages.value[courseId] = error.response?.data?.message || 'Failed to add topic'
  } finally {
    newTopics.value[courseId].loading = false
  }
}

const updateStandaloneTopic = async (topic) => {
  try {
    const course = courses.value.find(c => c.id === topic.course_id)
    const isLaravel = course.name === 'Laravel Frameworks'

    const updateUrl = isLaravel
      ? `/api/laravel-topics/${topic.id}`
      : `/api/topics/${topic.id}`

    await axios.put(updateUrl, {
      title: topic.title,
      content: topic.content
    })
    alert('Topic updated successfully.')
  } catch (error) {
    console.error('Update failed:', error)
    alert(error.response?.data?.message || 'Failed to update topic.')
  }
}

const deleteStandaloneTopic = async (id, courseId) => {
  try {
    const course = courses.value.find(c => c.id === courseId)
    const isLaravel = course.name === 'Laravel Frameworks'

    const deleteUrl = isLaravel
      ? `/api/laravel-topics/${id}`
      : `/api/topics/${id}`

    await axios.delete(deleteUrl)
    standaloneTopics.value[courseId] = standaloneTopics.value[courseId].filter(t => t.id !== id)
    alert('Topic deleted successfully.')
  } catch (error) {
    console.error('Delete failed:', error)
    alert(error.response?.data?.message || 'Failed to delete topic.')
  }
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/api/users')
    users.value = res.data
  } catch (error) {
    console.error('Failed to fetch users:', error)
  }
}

watch(showSection, async (newVal) => {
  if (newVal === 'users' && users.value.length === 0) {
    await fetchUsers()
  }
})

const toggleCourse = (courseId) => {
  expandedCourses.value[courseId] = !expandedCourses.value[courseId]
}

const logout = async () => {
  try {
    await axios.post('/logout')
    window.location.href = '/'
  } catch (error) {
    console.error('Logout failed:', error)
    alert('Logout failed.')
  }
}

onMounted(async () => {
  await fetchCourses()
})
</script>
  