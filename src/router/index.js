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
