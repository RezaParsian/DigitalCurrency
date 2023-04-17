import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import jquery from 'jquery';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm.js',
        },
    },
    define: {
        $: jquery,
        'window.jQuery': jquery,
    }
});
