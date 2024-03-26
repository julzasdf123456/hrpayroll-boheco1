import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';

import vue from '@vitejs/plugin-vue'


export default defineConfig({
    server : {
        hmr : {
            host : '192.168.12.2'
        }
    },
    plugins: [

        vue(),

        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        })
    ],
});
