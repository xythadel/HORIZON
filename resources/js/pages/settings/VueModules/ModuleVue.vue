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
      <a href="dashboard" class="absolute bottom-10 left-10 text-base font-normal text-zinc-800 hover:text-indigo-600">
        <button class="mt-6 text-sm text-gray-600 hover:underline">&larr; Back</button>
      </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
      <div v-if="currentTopic">
        <!-- Pre Test -->
        <div v-if="!preTestCompleted">
          <h1 class="text-4xl text-white">QUIZ</h1>

          <div class="items-center flex justify-center gap-4 mb-4 w-full mt-[200px]">
            <div v-if="PreTest.length && currentPreQuestionIndex < PreTest.length" class=" text-white p-10 rounded-xl w-full max-w-xl mx-auto border">
              <p class="font-medium text-2xl mb-2">
                Question {{ currentPreQuestionIndex + 1 }}:<br>
                {{ PreTest[currentPreQuestionIndex].description }} ?
              </p>

              <!-- Choices -->
              <div v-if="PreTest[currentPreQuestionIndex].questionType === 'Choices'" class="bg-[#5A5A5A] p-8 rounded-2xl">
                <label v-for="(choice, i) in PreTest[currentPreQuestionIndex].choices" :key="i" class="flex items-center gap-4">
                  <input
                    type="radio"
                    :name="'pre-question'"
                    :value="choice"
                    v-model="PreTest[currentPreQuestionIndex].userAnswer"
                    @change="onAnswerPre"
                  />
                  {{ choice }}
                </label>
              </div>

              <!-- Fill in the blank -->
              <div v-else-if="PreTest[currentPreQuestionIndex].questionType === 'Blank'" class="flex flex-col justify-end items-end">
                <input
                  type="text"
                  class="border p-1 rounded w-full text-black"
                  v-model="PreTest[currentPreQuestionIndex].userAnswer"
                  placeholder="Type your answer..."
                  @keydown.enter="onAnswerPre"
                />
                <button @click="onAnswerPre" class="mt-2 bg-[#4AC887] text-black px-4 py-2 rounded-full w-32">Next</button>
              </div>
            </div>

            <!-- When All Questions Are Done -->
            <div v-else-if="PreTest.length && currentPreQuestionIndex >= PreTest.length" class="border text-center p-10 w-1/3 rounded-2xl">
              <p class="text-white text-xl mb-4">You completed all Pre-Test questions.</p>
              <button @click="submitQuiz('pre')" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Submit Pre-Test</button>
            </div>

          </div>
        </div>
        <div v-if="ModuleTopic">
          <h1 class="text-2xl font-bold mb-1 text-white">{{ currentTopic.title }}</h1>
          <p class="text-sm text-gray-300 mb-6">{{ currentTopic.module_name }}</p>
          <div v-if="lessons.length" class="h-[700px] overflow-auto scrollbar-w-1">
            <div v-for="(lesson, i) in lessons" :key="lesson.id" class="bg-zinc-700 text-white p-6 rounded-xl mb-4">
              <div v-html="lesson.content" class="leading-relaxed text-white"></div>
            </div>
          </div>
          <div class="mt-5 flex justify-end">
            <button @click="startPostTest" class="py-3 bg-green-600 rounded-full w-72 text-white">Start Post Test</button>
          </div>
          <div v-if="currentTopic.code" class="bg-gray-900 text-green-300 p-4 rounded my-4 font-mono">
            <pre>{{ currentTopic.code }}</pre>
          </div>
        </div>

        <!-- Post Test -->
        <div v-if="postTestDisplay">
          <h1 class="text-4xl text-white">QUIZ</h1>

          <div class="items-center flex justify-center gap-4 mb-4 w-full mt-[200px]">
            <div v-if="PostTest.length && currentPostQuestionIndex < PostTest.length" class=" text-white p-10 rounded-xl w-full max-w-xl mx-auto border">
              <p class="font-medium text-2xl mb-2">
                Question {{ currentPostQuestionIndex + 1 }}:<br>
                {{ PostTest[currentPostQuestionIndex].description }} ?
              </p>

              <!-- Choices -->
              <div v-if="PostTest[currentPostQuestionIndex].questionType === 'Choices'" class="bg-[#5A5A5A] p-8 rounded-2xl">
                <label v-for="(choice, i) in PostTest[currentPostQuestionIndex].choices" :key="i" class="flex items-center gap-4">
                  <input
                    type="radio"
                    :name="'Post-question'"
                    :value="choice"
                    v-model="PostTest[currentPostQuestionIndex].userAnswer"
                    @change="onAnswerPost"
                  />
                  {{ choice }}
                </label>
              </div>

              <!-- Fill in the blank -->
              <div v-else-if="PostTest[currentPostQuestionIndex].questionType === 'Blank'" class="flex flex-col justify-end items-end">
                <input
                  type="text"
                  class="border p-1 rounded w-full text-black"
                  v-model="PostTest[currentPostQuestionIndex].userAnswer"
                  placeholder="Type your answer..."
                  @keydown.enter="onAnswerPost"
                />
                <button @click="onAnswerPost" class="mt-2 bg-[#4AC887] text-black px-4 py-2 rounded-full w-32">Next</button>
              </div>
            </div>

            <!-- When All Questions Are Done -->
            <div v-else-if="PostTest.length && currentPostQuestionIndex >= PostTest.length" class="border text-center p-10 w-1/3 rounded-2xl">
              <p class="text-white text-xl mb-4">You completed all Post-Test questions.</p>
              <button @click="submitQuiz('post')" class="bg-green-600 text-white px-4 py-2 rounded mt-4">Submit Post-Test</button>
            </div>
          </div>
        </div>
        <div v-if="quizCompleted && postTestResult" class="text-center mt-10 text-white">
          <p class="text-2xl mb-6">
            You {{ postTestResult === 'pass' ? 'passed' : 'did not pass' }} the post-test.
          </p>
          <button
            v-if="postTestResult === 'pass'"
            @click="goToNextTopic"
            class="bg-green-600 px-6 py-3 rounded-full text-white text-lg"
          >
            Continue to Next Topic
          </button>
          <button
            v-else
            @click="retryLessons"
            class="bg-red-600 px-6 py-3 rounded-full text-white text-lg"
          >
            Retry from First Lesson
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
      lessons: [],
      preTestCompleted: false,
      postTestDisplay: false,
      postTestResult: null,
      quizCompleted: false,
      ModuleTopic: false,
      timer: null,
      startTime: null,
      elapsedTime: 0, 
      PreTest: [],
      currentPreQuestionIndex: 0,
      currentPostQuestionIndex: 0,
      selectedDifficulty: 1,
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
    this.fetchTopics().then(() => {
      this.fetchPreTest().then(() => {
        this.startTimer();
      });
    });
  },
  methods: {
    onAnswerPre() {
      const question = this.PreTest[this.currentPreQuestionIndex];
      if (!question.userAnswer) return;

      question.isCorrect =
        question.questionType === 'Blank'
          ? question.userAnswer.trim().toLowerCase() === question.answer.trim().toLowerCase()
          : question.userAnswer === question.answer;

      setTimeout(() => {
        this.currentPreQuestionIndex++;
      }, 300);
    },

    onAnswerPost() {
      const question = this.PostTest[this.currentPostQuestionIndex];
      if (!question.userAnswer) return;

      question.isCorrect =
        question.questionType === 'Blank'
          ? question.userAnswer.trim().toLowerCase() === question.answer.trim().toLowerCase()
          : question.userAnswer === question.answer;

      setTimeout(() => {
        this.currentPostQuestionIndex++;
      }, 300);
    },
    async fetchLessons() {
      const topicId = this.currentTopic?.id;
      if (!topicId) return;

      const res = await fetch(`/api/topics/${topicId}/lessons`);
      const allLessons = await res.json();

      // Filter based on selectedDifficulty
      this.lessons = allLessons.filter(lesson => lesson.difficulty === this.selectedDifficulty);
    },
    async goToNextTopic() {
      const nextIndex = this.currentTopicIndex + 1;
      if (nextIndex >= this.topics.length) return;

      this.currentTopicIndex = nextIndex;
      this.preTestCompleted = true;
      this.ModuleTopic = true;
      this.postTestDisplay = false;
      this.quizCompleted = false;
      this.postTestResult = null;

      await this.fetchLessons();
    },

    async retryLessons() {
      this.selectedDifficulty = 1;
      this.ModuleTopic = true;
      this.postTestDisplay = false;
      this.quizCompleted = false;
      this.postTestResult = null;

      await this.fetchLessons();
    },
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
      const filtered = questions.filter(q => q.difficulty === this.selectedDifficulty);

      const enriched = await Promise.all(
        filtered.map(async q => {
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
      this.stopTimer();

      const testData = type === 'pre' ? this.PreTest : this.PostTest;
      const score = testData.filter(q => q.isCorrect).length;
      const total = testData.length;
      const passed = score >= Math.ceil(total * 0.6);

      let difficulty = 1;
      const percentage = score / total;

      if (percentage === 1) {
        difficulty = 3;
      } else if (percentage >= 0.5) {
        difficulty = 2;
      }
      const attempts = testData.map((q, index) => {
        return {
          user_id: this.user.id,
          topic_id: this.currentTopic.id,
          quiz_id: q.id,
          type,
          score: q.isCorrect ? 1 : 0,
          total: 1,
          passed: q.isCorrect ? true : false,
          difficulty: q.difficulty || this.selectedDifficulty,
          time_taken: this.elapsedTime,
          timestamp: new Date().toISOString(),
        };
      });
      for (const attempt of attempts) {
        await fetch('/api/recordAttempt', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(attempt),
        });
      }

      if (type === 'pre') {
        this.preTestCompleted = true;
        this.ModuleTopic = true;
        this.selectedDifficulty = difficulty;
        await this.fetchLessons();
      } else {
        this.postTestDisplay = false;
        this.quizCompleted = true;
        this.postTestResult = passed ? 'pass' : 'fail';

        if (passed && this.currentTopicIndex + 1 < this.topics.length) {
          this.topics[this.currentTopicIndex + 1].unlocked = true;
        }
      }
    },

    startTimer() {
      this.stopTimer(); // Make sure no previous timer is running
      this.startTime = Date.now();
      this.elapsedTime = 0;

      this.timer = setInterval(() => {
        this.elapsedTime = Math.floor((Date.now() - this.startTime) / 1000);
      }, 1000);
    },

    stopTimer() {
      clearInterval(this.timer);
      this.timer = null;
    },

    resetTimer() {
      this.elapsedTime = 0;
      this.startTime = null;
    },

    goToTopic(index) {
      const topic = this.topics[index];
      if (!topic.unlocked) return;
      this.currentPreQuestionIndex = 0;
      this.currentPostQuestionIndex = 0;
      this.stopTimer();
      this.resetTimer();

      this.currentTopicIndex = index;
      this.preTestCompleted = false;
      this.ModuleTopic = false;
      this.postTestDisplay = false;

      this.fetchPreTest().then(() => {
        this.startTimer();
      });

      this.fetchLessons();
    },

    startPostTest() {
      this.ModuleTopic = false;

      this.stopTimer();
      this.resetTimer();

      this.fetchPostTest().then(() => {
        this.postTestDisplay = true;
        this.startTimer();
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
