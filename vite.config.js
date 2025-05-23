import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'node:path'; // Fixes path resolution

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@settings': path.resolve(__dirname, 'resources/js/pages/setting'), // pick one, this is correct for MyLearning.vue
            '@vueModules': path.resolve(__dirname, 'resources/js/settings/VueModules'),
            '@laraModules': path.resolve(__dirname, 'resources/js/settings/LaraModules'),
        },
    },
});
