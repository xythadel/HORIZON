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

const routes = [
  { path: '/module-vue', component: ModuleVue },
  { path: '/module-lara', component: ModuleLara },
  { path: '/my-learning', component: MyLearning }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;