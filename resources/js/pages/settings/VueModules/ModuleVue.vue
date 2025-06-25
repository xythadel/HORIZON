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
          <span v-if="topics[index].unlocked && index < topics.length && topics[index + 1]?.unlocked" class="text-green-600">✓</span>
        </div>
      </nav>
      <a href="test" class="absolute bottom-10 left-10 text-base font-normal text-zinc-800 hover:text-indigo-600">
        <button class="mt-6 text-sm text-gray-600 hover:underline">&larr; Back</button>
      </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">
      <div v-if="currentTopic">
        <!-- Pre Test -->
        <div v-if="!preTestCompleted">
          <h1 class="text-4xl text-white">This is Pre Test</h1>
          <h2 class="text-xl font-semibold text-white mb-4">Pre Quiz for: {{ currentTopic.title }}</h2>

          <ul class="mb-4 space-y-4">
            <li v-for="(question, index) in PreTest" :key="index" class="bg-white text-black p-4 rounded shadow">
              <p class="font-medium mb-2">{{ index + 1 }}. {{ question.description }}</p>

              <div v-if="question.questionType === 'Choices'" v-for="(choice, i) in question.choices" :key="i" class="ml-4">
                <label class="block">
                  <input
                    type="radio"
                    :name="'pre-q' + index"
                    :value="choice"
                    v-model="question.userAnswer"
                    @change="checkAnswer(question)"
                  />
                  {{ choice }}
                </label>
              </div>

              <div v-else-if="question.questionType === 'Blank'" class="ml-4">
                <input
                  type="text"
                  v-model="question.userAnswer"
                  class="border p-1 rounded w-1/2"
                  placeholder="Type your answer..."
                  @input="checkAnswer(question)"
                />
              </div>

              <div v-if="question.userAnswer">
                <span v-if="question.isCorrect" class="text-green-600 font-semibold">Correct!</span>
                <span v-else class="text-red-600 font-semibold">Incorrect</span>
              </div>
            </li>
          </ul>

          <button @click="submitQuiz('pre')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Submit Pre-Test
          </button>
        </div>

        <!-- Topic Content -->
        <div v-if="ModuleTopic">
          <h1 class="text-2xl font-bold mb-1 text-white">{{ currentTopic.title }}</h1>
          <p class="text-sm text-gray-300 mb-6">{{ currentTopic.module_name }}</p>
          <div class="text-sm text-gray-300 mb-6" v-html="currentTopic.content"></div>
          <button @click="startPostTest" class="py-3 bg-green-600 rounded w-72 text-white">Start Post Test</button>

          <div v-html="formattedContent" class="topic-content text-white leading-relaxed max-h-64 overflow-y-auto pr-2"></div>

          <div v-if="currentTopic.code" class="bg-gray-900 text-green-300 p-4 rounded my-4 font-mono">
            <pre>{{ currentTopic.code }}</pre>
          </div>
        </div>

        <!-- Post Test -->
        <div v-if="postTestDisplay">
          <h1 class="text-4xl text-white">This is Post Test</h1>
          <h2 class="text-xl font-semibold text-white mb-4">Post Quiz for: {{ currentTopic.title }}</h2>

          <ul class="mb-4 space-y-4">
            <li v-for="(question, index) in PostTest" :key="index" class="bg-white text-black p-4 rounded shadow">
              <p class="font-medium mb-2">{{ index + 1 }}. {{ question.description }}</p>

              <div v-if="question.questionType === 'Choices'" v-for="(choice, i) in question.choices" :key="i" class="ml-4">
                <label class="block">
                  <input
                    type="radio"
                    :name="'post-q' + index"
                    :value="choice"
                    v-model="question.userAnswer"
                    @change="checkAnswer(question)"
                  />
                  {{ choice }}
                </label>
              </div>

              <div v-else-if="question.questionType === 'Blank'" class="ml-4">
                <input
                  type="text"
                  v-model="question.userAnswer"
                  class="border p-1 rounded w-1/2"
                  placeholder="Type your answer..."
                  @input="checkAnswer(question)"
                />
              </div>

              <div v-if="question.userAnswer">
                <span v-if="question.isCorrect" class="text-green-600 font-semibold">Correct!</span>
                <span v-else class="text-red-600 font-semibold">Incorrect</span>
              </div>
            </li>
          </ul>

          <button @click="submitQuiz('post')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Submit Post-Test
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { usePage } from '@inertiajs/vue3';

