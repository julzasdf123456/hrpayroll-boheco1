import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import inject from '@rollup/plugin-inject';

import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [

        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => ['draggable'].includes(tag),
                }
            }
        }),

        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        })
    ],
});
