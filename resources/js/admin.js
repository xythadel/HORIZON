import { createApp } from 'vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';

const app = createApp({});
app.component('AdminDashboard', AdminDashboard);
app.mount('#admin-app');
