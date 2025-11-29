import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
          input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Pastikan file manifest dihasilkan di folder public/build
        manifest: true,
        outDir: 'public/build', // Tentukan folder untuk menyimpan hasil build
    },
});
