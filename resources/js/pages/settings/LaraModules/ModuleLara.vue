//laravel page
<template>
    <div class="flex min-h-screen bg-gray-800 text-white">
      <!-- Sidebar -->
      <aside class="w-48 bg-white text-gray-900 py-6 px-4">
        <h2 class="text-xl font-bold mb-4">Horizon</h2>
        <nav class="space-y-2">
          <div v-for="(topic, index) in topics" :key="index">
            <button
              class="block w-full text-left px-2 py-1 rounded hover:bg-gray-200"
              :class="{ 'font-bold': currentTopicIndex === index }"
              :disabled="!topic.unlocked"
              @click="goToTopic(index)"
            >
              {{ topic.title }}
            </button>
          </div>
        </nav>
        <button class="mt-6 text-sm text-gray-600 hover:underline">&larr; Back</button>
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
        topics: [
          {
            title: "Introduction to Laravel",
            content: `<p>Laravel is a PHP web application framework with expressive, elegant syntax...</p>`,
            code: `composer create-project laravel/laravel example-app`,
            unlocked: true,
            completed: false,
          },
          {
            title: "Routing in Laravel",
            content: `<p>Routes in Laravel define the endpoints your application responds to...</p>` ,
            code: `Route::get('/', function () {
    return view('welcome');
  });`,
            unlocked: false,
            completed: false,
          },
          {
            title: "Controllers",
            content: `<p>Controllers are responsible for handling request logic...</p>` ,
            code: `php artisan make:controller PostController`,
            unlocked: false,
            completed: false,
          },
          {
            title: "Blade Templating",
            content: `<p>Blade is Laravel's powerful templating engine...</p>` ,
            code: `@extends('layouts.app')
  @section('content')
  <h1>Hello, Blade!</h1>
  @endsection`,
            unlocked: false,
            completed: false,
          },
          {
            title: "Eloquent ORM",
            content: `<p>Eloquent provides a simple ActiveRecord implementation for working with databases...</p>` ,
            code: `use App\Models\User;
  $users = User::all();`,
            unlocked: false,
            completed: false,
          },
        ],
      };
    },
    computed: {
      currentTopic() {
        return this.topics[this.currentTopicIndex];
      },
    },
    methods: {
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
  