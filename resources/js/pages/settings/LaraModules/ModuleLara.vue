<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <nav class="space-y-2 mt-6">
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

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">
      <div v-if="currentTopic">
        <!-- Topic Title -->
        <h1 class="text-2xl font-bold mb-1 text-white">{{ currentTopic.title }}</h1>

        <!-- Module Name -->
        <p class="text-sm text-gray-300 mb-6">{{ currentTopic.module_name }}</p>

        <!-- Scrollable Content -->
        <div
          v-html="formattedContent"
          class="topic-content text-white leading-relaxed max-h-64 overflow-y-auto pr-2"
        ></div>

        <!-- Code Snippet -->
        <div v-if="currentTopic.code" class="bg-gray-900 text-green-300 p-4 rounded my-4 font-mono">
          <pre>{{ currentTopic.code }}</pre>
        </div>

        <!-- Quiz Button -->
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
    formattedContent() {
      if (!this.currentTopic?.content) return '';

      const lines = this.currentTopic.content
        .split('\n')
        .map(line => line.trim())
        .filter(line => line !== '');
      let html = '';
      let inList = false;

      lines.forEach((line) => {
        if (line.startsWith('•')) {
          if (!inList) {
            html += '<ul>';
            inList = true;
          }
          html += `<li>${line.slice(1).trim()}</li>`;
        } else {
          if (inList) {
            html += '</ul>';
            inList = false;
          }

          if (line.endsWith(':')) {
            html += `<p class="mt-4 mb-2 font-semibold">${line}</p>`;
          } else {
            html += `<p>${line}</p>`;
          }
        }
      });

      if (inList) {
        html += '</ul>';
      }

      return html;
    }
  },
  mounted() {
    this.fetchTopics();
  },
  methods: {
    async fetchTopics() {
      try {
        const response = await fetch('/api/courses/2/laravel-topics');
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
.topic-content {
  color: white;
  font-size: 1.1rem;
  scrollbar-width: thin;
  scrollbar-color: #888 transparent;
}

.topic-content::-webkit-scrollbar {
  width: 8px;
}
.topic-content::-webkit-scrollbar-thumb {
  background-color: #888;
  border-radius: 4px;
}
.topic-content::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.topic-content p {
  margin-bottom: 1rem;
}

.topic-content ul {
  list-style: disc;
  padding-left: 1.5rem;
  margin: 1rem 0;
}

.topic-content li {
  margin-bottom: 0.5rem;
}
</style>
