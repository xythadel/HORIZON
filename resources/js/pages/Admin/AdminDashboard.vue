<template>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="flex min-h-screen flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="flex w-full flex-col justify-between bg-[#EDEDED] p-4 text-black md:w-64">
            <div>
                <h2 class="mb-4 text-xl font-semibold">{{ titleHeader }}</h2>
                <nav class="flex flex-col gap-2">
                    <button @click="showSection = 'dashboard'" class="w-full rounded bg-white p-2 text-left hover:bg-[#EDEDED]">Dashboard</button>

                    <button @click="showSection = 'course'" class="w-full rounded bg-white p-2 text-left hover:bg-[#EDEDED]">Courses</button>
                    <button @click="showSection = 'reports'" class="w-full rounded bg-white p-2 text-left hover:bg-[#EDEDED]">Reports</button>
                    <button @click="showSection = 'users'" class="w-full rounded bg-white p-2 text-left hover:bg-[#EDEDED]">
                        User & Admin Management
                    </button>
                </nav>
            </div>
            <button
                @click="logout"
                class="mt-4 w-full rounded bg-red-500 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-red-600"
            >
                Logout
            </button>
        </aside>

        <main class="flex-1 p-4 md:p-8">
            <!-- User Table -->
            <div v-if="showSection === 'course'" class="h-[95%] w-full overflow-auto rounded">
                <header class="text-2xl font-semibold">Courses</header>
                <div class="!w-50 mt-20 flex flex-row items-center justify-center gap-8 p-10">
                    <a class="h-[20%] w-2/5 rounded-3xl" href="/Admin/VuePanel">
                        <img src="/Photos/vue.png" class="w-full" alt="" />
                    </a>
                    <a class="h-[20%] w-2/5 rounded-3xl" href="/Admin/LaravelPanel">
                        <img src="/Photos/laravel.png" class="w-full" alt="" />
                    </a>
                </div>
            </div>
            <div v-if="showSection === 'users'" class="mb-6 overflow-auto rounded bg-white p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h1 class="text-2xl font-semibold">Accounts</h1>
                    <div class="flex gap-2">
                        <button
                            class="rounded-md px-4 py-2"
                            :class="activeTab === 'users' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
                            @click="activeTab = 'users'"
                        >
                            User Accounts
                        </button>
                        <button
                            class="rounded-md px-4 py-2"
                            :class="activeTab === 'admins' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
                            @click="activeTab = 'admins'"
                        >
                            Admin Accounts
                        </button>
                    </div>
                </div>

                <!-- User Accounts -->
                <div v-if="activeTab === 'users'">
                    <h2 class="mb-3 text-xl font-bold">Registered Users</h2>
                    <table class="w-full border border-gray-300 text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">ID</th>
                                <th class="border p-2">Name</th>
                                <th class="border p-2">Email</th>
                                <th class="border p-2">Role</th>
                                <th class="border p-2">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                                <td class="border p-2">{{ user.id }}</td>
                                <td class="border p-2">{{ user.firstname }} {{ user.lastname }}</td>
                                <td class="border p-2">{{ user.email }}</td>
                                <td class="border p-2">{{ user.role }}</td>
                                <td class="border p-2">{{ new Date(user.created_at).toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Admin Accounts -->
                <div v-else-if="activeTab === 'admins'">
                    <h2 class="mb-3 text-xl font-bold">Registered Administrators</h2>
                    <table class="w-full border border-gray-300 text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border p-2">Name</th>
                                <th class="border p-2">Email</th>
                                <th class="border p-2">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in admin" :key="user.id" class="hover:bg-gray-50">
                                <td class="border p-2">{{ user.role }} {{ user.lastname }}</td>
                                <td class="border p-2">{{ user.email }}</td>
                                <td class="border p-2">{{ new Date(user.created_at).toLocaleString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showSection === 'reports'" class="rounded bg-white p-4 shadow">
                <h2 class="mb-4 text-2xl font-bold">Reports</h2>
                <div class="space-y-4">
                    <div v-for="type in reportTypes" :key="type" class="flex items-center justify-between rounded border p-4">
                        <span class="capitalize">{{ type.replace('-', ' ') }} Report</span>
                        <div class="space-x-2">
                            <button @click="fetchReport(type)" class="rounded bg-blue-600 px-4 py-1 text-white">View</button>
                            <button @click="downloadPdf(type)" class="rounded bg-green-600 px-4 py-1 text-white">Download PDF</button>
                        </div>
                    </div>

                    <div v-if="reportResults" class="mt-6">
                        <pre class="max-h-[600px] overflow-auto rounded bg-gray-100 p-4 text-sm">{{ JSON.stringify(reportResults, null, 2) }}</pre>
                    </div>
                </div>
            </div>
            <div v-if="showSection === 'dashboard'" class="rounded bg-white p-4 shadow">
              <header class="text-2xl font-semibold">Dashboard</header>
              <div class="w-full rounded bg-[#1F8AE8] p-4 text-white mb-6">
                  <header class="text-3xl font-semibold">Welcome, Admin!</header>
                  <span class="mt-4 block !w-1/4">
                      Welcome back! Manage your learners and keep Vue & Laravel lessons on track.
                  </span>
              </div>

              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div class="rounded-lg bg-blue-500 text-white p-6 shadow">
                      <h3 class="text-lg font-semibold">Total Users</h3>
                      <p class="mt-2 text-3xl font-bold">{{ counts.total_users }}</p>
                  </div>
                  <div class="rounded-lg bg-green-500 text-white p-6 shadow">
                      <h3 class="text-lg font-semibold">Vue Students</h3>
                      <p class="mt-2 text-3xl font-bold">{{ counts.vue_students }}</p>
                  </div>
                  <div class="rounded-lg bg-red-500 text-white p-6 shadow">
                      <h3 class="text-lg font-semibold">Laravel Students</h3>
                      <p class="mt-2 text-3xl font-bold">{{ counts.laravel_students }}</p>
                  </div>
              </div>
          </div>

            <div v-if="modalVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="relative w-full max-w-3xl rounded bg-white p-6 shadow-lg">
                    <button @click="closeModal" class="absolute right-2 top-2 text-xl text-gray-500 hover:text-black">&times;</button>
                    <h3 class="mb-4 text-xl font-bold">{{ selectedReport.title }}</h3>
                    <div class="max-h-[600px] space-y-6 overflow-auto text-sm">
                        <div v-if="Array.isArray(selectedReport.data)">
                            <div v-for="(entry, index) in selectedReport.data" :key="index" class="space-y-4 border-b pb-4">
                                <div v-if="entry.user">
                                    <h4 class="text-base font-semibold">User: {{ entry.user }}</h4>
                                    <table v-if="entry.progress" class="w-full border-collapse border">
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
                                    <table v-else-if="entry.topics" class="w-full border-collapse border">
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
                                    <table class="w-full border-collapse border">
                                        <thead>
                                            <tr class="bg-gray-200">
                                                <th v-for="(val, key) in entry" :key="key" class="border p-2">
                                                    {{ key.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase()) }}
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
                                    <table class="w-full border-collapse border">
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
            <div v-if="showSection === 'lessons'" class="rounded bg-white p-4 shadow">
                <h2 class="mb-4 text-2xl font-bold">Manage Lessons</h2>
                <div class="mb-4 flex gap-4">
                    <button
                        :class="lessonTab === 'vue' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="rounded px-4 py-2"
                        @click="lessonTab = 'vue'"
                    >
                        Laravel Lessons
                    </button>
                    <button
                        :class="lessonTab === 'laravel' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                        class="rounded px-4 py-2"
                        @click="lessonTab = 'laravel'"
                    >
                        Vue Lessons
                    </button>
                </div>

                <div v-for="topic in filteredTopics" :key="topic.id" class="mb-6 rounded border shadow">
                    <div class="flex items-center justify-between bg-gray-100 p-4">
                        <div>
                            <h3 class="text-xl font-semibold">{{ topic.title }}</h3>
                        </div>
                        <button @click="toggleLesson(topic.id)" class="font-medium text-blue-600">
                            {{ lessonToggles[topic.id] ? 'Hide' : 'Add/View Lessons' }}
                        </button>
                    </div>

                    <div v-if="lessonToggles[topic.id]" class="space-y-6 p-4">
                        <form @submit.prevent="createLesson(topic.id)" class="space-y-4">
                            <label class="block font-medium">Difficulty</label>
                            <select v-model="newLesson[topic.id].difficulty" class="w-full rounded border p-2" required>
                                <option disabled value="">-- Select Difficulty --</option>
                                <option value="1">Beginner</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Advanced</option>
                            </select>

                            <label class="block font-medium">Content</label>
                            <QuillEditor
                                v-model:content="newLesson[topic.id].content"
                                contentType="html"
                                class="rounded border bg-white"
                                toolbar="full"
                            />

                            <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">Update Lesson</button>
                        </form>

                        <ul class="mt-4">
                            <li
                                v-for="lesson in lessonsByTopic[topic.id] || []"
                                :key="lesson.id"
                                class="mb-2 flex items-center justify-between rounded border bg-gray-50 p-4"
                            >
                                <p class="text-sm text-gray-600">Difficulty: {{ difficultyLabel(lesson.difficulty) }}</p>
                                <div v-if="editingLessonId !== lesson.id">
                                    <!-- <div v-html="lesson.content" class="prose text-sm"></div> -->
                                    <button @click="startEditingLesson(lesson)" class="mt-2 text-sm text-blue-600">Edit</button>
                                </div>
                                <div v-else>
                                    <label class="mb-1 text-sm font-medium">Content</label>
                                    <QuillEditor
                                        v-model:content="editLesson.content"
                                        contentType="html"
                                        class="rounded border bg-white"
                                        toolbar="full"
                                    />
                                    <div class="mt-2 flex gap-2">
                                        <button @click="updateLesson(topic.id)" class="rounded bg-blue-600 px-3 py-1 text-white">Save</button>
                                        <button @click="cancelEditingLesson" class="text-gray-600 underline">Cancel</button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div v-if="showSection === 'sandpit'" class="rounded bg-white p-4 shadow">
                <Sandpit></Sandpit>
            </div>
            <div v-if="showSection === 'skilltests'" class="rounded bg-white p-4 shadow">
                <h2 class="mb-4 text-2xl font-bold">Skill Test Management</h2>
                <form @submit.prevent="createSkillTest" class="mb-6 space-y-4">
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Title</label>
                        <input v-model="newSkillTest.title" class="rounded border p-2" required />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Description</label>
                        <textarea v-model="newSkillTest.description" class="rounded border p-2"></textarea>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Language</label>
                        <select v-model="newSkillTest.language" class="rounded border p-2" required>
                            <option value="php">PHP</option>
                            <option value="javascript">JavaScript</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Topics</label>
                        <select v-model="newSkillTest.topic_id" class="rounded border p-2" required>
                            <option disabled value="">-- Select Topic --</option>
                            <option v-for="topic in allTopics" :key="topic.id" :value="topic.id">{{ topic.title }}</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Score</label>
                        <input v-model="newSkillTest.score" class="rounded border p-2" required />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Test Input (stdin)</label>
                        <input v-model="newSkillTest.test_input" class="rounded border p-2" />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Expected Output</label>
                        <input v-model="newSkillTest.expected_output" class="rounded border p-2" required />
                    </div>
                    <div class="flex flex-col">
                        <label class="mb-1 text-sm font-medium">Code Snippets</label>
                        <input type="file" @change="handleSnippetFile" class="rounded border p-2" required />
                    </div>
                    <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">Create Test</button>
                </form>
                <div v-if="skillTests.length">
                    <h3 class="mb-3 text-xl font-semibold">Existing Skill Tests</h3>
                    <ul>
                        <li v-for="test in skillTests" :key="test.id" class="mb-2 flex justify-between rounded border p-3">
                            <div>
                                <h4 class="font-semibold">
                                    {{ test.title }} <span class="text-xs text-gray-500">({{ test.language }})</span>
                                </h4>
                                <p class="text-sm text-gray-600">{{ test.description }}</p>
                                <p class="text-xs text-gray-400">Input: {{ test.test_input }} | Output: {{ test.expected_output }}</p>
                            </div>
                            <button @click="deleteSkillTest(test.id)" class="text-red-600 hover:underline">Delete</button>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import Sandpit from '../sandpitComponent.vue';

const leaderboard = ref([]);
const quizTab = ref('pretest');
const lessonsByTopic = ref({});
const newLesson = ref({});
const lessonToggles = ref({});
const users = ref([]);
const admin = ref([]);
const courses = ref([]);
const quizzes = ref([]);
const allTopics = ref([]);
const modalVisible = ref(false);
const selectedReport = ref({ title: '', data: {} });
const standaloneTopics = ref({});
const newTopics = ref({});
const expandedCourses = ref({});
const showSection = ref('dashboard');
const lessonTab = ref('vue');
const options = ref(['', '', '', '']);
const editingBadgeId = ref(null);
const editingLessonId = ref(null);
const editLesson = ref({});
const editBadge = ref({});
const activeTab = ref('users')
const titleHeader = '<HORIZON/>';

const filteredTopics = computed(() => {
    return allTopics.value.filter((topic) => {
        if (lessonTab.value === 'vue') return topic.course_id === 2;
        if (lessonTab.value === 'laravel') return topic.course_id === 1;
        return true;
    });
});
const fetchReport = async (type) => {
    try {
        const res = await axios.get(`/api/reports/${type}`);
        selectedReport.value = {
            title: `${type.replace('-', ' ')} Report`,
            data: res.data,
        };
        modalVisible.value = true;
    } catch (err) {
        selectedReport.value = {
            title: 'Error',
            data: { message: 'Failed to load report.' },
        };
        modalVisible.value = true;
    }
};
const counts = ref({
    total_users: 0,
    vue_students: 0,
    laravel_students: 0,
});

const fetchCounts = async () => {
    try {
        const res = await axios.get('/api/leaderboard-counts');
        counts.value = res.data;
    } catch (err) {
        console.error('Failed to fetch counts', err);
    }
};
const skillTests = ref([]);
const newSkillTest = ref({
    title: '',
    description: '',
    language: 'php',
    test_input: '',
    expected_output: '',
    score: '',
    topic_id: '',
    codesnippet: '',
});

const fetchSkillTests = async () => {
    try {
        const res = await axios.get('/api/skill-tests');
        skillTests.value = res.data;
    } catch (err) {
        console.error(err);
    }
};

const handleSnippetFile = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
        newSkillTest.value.codesnippet = reader.result.split(',')[1];
    };
    reader.readAsDataURL(file);
};

const createSkillTest = async () => {
    try {
        const res = await axios.post('/api/skill-tests', newSkillTest.value, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        });

        skillTests.value.push(res.data.data);
        newSkillTest.value = {
            title: '',
            description: '',
            language: 'php',
            test_input: '',
            expected_output: '',
            score: '',
            topic_id: '',
            codesnippet: '',
        };
    } catch (err) {
        console.error(err.response?.data || err);
    }
};

const deleteSkillTest = async (id) => {
    if (!confirm('Are you sure?')) return;
    try {
        await axios.delete(`/api/skill-tests/${id}`);
        skillTests.value = skillTests.value.filter((t) => t.id !== id);
    } catch (err) {
        console.error(err);
    }
};
const startEditingBadge = (badge) => {
    editingBadgeId.value = badge.id;
    editBadge.value = { ...badge };
};

const cancelEditingBadge = () => {
    editingBadgeId.value = null;
    editBadge.value = {};
};
const startEditingLesson = (lesson) => {
    editingLessonId.value = lesson.id;
    editLesson.value = { ...lesson };
};

const cancelEditingLesson = () => {
    editingLessonId.value = null;
    editLesson.value = {};
};

const updateLesson = async (topicId) => {
    try {
        await axios.put(`/api/lessons/${editingLessonId.value}`, editLesson.value);
        await fetchLessons(topicId);
        cancelEditingLesson();
    } catch (e) {
        alert('Failed to update lesson');
        console.error(e);
    }
};
const updateBadge = async () => {
    try {
        await axios.put(`/api/badges/${editingBadgeId.value}`, editBadge.value);
        await fetchBadges();
        cancelEditingBadge();
    } catch (e) {
        alert('Failed to update badge');
        console.error(e);
    }
};
const closeModal = () => {
    modalVisible.value = false;
    selectedReport.value = { title: '', data: {} };
};
const difficultyLabel = (val) => {
    if (val === 1) return 'Beginner';
    if (val === 2) return 'Intermediate';
    if (val === 3) return 'Advanced';
    return 'Unknown';
};
const toggleLesson = (topicId) => {
    lessonToggles.value[topicId] = !lessonToggles.value[topicId];
    if (lessonToggles.value[topicId]) fetchLessons(topicId);
};

const fetchLessons = async (topicId) => {
    const res = await axios.get(`/api/lessons?topic_id=${topicId}`);
    lessonsByTopic.value[topicId] = res.data;
    if (!newLesson.value[topicId]) {
        newLesson.value[topicId] = { difficulty: '', content: '' };
    }
};

const createLesson = async (topicId) => {
    const data = {
        topic_id: topicId,
        difficulty: newLesson.value[topicId].difficulty,
        content: newLesson.value[topicId].content,
    };

    try {
        await axios.post('/api/lessons', data);
        newLesson.value[topicId] = { difficulty: '', content: '' };
        await fetchLessons(topicId);
    } catch (e) {
        alert('Failed to create lesson');
        console.error(e);
    }
};
const reportTypes = ['course-completion', 'framework-scorecard', 'gamification', 'assessment', 'framework-comparison'];
const reportResults = ref(null);
const fieldsVisible = ref(false);
const newBadge = ref({ title: '', description: '', image: '', topic_id: '' });
const badges = ref([]);

const downloadPdf = async (type) => {
    try {
        window.open(`/api/reports/export-pdf?type=${type}`, '_blank');
    } catch (err) {
        console.error('Failed to download PDF', err);
    }
};
const fetchCourses = async () => {
    const res = await axios.get('/api/courses');
    courses.value = res.data;
    for (const course of courses.value) {
        newTopics.value[course.id] = { title: '', content: '', module_name: '', loading: false };
        expandedCourses.value[course.id] = false;
        const topicUrl = course.name === 'Laravel Frameworks' ? `/api/courses/${course.id}/laravel-topics` : `/api/courses/${course.id}/topics`;
        const topicRes = await axios.get(topicUrl);
        standaloneTopics.value[course.id] = topicRes.data;
    }
};

const fetchUsers = async () => {
    const res = await axios.get('/api/users');
    users.value = res.data;
};
const fetchAdmin = async () => {
    const res = await axios.get('/api/admin');
    admin.value = res.data;
};

const fetchQuizzes = async () => {
    const res = await axios.get('/api/quizzes');
    quizzes.value = res.data;
};

const fetchAllTopics = async () => {
    const res = await axios.get('/api/topics');
    allTopics.value = res.data;
};
watch(showSection, async (val) => {
    if (val === 'users') {
        await fetchUsers();
        await fetchAdmin();
    }
    if (val === 'quizzes') {
        await fetchQuizzes();
        await fetchAllTopics();
    }
});

const toggleCourse = (courseId) => {
    expandedCourses.value[courseId] = !expandedCourses.value[courseId];
};
const fetchBadges = async () => {
    const res = await axios.get('/api/badges');
    badges.value = res.data;
};

const createBadge = async () => {
    await axios.post('/api/badges', newBadge.value);
    await fetchBadges();
    newBadge.value = { title: '', description: '', image: '', topic_id: '' };
};

const logout = async () => {
    await axios.post('/logout');
    window.location.href = '/';
};

onMounted(async () => {
    const lbRes = await fetch('/api/leaderboard');
    leaderboard.value = await lbRes.json();
    fetchBadges();
    fetchCourses();
    fetchAllTopics();
    fetchSkillTests();
    fetchCounts();
});
</script>
