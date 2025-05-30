<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <nav class="space-y-2">
        <!-- Navigation Links(Topics) -->
        <div v-for="(topic, index) in topics" :key="index">
          <button
            class="block w-full text-left px-6 py-3 text-base text-black font-normal hover:bg-gray-100 transition duration-200"
            :class="{ 'font-bold': currentTopicIndex === index }"
            :disabled="!topic.unlocked"
            @click="goToTopic(index)"
          >
            {{ topic.title }}
          </button>
        </div>
      </nav>
      <a href="test" class="absolute bottom-10 left-10 text-base font-normal text-zinc-800 hover:text-indigo-600">
        <button class="mt-6 text-sm text-gray-600 hover:underline">&larr; Back</button>
      </a>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-10">
      <div v-if="currentTopic">
        <h1 class="text-2xl font-bold mb-4">{{ currentTopic.title }}</h1>
        <div v-html="currentTopic.content" class="prose prose-invert max-w-none"></div>

        <!-- Code snippet -->
        <div v-if="currentTopic.code" class="bg-gray-900 text-green-300 p-4 rounded my-4 font-mono">
          <pre>{{ currentTopic.code }}</pre>
        </div>

        <!-- Mark topic as complete -->
        <button 
          v-if="!currentTopic.completed"
          class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
          @click="completeTopic"
        >
          Take Quiz
        </button>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentTopicIndex: 0,
      topics: [],
    };
  },
  computed: {
    currentTopic() {
      return this.topics[this.currentTopicIndex];
    },
  },
  mounted() {
    this.fetchTopics();
  },
  methods: {
    async fetchTopics() {
      try {
        const response = await fetch('/api/courses/2/laravel-topics'); // ✅ corrected
        const data = await response.json();

        if (data.length > 0) {
          data[0].unlocked = true;
        }

        this.topics = data.map(topic => ({
          ...topic,
          unlocked: topic.unlocked || false,
          completed: false,
        }));
      } catch (error) {
        console.error('Failed to load topics:', error);
      }
    },
    goToTopic(index) {
      if (this.topics[index].unlocked) {
        this.currentTopicIndex = index;
      }
    },
    completeTopic() {
      const current = this.topics[this.currentTopicIndex];
      current.completed = true;
      const nextIndex = this.currentTopicIndex + 1;
      if (this.topics[nextIndex]) {
        this.topics[nextIndex].unlocked = true;
      }
    },
  },
};
</script>


<style scoped>
.prose p {
  margin-bottom: 1rem;
}
</style>
