// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'


export default defineConfig({
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler'
        }
    },
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        }
    },
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
