import { defineConfig } from "vite";
import path from 'node:path';
import symfonyPlugin from "vite-plugin-symfony";
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

/* if you're using React */
// import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        /* react(), // if you're using React */
        symfonyPlugin(),
        vue(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, './assets')
        }
    },
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js"
            },
        }
    },
});
