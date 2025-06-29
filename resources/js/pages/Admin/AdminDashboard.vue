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
          <button @click="showSection = 'lessons'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Lessons
          </button>
          <button @click="showSection = 'quizzes'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Manage Quizzes
          </button>
          <button @click="showSection = 'badges'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Badge
          </button>
          <button @click="showSection = 'reports'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Reports
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
            <label class="font-medium text-sm mb-1">Type</label>
            <select name="type" id="" class="border p-2 rounded" v-model="newQuiz.questionType" @change="displayFields">
              <option value="">Select Type</option>
              <option value="Blank">Fill in the Blanks</option>
              <option value="Choices">Multiple Choice</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Answer</label>
            <textarea v-model="newQuiz.answer" class="border p-2 rounded"></textarea>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Difficulty</label>
            <select v-model="newQuiz.difficulty" class="border p-2 rounded">
              <option value="">SELECT DIFFICULTY</option>
              <option value="1">Beginner</option>
              <option value="2">Intermediate</option>
              <option value="3">Advance</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Category</label>
            <!-- <textarea ></textarea> -->
            <select v-model="newQuiz.questionCategory" class="border p-2 rounded">
              <option value="">SELECT CATEGORY</option>
              <option value="Pre-test">Pre-test</option>
              <option value="Post-test">Post-test</option>
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
          <div v-if="fieldsVisible" class="flex flex-col gap-y-2">
            <label>Options</label>
            <div v-for="(opt, index) in options" :key="index" class="flex items-center border rounded-md">
              <div class="w-10 p-2 text-center bg-green-300">
                {{ String.fromCharCode(65 + index) }}.
              </div>
              <input v-model="options[index]" type="text" class="w-full rounded-r-md border-none" />
            </div>
          </div>

          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create Quiz</button>
        </form>

        <ul>
          <li v-for="quiz in quizzes" :key="quiz.id" class="border p-3 mb-2 rounded bg-gray-50 flex justify-between">
            <div>
              <h4 class="font-semibold">{{ quiz.title }}</h4>
              <p class="text-sm text-gray-600">{{ quiz.description }}</p>
              <p class="text-xs text-gray-500">Type: {{ quiz.questionCategory }} | Topic ID: {{ quiz.topic_id }} | Published: {{ quiz.is_published }}</p>
            </div>
            <button @click="deleteQuiz(quiz.id)" class="text-red-600 hover:underline">Delete</button>
          </li>
        </ul>
      </div>

      <div v-if="showSection === 'badges'" class="p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Add Badge</h2>
        <form @submit.prevent="createBadge" class="space-y-4">
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Title</label>
            <input v-model="newBadge.title" class="border p-2 rounded" required />
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Description</label>
            <textarea v-model="newBadge.description" class="border p-2 rounded" required></textarea>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Image URL</label>
            <input v-model="newBadge.image" class="border p-2 rounded" required />
          </div>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Create Badge</button>
        </form>
        <div class="mt-6">
          <h3 class="text-xl font-semibold mb-2">All Badges</h3>
          <ul>
            <li v-for="badge in badges" :key="badge.id" class="border p-3 mb-2 rounded bg-gray-50">
              <img :src="badge.image" class="w-12 h-12" />
              <div>
                <h4 class="font-semibold">{{ badge.title }}</h4>
                <p class="text-sm">{{ badge.description }}</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div v-if="showSection === 'reports'" class="p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Reports</h2>
        <div class="space-y-4">
          <div v-for="type in reportTypes" :key="type" class="flex justify-between items-center border p-4 rounded">
            <span class="capitalize">{{ type.replace('-', ' ') }} Report</span>
            <div class="space-x-2">
              <!-- <button @click="fetchReport(type)" class="bg-blue-600 text-white px-4 py-1 rounded">View</button> -->
              <button @click="downloadPdf(type)" class="bg-green-600 text-white px-4 py-1 rounded">Download PDF</button>
            </div>
          </div>

          <div v-if="reportResults" class="mt-6">
            <pre class="bg-gray-100 p-4 rounded max-h-[600px] overflow-auto text-sm">{{ JSON.stringify(reportResults, null, 2) }}</pre>
          </div>
        </div>
      </div>

      <div v-if="showSection === 'lessons'" class="p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Manage Lessons</h2>
        <div class="flex gap-4 mb-4">
          <button
            :class="lessonTab === 'vue' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
            class="px-4 py-2 rounded"
            @click="lessonTab = 'vue'"
          >
            Vue Lessons
          </button>
          <button
            :class="lessonTab === 'laravel' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
            class="px-4 py-2 rounded"
            @click="lessonTab = 'laravel'"
          >
            Laravel Lessons
          </button>
        </div>

        <div v-for="topic in filteredTopics" :key="topic.id" class="mb-6 border rounded shadow" >
          <div class="p-4 bg-gray-100 flex justify-between items-center">
            <div>
              <h3 class="text-xl font-semibold">{{ topic.title }}</h3>
            </div>
            <button @click="toggleLesson(topic.id)" class="text-blue-600 font-medium">
              {{ lessonToggles[topic.id] ? 'Hide' : 'Add/View Lessons' }}
            </button>
          </div>

          <div v-if="lessonToggles[topic.id]" class="p-4 space-y-6">
            <form @submit.prevent="createLesson(topic.id)" class="space-y-4">
              <label class="block font-medium">Difficulty</label>
              <select v-model="newLesson[topic.id].difficulty" class="border rounded p-2 w-full" required>
                <option disabled value="">-- Select Difficulty --</option>
                <option value="1">Beginner</option>
                <option value="2">Intermediate</option>
                <option value="3">Advanced</option>
              </select>

              <label class="block font-medium">Content</label>
                <QuillEditor
                  v-model:content="newLesson[topic.id].content"
                  contentType="html"
                  class="bg-white border rounded"
                  toolbar="full"
                />

              <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Add Lesson
              </button>
            </form>

            <!-- Existing lessons list -->
            <ul class="mt-4">
              <li
                v-for="lesson in lessonsByTopic[topic.id] || []"
                :key="lesson.id"
                class="border rounded p-4 mb-2 bg-gray-50"
              >
                <p class="text-sm text-gray-600">Difficulty: {{ difficultyLabel(lesson.difficulty) }}</p>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </main> 
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
const lessonsByTopic = ref({})
const newLesson = ref({})
const lessonToggles = ref({})
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
const lessonTab = ref('vue')
const options = ref(['', '', '', ''])
const newQuiz = ref({
  title: '',
  description: '',
  course_id: '',
  topic_id: '',
  type: '',
  is_published: false
})
const filteredTopics = computed(() => {
  return allTopics.value.filter(topic => {
    if (lessonTab.value === 'vue') return topic.course_id === 2
    if (lessonTab.value === 'laravel') return topic.course_id === 1
    return true
  })
})
const difficultyLabel = (val) => {
  if (val === 1) return 'Beginner'
  if (val === 2) return 'Intermediate'
  if (val === 3) return 'Advanced'
  return 'Unknown'
}
const toggleLesson = (topicId) => {
  lessonToggles.value[topicId] = !lessonToggles.value[topicId]
  if (lessonToggles.value[topicId]) fetchLessons(topicId)
}

