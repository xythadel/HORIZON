// resources/js/admin.js
import { createApp } from 'vue';
import CourseManager from './components/CourseManager.vue';
import axios from 'axios';

// Set up CSRF protection for axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Create and mount Vue app
const app = createApp({});
app.component('course-manager', CourseManager);
app.mount('#app');