// QuizLara.vue
<template>
    <div class="min-h-screen bg-gray-800 flex flex-col items-center justify-center text-white p-8">
      <h1 class="text-3xl font-bold mb-8">Quiz - Laravel Module</h1>
  
      <div v-for="(question, index) in questions" :key="index" class="mb-10 w-full max-w-md">
        <div class="bg-gray-700 p-6 rounded-lg shadow-lg">
          <h2 class="text-lg font-semibold mb-4">Question {{ index + 1 }}</h2>
          <p class="mb-6">{{ question.text }}</p>
  
          <div class="space-y-2">
            <div v-for="(option, idx) in question.options" :key="idx" class="flex items-center">
              <input type="radio" :id="`q${index}o${idx}`" :name="`question${index}`" class="mr-2" />
              <label :for="`q${index}o${idx}`">{{ option }}</label>
            </div>
          </div>
  
          <button class="mt-6 w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded xl">
            Next
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { usePage } from '@inertiajs/vue3';
  
  const { props } = usePage();
  const questions = props.questions; // FETCH dynamic from Laravel
  </script>
  