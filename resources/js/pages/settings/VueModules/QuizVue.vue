// QuizVue.vue
<template>
  <div class="min-h-screen bg-gray-900 text-white flex justify-center items-center">
    <div class="bg-gray-800 p-8 rounded shadow-lg w-full max-w-xl">
      <div>
        <h2 class="text-2xl font-semibold mb-4">Quiz</h2>
        <p class="mb-2 font-medium">Question {{ currentQuestionIndex + 1 }}:</p>
        <p class="mb-4">{{ currentQuestion.text }}</p>

        <div class="mb-6 space-y-2">
          <label
            v-for="(option, index) in currentQuestion.options"
            :key="index"
            class="flex items-center"
          >
            <input
              type="radio"
              :value="option"
              v-model="userAnswers[currentQuestionIndex]"
              class="mr-2"
            />
            {{ option }}
          </label>
        </div>

        <div class="flex justify-end">
          <button
            :disabled="!userAnswers[currentQuestionIndex]"
            @click="nextQuestion"
            :class="[
              'px-4 py-2 rounded text-white',
              isLastQuestion ? 'bg-green-500 hover:bg-green-600' : 'bg-blue-500 hover:bg-blue-600',
              !userAnswers[currentQuestionIndex] ? 'opacity-50 cursor-not-allowed' : ''
            ]"
          >
            {{ isLastQuestion ? 'Submit' : 'Next' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    questions: Array
  },
  data() {
    return {
      currentQuestionIndex: 0,
      userAnswers: []
    };
  },
  computed: {
    currentQuestion() {
      return this.questions[this.currentQuestionIndex];
    },
    isLastQuestion() {
      return this.currentQuestionIndex === this.questions.length - 1;
    }
  },
  methods: {
    nextQuestion() {
      if (!this.userAnswers[this.currentQuestionIndex]) return;

      if (this.isLastQuestion) {
        this.submitAnswers();
      } else {
        this.currentQuestionIndex++;
      }
    },
    submitAnswers() {
      console.log("Submitted answers:", this.userAnswers);
      alert("Quiz completed!");
      // You can use axios.post to send this to your Laravel backend
    }
  }
};
</script>
