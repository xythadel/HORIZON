<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-900 text-white">
    <div class="w-full max-w-md p-8 bg-gray-800 rounded shadow-md">
      <h2 class="text-xl font-bold mb-4">Quiz</h2>
      <p class="mb-6">{{ questions[currentQuestion].text }}</p>

      <div class="space-y-2 mb-6">
        <div v-for="(option, index) in questions[currentQuestion].options" :key="index">
          <label class="flex items-center space-x-2">
            <input type="radio" :value="option" v-model="selectedOption" />
            <span>{{ option }}</span>
          </label>
        </div>
      </div>

      <button
        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
        @click="nextQuestion"
        :disabled="!selectedOption"
      >
        {{ currentQuestion < questions.length - 1 ? 'Next' : 'Finish' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = usePage().props
const questions = ref(props.questions)

const currentQuestion = ref(0)
const selectedOption = ref(null)

function nextQuestion() {
  if (currentQuestion.value < questions.value.length - 1) {
    currentQuestion.value++
    selectedOption.value = null
  } else {
    alert('Quiz complete!')
    // Here you can post the answers to Laravel backend if needed
  }
}
</script>


  