import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
             input: [
                'resources/css/app.css',
                'resources/css/doctor.css',   // 👈 Add this line
                'resources/css/doctor-sidebar.css', // 👈 And this line
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
