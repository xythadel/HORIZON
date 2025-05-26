<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
      <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
      <p v-if="user && user.name" class="pl-24 pt-16 text-base font-normal text-zinc-800">
    {{ user.name }}
  </p>


      <!--Navigation Panel-->
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="/dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
          </svg>
          Dashboard
        </a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a> 
        <a href="/settings" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Settings</a>
      </nav>

      <!-- Logout -->
      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a
          href="#"
          @click.prevent="showLogoutModal = true"
          class="text-base font-normal text-zinc-800 hover:text-indigo-600"
        >
          Logout
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-8 text-white">
      <h2 class="text-2xl font-bold">My Learning</h2>
      <div v-if="userProgress.length" class="mt-6 space-y-4">
        <div
          v-for="(course, index) in userProgress"
          :key="index"
          v-if="course.progress > 0"
          class="bg-gray-900 p-4 rounded-lg"
        >
          <h3 class="text-lg font-semibold">{{ course.name }}</h3>
          <div class="mt-2 w-full bg-gray-700 rounded-full h-2">
            <div class="bg-green-500 h-2 rounded-full" :style="{ width: course.progress + '%' }"></div>
          </div>
          <p class="mt-2 text-sm">{{ course.progress }}% completed</p>
          <button
            @click="resumeCourse(course.courseId, course.lastTopicId)"
            class="mt-2 px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm"
          >
            Resume
          </button>
        </div>
      </div>
      <p v-else class="mt-6 text-gray-400">You haven't started any courses yet.</p>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div
    v-if="showLogoutModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center text-gray-800">
      <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
      <p class="mb-6">Do you really want to logout from your account?</p>
      <div class="flex justify-center space-x-4">
        <button
          @click="confirmLogout"
          class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
        >
          Yes, Logout
        </button>
        <button
          @click="showLogoutModal = false"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export default {
  props: {
    user: Object,
  },
  data() {
    return {
      userProgress: [],
      showLogoutModal: false,
    };
  },
  mounted() {
    this.fetchProgress();
  },
  methods: {
    fetchProgress() {
      axios.get('/mylearning/progress')
        .then(response => {
          this.userProgress = response.data.map(entry => {
            const course = entry.course;
            const lastTopicId = entry.last_topic_id;
            const courseId = course.id;
            const totalTopics = course.topics.length;

            let currentIndex = course.topics.findIndex(topic => topic.id === lastTopicId);
            currentIndex = currentIndex === -1 ? 0 : currentIndex + 1;

            const progress = totalTopics > 0
              ? Math.round((currentIndex / totalTopics) * 100)
              : 0;

            return {
              name: course.name,
              progress: progress,
              courseId: courseId,
              lastTopicId: lastTopicId,
            };
          });
        })
        .catch(error => {
          console.error('Failed to load progress:', error);
        });
    },
    confirmLogout() {
      router.post('/logout');
    },
    resumeCourse(courseId, topicId) {
      router.visit(`/courses/${courseId}/topics/${topicId}`);
    }
  }
};
</script>
