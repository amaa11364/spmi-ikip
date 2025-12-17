import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Hanya CSS jika perlu
                'resources/js/bootstrap-only.js' // Hanya untuk Bootstrap JS
            ],
            refresh: true,
        }),
    ],
});