export default {
  props: {
    courseId: Number,
  },
  data() {
    return {
      currentTopicIndex: 0,
      topics: [],
      PostTest: [],
      PreTest: [],
      preTestCompleted: false,
      postTestDisplay: false,
      ModuleTopic: false,
    };
  },
  computed: {
    currentTopic() {
      return this.topics[this.currentTopicIndex];
    },
    formattedContent() {
      return this.currentTopic?.content || '';
    },
    user() {
      return usePage().props.auth.user;
    },
  },
  mounted() {
    this.fetchTopics().then(() => this.fetchPreTest());
  },
  methods: {
    async fetchTopics() {
      const res = await fetch(`/api/courses/1/topics`);
      const topics = await res.json();
      const userRes = await fetch(`/api/user-attempts/${this.user.id}`);
      const attempts = await userRes.json();

      const unlockedTopics = [];

      this.topics = topics.map((topic, index) => {
        const attempt = attempts.find(a => a.topic_id === topic.id);
        const passed = attempt?.passed === 1;

        const isUnlocked = index === 0 || unlockedTopics[index - 1] === true;

        unlockedTopics.push(passed);
        return {
          ...topic,
          unlocked: isUnlocked || passed,
        };
      });
    },

    async fetchPreTest() {
      const topicId = this.currentTopic?.id;
      if (!topicId) return;
      const res = await fetch(`/api/displayPreQuiz/${topicId}`);
      const questions = await res.json();
      const enriched = await Promise.all(
        questions.map(async q => {
          if (q.questionType === 'Choices') {
            const optRes = await fetch(`/api/options/by-question/${q.id}`);
            const options = await optRes.json();
            q.choices = options.map(o => o.option_text);
          }
          return { ...q, userAnswer: '', isCorrect: false };
        })
      );
      this.PreTest = enriched;
    },
    async fetchPostTest() {
      const topicId = this.currentTopic?.id;
      if (!topicId) return;
      const res = await fetch(`/api/displayPostQuiz/${topicId}`);
      const questions = await res.json();
      const enriched = await Promise.all(
        questions.map(async q => {
          if (q.questionType === 'Choices') {
            const optRes = await fetch(`/api/options/by-question/${q.id}`);
            const options = await optRes.json();
            q.choices = options.map(o => o.option_text);
          }
          return { ...q, userAnswer: '', isCorrect: false };
        })
      );
      this.PostTest = enriched;
    },
    checkAnswer(q) {
      if (!q || !q.answer) return;
      q.isCorrect = q.questionType === 'Blank'
        ? q.userAnswer.trim().toLowerCase() === q.answer.trim().toLowerCase()
        : q.userAnswer === q.answer;
    },
    async submitQuiz(type) {
      const testData = type === 'pre' ? this.PreTest : this.PostTest;
      const score = testData.filter(q => q.isCorrect).length;
      const total = testData.length;
      const passed = score >= Math.ceil(total * 0.6);

      const payload = {
        user_id: this.user.id,
        topic_id: this.currentTopic.id,
        type,
        score,
        total,
        passed,
        timestamp: new Date().toISOString(),
      };

      await fetch('/api/recordAttempt', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });

      if (type === 'pre') {
        this.preTestCompleted = true;
        this.ModuleTopic = true;
      } else {
        this.postTestDisplay = false;
        this.quizCompleted = true;

        if (passed && this.currentTopicIndex + 1 < this.topics.length) {
          this.topics[this.currentTopicIndex + 1].unlocked = true;
        }
      }
    },

    goToTopic(index) {
      const topic = this.topics[index];
      if (!topic.unlocked) return;

      this.currentTopicIndex = index;
      this.preTestCompleted = false;
      this.ModuleTopic = false;
      this.postTestDisplay = false;
      this.fetchPreTest();
    },
    startPostTest() {
      this.ModuleTopic = false;
      this.fetchPostTest().then(() => {
        this.postTestDisplay = true;
      });
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