const fetchLessons = async (topicId) => {
  const res = await axios.get(`/api/lessons?topic_id=${topicId}`)
  lessonsByTopic.value[topicId] = res.data
  if (!newLesson.value[topicId]) {
    newLesson.value[topicId] = { difficulty: '', content: '' }
  }
}

const createLesson = async (topicId) => {
  const data = {
    topic_id: topicId,
    difficulty: newLesson.value[topicId].difficulty,
    content: newLesson.value[topicId].content,
  }

  try {
    await axios.post('/api/lessons', data)
    newLesson.value[topicId] = { difficulty: '', content: '' }
    await fetchLessons(topicId)
  } catch (e) {
    alert('Failed to create lesson')
    console.error(e)
  }
}
const reportTypes = [
  'course-completion',
  'framework-scorecard',
  'gamification',
  'assessment',
  'framework-comparison'
]
const reportResults = ref(null)
const fieldsVisible = ref(false);
const newBadge = ref({ title: '', description: '', image: '' })
const badges = ref([])
const fetchReport = async (type) => {
  try {
    const res = await axios.get(`/api/reports/${type}`)
    reportResults.value = res.data
  } catch (err) {
    reportResults.value = { error: 'Failed to load report.' }
  }
}

const downloadPdf = async (type) => {
  try {
    window.open(`/api/reports/export-pdf?type=${type}`, '_blank')
  } catch (err) {
    console.error('Failed to download PDF', err)
  }
}
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

const displayFields = async () => {
  const selected = newQuiz.value.questionType;

  if (selected === 'Choices') {
    fieldsVisible.value = true;
  } else {
    fieldsVisible.value = false;
  }
  
}

const fetchQuizzes = async () => {
  const res = await axios.get('/api/quizzes')
  quizzes.value = res.data
}

const fetchAllTopics = async () => {
  const res = await axios.get('/api/topics')
  allTopics.value = res.data
}

const uploadImage = async (file) => {
  const formData = new FormData();
  formData.append('image', file);

  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const response = await fetch('/upload-image', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    body: formData
  });

  if (!response.ok) {
    throw new Error('Upload failed');
  }

  const data = await response.json();
  return data.url;
};
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
  try {
    newQuiz.value.quizStatus = 'ACTIVE'
    const res = await axios.post('/api/quizzes', newQuiz.value)
    const quiz = res.data
    quizzes.value.push(quiz)

    if (newQuiz.value.questionType === 'Choices') {
      // Post options to the server
      const optionPayload = {
        question_id: quiz.id,
        options: options.value.map(text => ({ option_text: text }))
      }
      await axios.post('/api/options/storeOptions', optionPayload)
    }

    // Reset form
    newQuiz.value = {
      title: '',
      description: '',
      course_id: '',
      topic_id: '',
      type: '',
      is_published: false,
      difficulty: '',
      questionCategory: '',
      quizStatus: ''
    }
    options.value = ['', '', '', '']
  } catch (err) {
    console.error(err)
  }
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
const fetchBadges = async () => {
  const res = await axios.get('/api/badges')
  badges.value = res.data
}

const createBadge = async () => {
  await axios.post('/api/badges', newBadge.value)
  await fetchBadges()
  newBadge.value = { title: '', description: '', image: '' }
}

const logout = async () => {
  await axios.post('/logout')
  window.location.href = '/'
}
onMounted(() => {
  fetchBadges();
  fetchCourses();
  fetchAllTopics();
})
</script>
