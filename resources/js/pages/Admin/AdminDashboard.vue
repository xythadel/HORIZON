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
          <!-- <button @click="showSection = 'lessons'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Lessons
          </button> -->
          <button @click="showSection = 'quizzes'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Manage Quizzes
          </button>
          <button @click="showSection = 'badges'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Badge
          </button>
          <button @click="showSection = 'reports'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Reports
          </button>
          <button @click="showSection = 'sandpit'" class="w-full text-left bg-gray-700 p-2 rounded hover:bg-gray-600">
            Sandpit
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
              
              <th class="p-2 border">Name</th>
              <th class="p-2 border">Email</th>
              
              <th class="p-2 border">Created At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
              
              <td class="p-2 border">{{ user.name }}</td>
              <td class="p-2 border">{{ user.email }}</td>
              
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
                <label class="font-medium text-sm mb-1">Module Name <span class="text-red-500">*</span></label>
                <input v-model="newTopics[course.id].title" placeholder="Enter topic title" class="border p-2 rounded-md" required />
              </div>
              <div class="flex flex-col">
                <label class="font-medium text-sm mb-1">Topic Name <span class="text-red-500">*</span></label>
                <input v-model="newTopics[course.id].module_name" placeholder="Enter module name" class="border p-2 rounded-md" required />
              </div>
              <label class="block font-medium">Difficulty</label>
              <select v-model="newTopics[course.id].difficulty" class="border rounded p-2 w-full" required>
                <option disabled value="">-- Select Difficulty --</option>
                <option value="1">Beginner</option>
                <option value="2">Intermediate</option>
                <option value="3">Advanced</option>
              </select>

              <label class="block font-medium">Content</label>
                <QuillEditor  
                  v-model:content="newTopics[course.id].content"       
                  contentType="html"
                  class="bg-white border rounded"
                  toolbar="full"
                />
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
                  <label class="block font-medium">Difficulty</label>
                  <select v-model="topic.difficulty" class="border rounded p-2 w-full" required>
                    <option disabled value="">-- Select Difficulty --</option>
                    <option value="1">Beginner</option>
                    <option value="2">Intermediate</option>
                    <option value="3">Advanced</option>
                  </select>

                  <label class="block font-medium">Content</label>
                    <QuillEditor  
                      v-model:content="topic.content"       
                      contentType="html"
                      class="bg-white border rounded"
                      toolbar="full"
                    />
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
        <div class="flex gap-4 mb-4">
          <button
            :class="quizTab === 'pretest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
            class="px-4 py-2 rounded"
            @click="quizTab = 'pretest'"
          >
            Pre-test
          </button>
          <button
            :class="quizTab === 'posttest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
            class="px-4 py-2 rounded"
            @click="quizTab = 'posttest'"
          >
            Post-test
          </button>
        </div>
        <form @submit.prevent="createQuiz" class="mb-6 space-y-4">
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Topic</label>
            <select v-model="newQuiz.topic_id" class="border p-2 rounded" required>
              <option disabled value="">-- Select Topic --</option>
              <option v-for="topic in allTopics" :key="topic.id" :value="topic.id">{{ topic.title }}</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Quiz Type</label>
            <select name="type" id="" class="border p-2 rounded" v-model="newQuiz.questionType" @change="displayFields">
              <option value="">Select Type</option>
              <option value="Blank">Fill in the Blanks</option>
              <option value="Choices">Multiple Choice</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Title</label>
            <input v-model="newQuiz.title" class="border p-2 rounded" required />
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Question</label>
            <textarea v-model="newQuiz.description" class="border p-2 rounded"></textarea>
          </div>
          <div class="flex flex-col">
            <label class="font-medium text-sm mb-1">Answer</label>
            <textarea v-model="newQuiz.answer" class="border p-2 rounded"></textarea>
            <div v-if="fieldsVisible" class="flex flex-col gap-y-2">
            <label>Options</label>
            <div v-for="(opt, index) in options" :key="index" class="flex items-center border rounded-md">
              <div class="w-10 p-2 text-center bg-green-300">
                {{ String.fromCharCode(65 + index) }}.
              </div>
              <input v-model="options[index]" type="text" class="w-full rounded-r-md border-none" />
            </div>
          </div>
            <button type="submit" class="bg-gray-100 text-black px-4 py-2 rounded mt-4">+</button>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="newQuiz.is_published" />
            <label class="text-sm">Publish immediately</label>
          </div>
          

          
        </form>
        <div v-if="pendingQuizzes.length" class="mt-6">
          <h4 class="text-lg font-bold mb-2">Quiz List</h4>
          <ul class="mb-4">
            <li v-for="(quiz, index) in pendingQuizzes.filter(q => q.questionCategory === (quizTab === 'pretest' ? 'Pre-test' : 'Post-test'))"
              :key="index"
              class="bg-gray-100 border p-3 rounded mb-2"
            >
              <strong>{{ quiz.title }}</strong> — {{ quiz.questionCategory }}
            </li>
          </ul>
          <button @click="saveAllQuizzes" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save All Quizzes
          </button>
        </div>
        <ul>
          <li
            v-for="quiz in quizzes.filter(q => q.questionCategory === (quizTab === 'pretest' ? 'Pre-test' : 'Post-test'))"
            :key="quiz.id"
            class="border p-3 mb-2 rounded bg-gray-50 flex justify-between"
          >
            <div>
              <h4 class="font-semibold">{{ quiz.title }}</h4>
              <p class="text-sm text-gray-600">{{ quiz.description }}</p>
              <p class="text-xs text-gray-500">
                Type: {{ quiz.questionCategory }} |
                Topic ID: {{ quiz.topic_id }} |
                Published: {{ quiz.is_published }}
              </p>
            </div>
            <button @click="deleteQuiz(quiz.id)" class="text-red-600 hover:underline">Delete</button>
          </li>
        </ul>
      </div>
      
      <!--Sandpit-->
      <div v-if="showSection === 'Sandpit'" class = "p-4 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Sandpit</h2>
      <!-- Main Area -->
        <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Sandpit</h2>

        <!-- Language Select -->
        <div class="mb-4">
            <label class="mr-2 font-medium">Language:</label>
            <select v-model="selectedLanguage" class="border p-1 rounded">
            <option value="vue">Vue.js</option>
            <option value="laravel">Laravel Blade</option>
            </select>
        </div>

        <!-- Tabs -->
        <div class="mb-2 flex space-x-4 border-b">
            <button
            v-for="tab in visibleTabs"
            :key="tab"
            @click="activeTab = tab"
            :class="[
                'py-1 px-4 border-b-2',
                activeTab === tab ? 'border-indigo-500 font-semibold' : 'border-transparent',
            ]"
            >
            {{ tab }}
            </button>
        </div>

        <!-- Code Area -->
        <textarea
            v-model="codeSections[activeTab]"
            rows="12"
            class="w-full border rounded-md p-2 font-mono bg-slate-800 text-white"
            style="scrollbar-width: thin;"
        ></textarea>

        <!-- Run -->
        <div class="mt-3">
            <button @click="runCode" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Run
            </button>
        </div>

        <!-- Error -->
        <div v-if="error" class="mt-4 text-red-500 font-mono bg-red-100 p-2 rounded">
            {{ error }}
        </div>

        <!-- Result -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Result:</h3>
            <iframe :srcdoc="compiledHtml" class="w-full h-[400px] border rounded-md"></iframe>
        </div>
        </main>
    </div>

      <!-- Badges CRUD -->
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
              <div class="flex flex-row gap-3 items-center">
                <img :src="badge.image" class="w-12 h-12" />
                <div>
                  <h4 class="font-semibold">{{ badge.title }}</h4>
                  <p class="text-sm">{{ badge.description }}</p>
                </div>
              </div>
              <div v-if="editingBadgeId !== badge.id">
                <div class="text-sm text-blue-600 underline cursor-pointer mt-1" @click="startEditingBadge(badge)">Edit</div>
              </div>
              <div v-else class="space-y-2 mt-5">
                <input v-model="editBadge.title" class="border p-1 rounded w-full" />
                <textarea v-model="editBadge.description" class="border p-1 rounded w-full"></textarea>
                <input v-model="editBadge.image" class="border p-1 rounded w-full" />
                <div class="flex gap-2">
                  <button @click="updateBadge" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                  <button @click="cancelEditingBadge" class="text-gray-600 underline">Cancel</button>
                </div>
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
              <button @click="fetchReport(type)" class="bg-blue-600 text-white px-4 py-1 rounded">View</button>
              <button @click="downloadPdf(type)" class="bg-green-600 text-white px-4 py-1 rounded">Download PDF</button>
            </div>
          </div>

          <div v-if="reportResults" class="mt-6">
            <pre class="bg-gray-100 p-4 rounded max-h-[600px] overflow-auto text-sm">{{ JSON.stringify(reportResults, null, 2) }}</pre>
          </div>
        </div>
      </div>
      <div v-if="modalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-3xl p-6 rounded shadow-lg relative">
          <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
          <h3 class="text-xl font-bold mb-4">{{ selectedReport.title }}</h3>
          <div class="space-y-6 max-h-[600px] overflow-auto text-sm">
            <div v-if="Array.isArray(selectedReport.data)">
              <div v-for="(entry, index) in selectedReport.data" :key="index" class="space-y-4 border-b pb-4">
                <div v-if="entry.user">
                  <h4 class="font-semibold text-base">User: {{ entry.user }}</h4>
                  <table v-if="entry.progress" class="w-full border border-collapse">
                    <thead>
                      <tr class="bg-gray-200">
                        <th class="border p-2">Course</th>
                        <th class="border p-2">Topics Complete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(p, i) in entry.progress" :key="i">
                        <td class="border p-2">{{ p.course }}</td>
                        <td class="border p-2">{{ p.progress ?? 'N/A' }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table v-else-if="entry.topics" class="w-full border border-collapse">
                    <thead>
                      <tr class="bg-gray-200">
                        <th class="border p-2">Title</th>
                        <th class="border p-2">Pre Avg</th>
                        <th class="border p-2">Post Avg</th>
                        <th class="border p-2">Gain</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(topic, i) in entry.topics" :key="i">
                        <td class="border p-2">{{ topic.title }}</td>
                        <td class="border p-2">{{ topic.pre_avg }}</td>
                        <td class="border p-2">{{ topic.post_avg }}</td>
                        <td class="border p-2">{{ topic.gain }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-else-if="entry.framework">
                  <table class="w-full border border-collapse">
                    <thead>
                      <tr class="bg-gray-200">
                        <th v-for="(val, key) in entry" :key="key" class="border p-2">
                          {{ key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td v-for="(val, key) in entry" :key="key" class="border p-2">
                          {{ val }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-else-if="entry.name && entry.email && entry.badges_earned_count !== undefined">
                  <table class="w-full border border-collapse">
                    <thead>
                      <tr class="bg-gray-200">
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Badges Earned</th>
                        <th class="border p-2">Total Attempt</th>
                        <th class="border p-2">Average Score</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="border p-2">{{ entry.name }}</td>
                        <td class="border p-2">{{ entry.badges_earned_count }}</td>
                        <td class="border p-2">{{ entry.total_quizzes_taken }}</td>
                        <td class="border p-2">{{ entry.average_score }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-600">No data available for this report.</p>
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
            Laravel Lessons
          </button>
          <button
            :class="lessonTab === 'laravel' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
            class="px-4 py-2 rounded"
            @click="lessonTab = 'laravel'"
          >
            Vue Lessons
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
                Update Lesson
              </button>
            </form>

            <ul class="mt-4">
              <li v-for="lesson in lessonsByTopic[topic.id] || []" :key="lesson.id" class="border rounded p-4 mb-2 bg-gray-50 flex justify-between items-center">
                <p class="text-sm text-gray-600">Difficulty: {{ difficultyLabel(lesson.difficulty) }}</p>
                <div v-if="editingLessonId !== lesson.id">
                  <!-- <div v-html="lesson.content" class="prose text-sm"></div> -->
                  <button @click="startEditingLesson(lesson)" class="text-blue-600 text-sm mt-2">Edit</button>
                </div>
                <div v-else>
                  <label class="text-sm font-medium mb-1">Content</label>
                  <QuillEditor
                    v-model:content="editLesson.content"
                    contentType="html"
                    class="bg-white border rounded"
                    toolbar="full"
                  />
                  <div class="flex gap-2 mt-2">
                    <button @click="updateLesson(topic.id)" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                    <button @click="cancelEditingLesson" class="text-gray-600 underline">Cancel</button>
                  </div>
                </div>
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
const quizTab = ref('pretest')
const lessonsByTopic = ref({})
const newLesson = ref({})
const lessonToggles = ref({})
const users = ref([])
const courses = ref([])
const quizzes = ref([])
const allTopics = ref([])
const modalVisible = ref(false)
const selectedReport = ref({ title: '', data: {} })
const standaloneTopics = ref({})
const newTopics = ref({})
const expandedCourses = ref({})
const errorMessages = ref({})
const editingTopicId = ref(null)
const showSection = ref('topics')
const lessonTab = ref('vue')
const options = ref(['', '', '', ''])
const editingBadgeId = ref(null)
const editingLessonId = ref(null)
const editLesson = ref({})
const editBadge = ref({})
const pendingQuizzes = ref([]);
const newQuiz = ref({
  title: '',
  description: '',
  questionType: '',
  topic_id: '',
  is_published: false,
  answer: '',
})
const filteredTopics = computed(() => {
  return allTopics.value.filter(topic => {
    if (lessonTab.value === 'vue') return topic.course_id === 2
    if (lessonTab.value === 'laravel') return topic.course_id === 1
    return true
  })
})
const fetchReport = async (type) => {
  try {
    const res = await axios.get(`/api/reports/${type}`)
    selectedReport.value = {
      title: `${type.replace('-', ' ')} Report`,
      data: res.data
    }
    modalVisible.value = true
  } catch (err) {
    selectedReport.value = {
      title: 'Error',
      data: { message: 'Failed to load report.' }
    }
    modalVisible.value = true
  }
}
const startEditingBadge = (badge) => {
  editingBadgeId.value = badge.id
  editBadge.value = { ...badge }
}

const cancelEditingBadge = () => {
  editingBadgeId.value = null
  editBadge.value = {}
}
const startEditingLesson = (lesson) => {
  editingLessonId.value = lesson.id
  editLesson.value = { ...lesson }
}

const cancelEditingLesson = () => {
  editingLessonId.value = null
  editLesson.value = {}
}

const updateLesson = async (topicId) => {
  try {
    await axios.put(`/api/lessons/${editingLessonId.value}`, editLesson.value)
    await fetchLessons(topicId)
    cancelEditingLesson()
  } catch (e) {
    alert('Failed to update lesson')
    console.error(e)
  }
}
const updateBadge = async () => {
  try {
    await axios.put(`/api/badges/${editingBadgeId.value}`, editBadge.value)
    await fetchBadges()
    cancelEditingBadge()
  } catch (e) {
    alert('Failed to update badge')
    console.error(e)
  }
}
const closeModal = () => {
  modalVisible.value = false
  selectedReport.value = { title: '', data: {} }
}
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
// const fetchReport = async (type) => {
//   try {
//     const res = await axios.get(`/api/reports/${type}`)
//     reportResults.value = res.data
//   } catch (err) {
//     reportResults.value = { error: 'Failed to load report.' }
//   }
// }

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

const createQuiz = () => {
  const category = quizTab.value === 'pretest' ? 'Pre-test' : 'Post-test';
  pendingQuizzes.value.push({
    ...newQuiz.value,
    questionCategory: category,
    options: newQuiz.value.questionType === 'Choices' ? [...options.value] : [],
    quizStatus: 'ACTIVE'
  });

  newQuiz.value.title = '';
  newQuiz.value.description = '';
  newQuiz.value.answer = '';
  if (newQuiz.value.questionType === 'Choices') {
    options.value = ['', '', '', ''];
  }
};

const saveAllQuizzes = async () => {
  try {
    for (const quiz of pendingQuizzes.value) {
      const { options: quizOptions, ...quizData } = quiz;
      const res = await axios.post('/api/quizzes', quizData);
      const savedQuiz = res.data;

      if (quiz.questionType === 'Choices' && quizOptions?.length) {
        await axios.post('/api/options/storeOptions', {
          question_id: savedQuiz.id,
          options: quizOptions.map(text => ({ option_text: text }))
        });
      }

      quizzes.value.push(savedQuiz);
    }

    pendingQuizzes.value = []; 
    alert('All quizzes saved successfully!');
  } catch (err) {
    console.error(err);
    alert('Failed to save some quizzes.');
  }
};
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

/*sandpit script still editing
const selectedLanguage = ref('vue')
  const activeTab = ref('template')

    const tabs = {
    vue: ['template', 'script', 'style'],
    laravel: ['template', 'web.php', 'style']
    }

    const visibleTabs = computed(() => tabs[selectedLanguage.value])

    const codeSections = ref({
    template: '',
    script: '',
    style: '',
    'web.php': ''
    })

    const compiledHtml = ref('')
    const error = ref('')
    watch(selectedLanguage, (lang) => {
    if (lang === 'laravel') {
        activeTab.value = 'template'
        codeSections.value.template = `<div>
    <h1>Hello, {{ name }}</h1>

    @if(isAdmin)
        <p>Welcome back, administrator!</p>
    @else
        <p>You are logged in as a regular user.</p>
    @endif

    <ul>
        @foreach($tasks as $task)
        <li>{{ task }}</li>
        @endforeach
    </ul>
    </div>`
        codeSections.value['web.php'] = `$name = "Jane";
    $isAdmin = true;
    $tasks = ["Write docs", "Fix bug", "Deploy"];`
        codeSections.value.style = `body { font-family: sans-serif; padding: 20px; }`
    } else {
        activeTab.value = 'template'
        codeSections.value.template = `<header>\n  <h1>WELCOME TO SANDPIT</h1>\n</header>`
        codeSections.value.script = `// JS logic here`
        codeSections.value.style = `header { text-align: center; }`
    }
    })

    function parseWebPhp(phpCode) {
    const lines = phpCode.split('\n')
    const data = {}
    for (let line of lines) {
        line = line.trim()
        if (line.startsWith('$')) {
        let [key, value] = line.split('=')
        key = key.replace('$', '').trim()
        value = value.trim().replace(/;$/, '')

        try {

            if (value.startsWith('"') || value.startsWith("'")) {
            data[key] = value.replace(/^["']|["']$/g, '')
            } else if (value.startsWith('[')) {
            data[key] = eval(value)
            } else {
            data[key] = eval(value)
            }
        } catch (e) {
            console.warn('Failed parsing:', line)
        }
        }
    }
    return data
    }
    function runCode() {
    error.value = ''

    try {
        if (selectedLanguage.value === 'vue') {
            compiledHtml.value = `
                <html>
                <head>
                <style>${codeSections.value.style}</style>
                </head>
                <body>
                <div id="app">
                    ${codeSections.value.template}
                </div>
                <script>
                    ${codeSections.value.script}
                <\/script>
                </body>
                </html>
            `
            } else if (selectedLanguage.value === 'laravel') {
            const data = parseWebPhp(codeSections.value['web.php'])
            let rendered = codeSections.value.template
            rendered = rendered.replace(/\{\{\s*(\w+)\s*\}\}/g, (_, key) => data[key] ?? '')

            rendered = rendered.replace(/@if\s*\((.*?)\)([\s\S]*?)@else([\s\S]*?)@endif/g, (_, condition, ifTrue, elseBlock) => {
                if (condition.includes('=') && !condition.includes('==') && !condition.includes('>=') && !condition.includes('<=') && !condition.includes('!=')) {
                throw new Error('Use "==", ">=", "<=" etc. for comparison in @if. Avoid single "=" (assignment).')
                }

                const expr = condition.replace(/(\w+)/g, 'data.$1')
                return eval(expr) ? ifTrue : elseBlock
            })


            rendered = rendered.replace(/@foreach\s*\(\s*\$(\w+)\s+as\s+\$(\w+)\s*\)([\s\S]*?)@endforeach/g, (_, list, item, content) => {
                const arr = data[list]
                if (!Array.isArray(arr)) return ''
                return arr.map(val => content.replace(new RegExp(`\\{\\{\\s*${item}\\s*\\}\\}`, 'g'), val)).join('')
            })


            compiledHtml.value = `
                <html>
                <head>
                <style>${codeSections.value.style}</style>
                </head>
                <body>
                ${rendered}
                </body>
                </html>
            `
            }
        } catch (err) {
            error.value = 'Error: ' + err.message
        }
      }*/

</script>
