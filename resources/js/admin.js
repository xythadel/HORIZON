// resources/js/admin.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

createApp(App).use(router).mount('#app');

import { createApp } from 'vue';
import CourseManager from './pages/Admin/AdminDashboard.vue';
import axios from 'axios';

// Set up CSRF protection for axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Create and mount Vue app
const app = createApp({});
app.component('course-manager', CourseManager);
app.mount('#app');