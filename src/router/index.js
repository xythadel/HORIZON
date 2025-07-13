// resources/js/admin.js
import { createApp as createAdminApp } from 'vue'; // Rename to avoid conflict
import CourseManager from './components/CourseManager.vue';
import axios from 'axios';

// Set up CSRF protection
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Create and mount Vue app with different name
const adminApp = createAdminApp({});
adminApp.component('course-manager', CourseManager);
adminApp.mount('#admin-app'); // Use a different ID\

import { createRouter, createWebHistory } from 'vue-router';

// Import your components
import ModuleVue from '@/settings/VueModules/ModuleVue.vue';
import ModuleLara from '@/settings/LaraModules/ModuleLara.vue';
import MyLearning from '@/settings/VueModules/MyLearning.vue';
import QuizVue from '@/pages/settings/VueModules/QuizVue.vue';
import AdminDashboard from '@/pages/Admin/AdminDashboard.vue';
import Dashboard from '@/pages/Dashboard.vue';
import Login from '@/pages/Welcome.vue';
import Test from '@/pages/test';
import path from 'path';
import Sandpit from '@/pages/sandpit.vue';
import Badgesasd from '@/pages/badges.vue'

const routes = [
  { path: '/modulevue', component: ModuleVue },
  { path: '/modulelaravel', component: ModuleLara },
  { path: '/my-learning', component: MyLearning },
  { path: '/admin-dashboard', component: AdminDashboard },
  { path: '/dashboard', component: Dashboard },
  { path: '/test', component: Test},
  { path: '/login', component: Login },
  { path: '/quiz-vue', component: QuizVue },
  { path: '/sandpit', component: Badgesasd},
  { path: '/quiz-vue', component: QuizVue },
  { path: '/badges', component: Badgesasd },
];
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;