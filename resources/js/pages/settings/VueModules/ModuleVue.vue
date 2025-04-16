<template>
  <div class="flex flex-col md:flex-row min-h-screen bg-gray-800 text-white">
    <!-- Sidebar -->
    <aside class="w-full md:w-48 bg-white text-gray-900 py-6 px-4">
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
    <main class="flex-1 p-4 md:p-10">
      <div v-if="currentTopic && !showingQuiz">
        <h1 class="text-2xl font-bold mb-4">{{ currentTopic.title }}</h1>
        <div v-html="currentTopic.content" class="prose prose-invert max-w-none"></div>
        
        <!-- Code snippet -->
        <div v-if="currentTopic.code" class="bg-gray-900 text-green-300 p-4 rounded my-4 font-mono overflow-x-auto">
          <pre>{{ currentTopic.code }}</pre>
        </div>
        
        <!-- Take Quiz button -->
        <button
          v-if="!currentTopic.completed"
          class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
          @click="startQuiz"
        >
          Take Quiz
        </button>
      </div>
      
      <!-- Quiz Component -->
      <div v-if="showingQuiz" class="max-w-md mx-auto bg-gray-900 rounded-lg p-6 shadow-lg">
        <div class="text-gray-400 text-sm mb-1">Horizon</div>
        <h2 class="text-xl font-bold mb-4">Quiz</h2>
        
        <div v-if="!quizCompleted">
          <p class="mb-4">Question {{ currentQuestionIndex + 1 }}/{{ currentTopic.quiz.questions.length }}</p>
          <p class="mb-3">{{ currentQuestion.question }}</p>
          
          <div class="space-y-2 mb-6">
            <div 
              v-for="(option, index) in currentQuestion.options" 
              :key="index"
              class="flex items-start"
            >
              <input 
                type="radio" 
                :id="'option-' + index" 
                :value="index" 
                v-model="selectedAnswer"
                class="mt-1 mr-2"
              >
              <label :for="'option-' + index" class="text-sm">{{ option }}</label>
            </div>
          </div>
          
          <button 
            :class="{'bg-blue-500 hover:bg-blue-600': isRightPanel, 'bg-green-500 hover:bg-green-600': !isRightPanel}"
            class="px-4 py-2 text-white rounded text-sm"
            @click="submitAnswer"
            :disabled="selectedAnswer === null"
          >
            Submit
          </button>
        </div>
        
        <div v-else>
          <p class="mb-6">You've completed the quiz!</p>
          <p class="mb-6">Score: {{ quizScore }}/{{ currentTopic.quiz.questions.length }}</p>
          <button 
            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm"
            @click="finishQuiz"
          >
            Continue
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentTopicIndex: 0,
      showingQuiz: false,
      currentQuestionIndex: 0,
      selectedAnswer: null,
      quizScore: 0,
      quizCompleted: false,
      isRightPanel: false,
      topics: [
        {
          title: "Introduction",
          content: `<p>Vue.js is a progressive framework for building user interfaces...</p>`,
          code: `import { createApp } from 'vue'
const app = createApp({
  data() {
    return {
      message: 'Hello Vue!'
    }
  }
})
app.mount('#app')`,
          unlocked: true,
          completed: false,
          quiz: {
            questions: [
              {
                question: "Lorem ipsum dolor sit amet, consectetur adipiscing elit?",
                options: [
                  "Lorem ipsum dolor sit amet",
                  "Consectetur adipiscing elit",
                  "Sed do eiusmod tempor",
                  "Ut labore et dolore magna"
                ],
                correctAnswer: 1
              },
              {
                question: "Duis aute irure dolor in reprehenderit in voluptate?",
                options: [
                  "Velit esse cillum dolore",
                  "Eu fugiat nulla pariatur",
                  "Excepteur sint occaecat",
                  "Cupidatat non proident"
                ],
                correctAnswer: 0
              },
              {
                question: "Sunt in culpa qui officia deserunt mollit anim id est laborum?",
                options: [
                  "Sed ut perspiciatis unde",
                  "Omnis iste natus error",
                  "Sit voluptatem accusantium",
                  "Doloremque laudantium"
                ],
                correctAnswer: 2
              },
              {
                question: "At vero eos et accusamus et iusto odio dignissimos?",
                options: [
                  "Ducimus qui blanditiis",
                  "Praesentium voluptatum",
                  "Deleniti atque corrupti",
                  "Quos dolores et quas"
                ],
                correctAnswer: 1
              },
              {
                question: "Nam libero tempore, cum soluta nobis est eligendi optio?",
                options: [
                  "Cumque nihil impedit",
                  "Quo minus id quod",
                  "Maxime placeat facere",
                  "Possimus omnis voluptas"
                ],
                correctAnswer: 2
              }
            ]
          }
        },
        {
          title: "Creating a Vue Application",
          content: `<p>Learn how to create your first Vue app using the Vue CLI or Vite.</p>`,
          code: '',
          unlocked: false,
          completed: false,
          quiz: {
            questions: [
              {
                question: "Lorem ipsum dolor sit amet, consectetur adipiscing elit?",
                options: [
                  "Lorem ipsum dolor sit amet",
                  "Consectetur adipiscing elit",
                  "Sed do eiusmod tempor",
                  "Ut labore et dolore magna"
                ],
                correctAnswer: 2
              },
              {
                question: "Duis aute irure dolor in reprehenderit in voluptate?",
                options: [
                  "Velit esse cillum dolore",
                  "Eu fugiat nulla pariatur",
                  "Excepteur sint occaecat",
                  "Cupidatat non proident"
                ],
                correctAnswer: 0
              },
              {
                question: "Sunt in culpa qui officia deserunt mollit anim id est laborum?",
                options: [
                  "Sed ut perspiciatis unde",
                  "Omnis iste natus error",
                  "Sit voluptatem accusantium",
                  "Doloremque laudantium"
                ],
                correctAnswer: 1
              },
              {
                question: "At vero eos et accusamus et iusto odio dignissimos?",
                options: [
                  "Ducimus qui blanditiis",
                  "Praesentium voluptatum",
                  "Deleniti atque corrupti",
                  "Quos dolores et quas"
                ],
                correctAnswer: 0
              },
              {
                question: "Nam libero tempore, cum soluta nobis est eligendi optio?",
                options: [
                  "Cumque nihil impedit",
                  "Quo minus id quod",
                  "Maxime placeat facere",
                  "Possimus omnis voluptas"
                ],
                correctAnswer: 3
              }
            ]
          }
        },
        {
          title: "Vue Components",
          content: `<p>Components are reusable Vue instances with a name...</p>`,
          code: '',
          unlocked: false,
          completed: false,
          quiz: {
            questions: [
              {
                question: "Lorem ipsum dolor sit amet, consectetur adipiscing elit?",
                options: [
                  "Lorem ipsum dolor sit amet",
                  "Consectetur adipiscing elit",
                  "Sed do eiusmod tempor",
                  "Ut labore et dolore magna"
                ],
                correctAnswer: 1
              },
              {
                question: "Duis aute irure dolor in reprehenderit in voluptate?",
                options: [
                  "Velit esse cillum dolore",
                  "Eu fugiat nulla pariatur",
                  "Excepteur sint occaecat",
                  "Cupidatat non proident"
                ],
                correctAnswer: 2
              },
              {
                question: "Sunt in culpa qui officia deserunt mollit anim id est laborum?",
                options: [
                  "Sed ut perspiciatis unde",
                  "Omnis iste natus error",
                  "Sit voluptatem accusantium",
                  "Doloremque laudantium"
                ],
                correctAnswer: 0
              },
              {
                question: "At vero eos et accusamus et iusto odio dignissimos?",
                options: [
                  "Ducimus qui blanditiis",
                  "Praesentium voluptatum",
                  "Deleniti atque corrupti",
                  "Quos dolores et quas"
                ],
                correctAnswer: 1
              },
              {
                question: "Nam libero tempore, cum soluta nobis est eligendi optio?",
                options: [
                  "Cumque nihil impedit",
                  "Quo minus id quod",
                  "Maxime placeat facere",
                  "Possimus omnis voluptas"
                ],
                correctAnswer: 2
              }
            ]
          }
        },
        {
          title: "Props and Events",
          content: `<p>Props are custom attributes you can register on a component...</p>`,
          code: '',
          unlocked: false,
          completed: false,
          quiz: {
            questions: [
              {
                question: "Lorem ipsum dolor sit amet, consectetur adipiscing elit?",
                options: [
                  "Lorem ipsum dolor sit amet",
                  "Consectetur adipiscing elit",
                  "Sed do eiusmod tempor",
                  "Ut labore et dolore magna"
                ],
                correctAnswer: 1
              },
              {
                question: "Duis aute irure dolor in reprehenderit in voluptate?",
                options: [
                  "Velit esse cillum dolore",
                  "Eu fugiat nulla pariatur",
                  "Excepteur sint occaecat",
                  "Cupidatat non proident"
                ],
                correctAnswer: 1
              },
              {
                question: "Sunt in culpa qui officia deserunt mollit anim id est laborum?",
                options: [
                  "Sed ut perspiciatis unde",
                  "Omnis iste natus error",
                  "Sit voluptatem accusantium",
                  "Doloremque laudantium"
                ],
                correctAnswer: 2
              },
              {
                question: "At vero eos et accusamus et iusto odio dignissimos?",
                options: [
                  "Ducimus qui blanditiis",
                  "Praesentium voluptatum",
                  "Deleniti atque corrupti",
                  "Quos dolores et quas"
                ],
                correctAnswer: 2
              },
              {
                question: "Nam libero tempore, cum soluta nobis est eligendi optio?",
                options: [
                  "Cumque nihil impedit",
                  "Quo minus id quod",
                  "Maxime placeat facere",
                  "Possimus omnis voluptas"
                ],
                correctAnswer: 1
              }
            ]
          }
        },
        {
          title: "Computed Properties and Watchers",
          content: `<p>Computed properties are cached based on their dependencies...</p>`,
          code: '',
          unlocked: false,
          completed: false,
          quiz: {
            questions: [
              {
                question: "Lorem ipsum dolor sit amet, consectetur adipiscing elit?",
                options: [
                  "Lorem ipsum dolor sit amet",
                  "Consectetur adipiscing elit",
                  "Sed do eiusmod tempor",
                  "Ut labore et dolore magna"
                ],
                correctAnswer: 1
              },
              {
                question: "Duis aute irure dolor in reprehenderit in voluptate?",
                options: [
                  "Velit esse cillum dolore",
                  "Eu fugiat nulla pariatur",
                  "Excepteur sint occaecat",
                  "Cupidatat non proident"
                ],
                correctAnswer: 1
              },
              {
                question: "Sunt in culpa qui officia deserunt mollit anim id est laborum?",
                options: [
                  "Sed ut perspiciatis unde",
                  "Omnis iste natus error",
                  "Sit voluptatem accusantium",
                  "Doloremque laudantium"
                ],
                correctAnswer: 1
              },
              {
                question: "At vero eos et accusamus et iusto odio dignissimos?",
                options: [
                  "Ducimus qui blanditiis",
                  "Praesentium voluptatum",
                  "Deleniti atque corrupti",
                  "Quos dolores et quas"
                ],
                correctAnswer: 1
              },
              {
                question: "Nam libero tempore, cum soluta nobis est eligendi optio?",
                options: [
                  "Cumque nihil impedit",
                  "Quo minus id quod",
                  "Maxime placeat facere",
                  "Possimus omnis voluptas"
                ],
                correctAnswer: 1
              }
            ]
          }
        },
      ],
    };
  },
  computed: {
    currentTopic() {
      return this.topics[this.currentTopicIndex];
    },
    currentQuestion() {
      if (!this.currentTopic || !this.currentTopic.quiz || !this.currentTopic.quiz.questions) {
        return null;
      }
      return this.currentTopic.quiz.questions[this.currentQuestionIndex];
    }
  },
  methods: {
    goToTopic(index) {
      if (this.topics[index].unlocked) {
        this.currentTopicIndex = index;
        this.showingQuiz = false;
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
    startQuiz() {
      this.showingQuiz = true;
      this.currentQuestionIndex = 0;
      this.selectedAnswer = null;
      this.quizScore = 0;
      this.quizCompleted = false;
      // Toggle between left and right panel styles from the image
      this.isRightPanel = Math.random() > 0.5;
    },
    submitAnswer() {
      if (this.selectedAnswer === this.currentQuestion.correctAnswer) {
        this.quizScore++;
      }
      
      if (this.currentQuestionIndex < this.currentTopic.quiz.questions.length - 1) {
        this.currentQuestionIndex++;
        this.selectedAnswer = null;
      } else {
        this.quizCompleted = true;
      }
    },
    finishQuiz() {
      this.showingQuiz = false;
      this.completeTopic();
    }
  },
};
</script>

<style scoped>
.prose p {
  margin-bottom: 1rem;
}

/* Additional media queries for better responsiveness */
@media (max-width: 768px) {
  .prose {
    font-size: 0.95rem;
  }
  
  pre {
    font-size: 0.8rem;
  }
}

@media (max-width: 640px) {
  .prose {
    font-size: 0.9rem;
  }
  
  pre {
    font-size: 0.7rem;
  }
}
</style>