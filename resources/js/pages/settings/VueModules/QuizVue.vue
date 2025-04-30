<template>
  <div class="min-h-screen bg-gray-900 flex items-center justify-center">
    <div class="bg-gray-800 p-10 rounded-md w-[600px] text-white">
      <h1 class="text-3xl font-bold mb-8 text-center">Quiz - Vue Module</h1>

      <div v-for="(question, index) in questions" :key="index" class="mb-6">
        <p class="font-semibold mb-3">Question {{ index + 1 }}:</p>
        <p class="mb-3">{{ question.text }}</p>

        <div class="space-y-2">
          <label v-for="(option, i) in question.options" :key="i" class="block">
            <input
              type="radio"
              :name="'question-' + index"
              :value="option"
              v-model="userAnswers[index]"
              class="mr-2"
            />
            {{ option }}
          </label>
        </div>
      </div>

      <div class="flex justify-between">
        <button
          @click="submitQuiz"
          class="bg-green-500 px-4 py-2 rounded hover:bg-green-600"
        >
          Submit
        </button>
        <button
          @click="resetQuiz"
          class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600"
        >
          Reset
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "QuizVue",
  data() {
    return {
      questions: [
        {
          text: "Lorem ipsum dolor sit amet?",
          options: ["Option A", "Option B", "Option C", "Option D"]
        },
        {
          text: "Consectetur adipiscing elit?",
          options: ["Option A", "Option B", "Option C", "Option D"]
        },
        {
          text: "Sed do eiusmod tempor incididunt?",
          options: ["Option A", "Option B", "Option C", "Option D"]
        },
        {
          text: "Ut labore et dolore magna aliqua?",
          options: ["Option A", "Option B", "Option C", "Option D"]
        },
        {
          text: "Ut enim ad minim veniam?",
          options: ["Option A", "Option B", "Option C", "Option D"]
        }
      ],
      userAnswers: {}
    };
  },
  methods: {
    submitQuiz() {
      console.log("Submitted answers:", this.userAnswers);
      alert("Quiz submitted!");
    },
    resetQuiz() {
      this.userAnswers = {};
    }
  }
};
</script>

<style scoped>
input[type="radio"] {
  transform: scale(1.2);
}
</style>

  