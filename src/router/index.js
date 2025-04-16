import { createRouter, createWebHistory } from 'vue-router';

// Import your components
import ModuleVue from '@/settings/VueModules/ModuleVue.vue';
import ModuleLara from '@/settings/LaraModules/ModuleLara.vue';
import MyLearning from '@/settings/VueModules/MyLearning.vue';

const routes = [
  { path: '/modulevue', component: ModuleVue },
  { path: '/modulelara', component: ModuleLara },
  { path: '/mylearning', component: MyLearning }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
