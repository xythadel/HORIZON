<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <nav class="space-y-2 mt-6">
        <div v-for="(topic, index) in topics" :key="index" class="flex justify-between items-center pr-4">
          <button
            class="block w-full text-left px-6 py-3 text-base text-black font-normal hover:bg-gray-100 transition duration-200"
            :class="{ 'font-bold': currentTopicIndex === index }"
            :disabled="!topic.unlocked"
            @click="goToTopic(index)"
          >
            {{ topic.title }}
          </button>
          <span v-if="topic.unlocked" class="ml-2 text-green-500">✓</span>
        </div>
      </nav>
      <a href="dashboard" class="absolute bottom-10 left-10 text-base font-normal text-zinc-800 hover:text-indigo-600">
        <button class="mt-6 text-sm text-gray-600 hover:underline">&larr; Back</button>
      </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
      <div v-if="currentTopic">
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
          <p class="text-2xl mb-2">
            You {{ postTestResult === 'pass' ? 'passed' : 'did not pass' }} the post-test.
          </p>
          <p class="text-xl mb-6">
            Your Score: {{ userTotalScore }} / {{ maxScore }}
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
    difficulty: Number,
  },
  data() {
    return {
      currentTopicIndex: 0,
      topics: [],
      PostTest: [],
      lessons: [],
      userTotalScore: 0,
      maxScore: 0,
      postTestDisplay: false,
      postTestResult: null,
      quizCompleted: false,
      ModuleTopic: true,
      timer: null,
      startTime: null,
      elapsedTime: 0,
      currentPostQuestionIndex: 0,
      selectedDifficulty: null,
    };
  },
  computed: {
    currentTopic() {
      return this.topics[this.currentTopicIndex];
    },
    user() {
      return usePage().props.auth.user;
    },
  },
  mounted() {
    this.fetchUserDifficulty().then(() => {
      this.fetchTopics().then(() => {
        this.checkCompletedTopics();
        this.startTimer();
      });
    });
  },
  methods: {
    async fetchUserDifficulty() {
      const res = await fetch(`/api/user-difficulty/${this.user.id}`);
      const result = await res.json();
      const difficultyRecord = result.find(d => d.course_id === 1);
      this.selectedDifficulty = difficultyRecord?.difficulty_level || 1;
    },
    async fetchLessons() {
      const topicId = this.currentTopic?.id;
      if (!topicId) return;

      const res = await fetch(`/api/topics/${topicId}/lessons`);
      const allLessons = await res.json();

      this.lessons = allLessons.filter(lesson => lesson.difficulty === this.selectedDifficulty);
    },
    async fetchTopics() {
      const res = await fetch(`/api/courses/1/topics`);
      const topics = await res.json();

      const allowedDifficulties = [1];
      if (this.selectedDifficulty >= 2) allowedDifficulties.push(2);
      if (this.selectedDifficulty === 3) allowedDifficulties.push(3);

      let filteredTopics = topics.filter(topic => allowedDifficulties.includes(topic.difficulty));
      this.topics = filteredTopics.map(topic => ({ ...topic, unlocked: false }));

      await this.unlockTopicsBasedOnProgress();
    },
    goToTopic(index) {
      if (!this.topics[index].unlocked) return;
      this.currentTopicIndex = index;
      this.ModuleTopic = true;
      this.postTestDisplay = false;
      this.quizCompleted = false;
      this.postTestResult = null;
      this.currentPostQuestionIndex = 0;
      this.stopTimer();
      this.resetTimer();
      this.fetchLessons();
      this.startTimer();
    },
    async fetchPostTest() {
      const topicId = this.currentTopic?.id;
      if (!topicId) return;

      try {
        const res = await fetch(`/api/displayPostQuiz/${topicId}`);
        const questions = await res.json();

        const enriched = await Promise.all(
          questions.map(async q => {
            if (q.questionType === 'Choices') {
              try {
                const optRes = await fetch(`/api/options/by-question/${q.id}`);
                const options = await optRes.json();
                const shuffled = options.map(o => o.option_text).sort(() => Math.random() - 0.5);
                q.choices = shuffled;
              } catch (err) {
                console.error(`Error fetching options for question ${q.id}:`, err);
                q.choices = [];
              }
            }

            return { ...q, userAnswer: '', isCorrect: false };
          })
        );

        this.PostTest = enriched;
      } catch (error) {
        console.error('Error loading post-test:', error);
        this.PostTest = [];
      }
    },
    async checkCompletedTopics() {
      try {
        const res = await fetch(`/api/user-topic-progress/${this.user.id}/1`);
        const completedTopics = await res.json();
        const completedIds = completedTopics.map(t => t.topic_id);

        this.topics = this.topics.map((topic, index) => {
          if (completedIds.includes(topic.id)) {
            topic.unlocked = true;
          } else if (index === 0) {
            topic.unlocked = true;
          }
          return topic;
        });
      } catch (error) {
        console.error("Failed to fetch completed topics", error);
      }
    },
    async unlockTopicsBasedOnProgress() {
      try {
        const res = await fetch(`/api/user-topic-progress/${this.user.id}/1`);
        const completed = await res.json();
        const completedIds = completed.map(item => item.topic_id);

        this.topics = this.topics.map((topic, index, arr) => {
          const isFirst = index === 0;
          const isPassed = completedIds.includes(topic.id);
          const prevPassed = index > 0 && completedIds.includes(arr[index - 1].id);

          topic.unlocked = isFirst || isPassed || prevPassed;
          return topic;
        });

      } catch (error) {
        console.error('Failed to unlock topics:', error);
      }
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
    async submitQuiz(type) {
      this.stopTimer();

      const testData = this.PostTest;

      // Calculate total possible score and user actual score
      const totalScore = testData.reduce((acc, q) => acc + (q.score || 1), 0);
      const userScore = testData.reduce((acc, q) => acc + (q.isCorrect ? (q.score || 1) : 0), 0);
      const passed = userScore >= Math.ceil(totalScore * 0.6);

      this.userTotalScore = userScore;
      this.maxScore = totalScore;

      const attempts = testData.map(q => ({
        user_id: this.user.id,
        topic_id: this.currentTopic.id,
        quiz_id: q.id,
        type,
        score: q.isCorrect ? (q.score || 1) : 0,
        total: q.score || 1,
        passed: q.isCorrect,
        difficulty: q.difficulty || this.selectedDifficulty,
        time_taken: this.elapsedTime,
        timestamp: new Date().toISOString(),
      }));

      for (const attempt of attempts) {
        await fetch('/api/recordAttempt', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(attempt),
        });
      }

      this.postTestDisplay = false;
      this.quizCompleted = true;
      this.postTestResult = passed ? 'pass' : 'fail';

      if (passed && this.currentTopicIndex + 1 < this.topics.length) {
        this.topics[this.currentTopicIndex + 1].unlocked = true;
      }
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
    goToNextTopic() {
      const nextIndex = this.currentTopicIndex + 1;
      if (nextIndex >= this.topics.length) return;
      this.goToTopic(nextIndex);
    },
    retryLessons() {
      this.ModuleTopic = true;
      this.postTestDisplay = false;
      this.quizCompleted = false;
      this.postTestResult = null;
      this.fetchLessons();
    },
    startTimer() {
      this.stopTimer();
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
  },
};
</script>
