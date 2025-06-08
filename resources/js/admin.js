//resources/js/admin.js
import { createApp as createAdminApp } from 'vue';
import axios from 'axios';
import { createRouter, createWebHistory } from 'vue-router';

// CSRF Token setup
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Import your Vue components
import ModuleVue from '@/settings/VueModules/ModuleVue.vue';
import ModuleLara from '@/settings/LaraModules/ModuleLara.vue';
import MyLearning from '@/settings/VueModules/MyLearning.vue';
import AdminDashboard from '@/pages/Admin/AdminDashboard.vue';
import Dashboard from '@/pages/Dashboard.vue';
import Login from '@/pages/auth/Login.vue';

// Define routes
const routes = [
  { path: '/module-vue', component: ModuleVue },
  { path: '/module-lara', component: ModuleLara },
  { path: '/my-learning', component: MyLearning },
  { path: '/admin-dashboard', component: AdminDashboard },
  { path: '/dashboard', component: Dashboard },
  { path: '/login', component: Login }
];

// Create router
const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard to redirect based on role
router.beforeEach((to, from, next) => {
  axios.get('/api/user')
    .then(response => {
      const user = response.data;

      // If accessing admin dashboard as a user, redirect
      if (to.path === '/admin-dashboard' && user.role !== 'admin') {
        return next('/dashboard');
      }

      // If accessing user dashboard as an admin, redirect
      if (to.path === '/dashboard' && user.role !== 'user') {
        return next('/admin-dashboard');
      }

      next();
    })
    .catch(() => {
      // Not authenticated
      if (to.path !== '/login') return next('/login');
      next();
    });
});

// Create and mount the Vue app
const adminApp = createAdminApp({});
adminApp.use(router);
adminApp.mount('#admin-app');
