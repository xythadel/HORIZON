<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-700">
    <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg shadow-md text-white">
      <h3 class="text-sm text-gray-400 mb-2">{{currentQuestion + 1}}/{{questions.length}}</h3>
      <h2 class="text-xl font-bold mb-4">Quiz</h2>
      <p class="mb-6">{{ questions[currentQuestion].text }}</p>

      <div class="space-y-2 mb-6">
        <div v-for="(option, index) in questions[currentQuestion].options" :key="index"
          class="bg-gray-900 p-3 rounded flex items-center hover:bg-gray-700 transition-colors cursor-pointer"
          @click="selectedOption = option">
          <div class="mr-3 flex items-center justify-center">
            <div class="w-5 h-5 rounded-full border-2 border-white flex items-center justify-center">
              <div v-if="selectedOption === option" class="w-3 h-3 bg-white rounded-full"></div>
            </div>
          </div>
          <span>{{ option }}</span>
        </div>
      </div>

      <button
        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition-colors"
        @click="nextQuestion"
        :disabled="!selectedOption"
        :class="{'opacity-50 cursor-not-allowed': !selectedOption, 'cursor-pointer': selectedOption}"
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


  