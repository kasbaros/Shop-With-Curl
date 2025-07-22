// import {
//     defineConfig
// } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import tailwindcss from "@tailwindcss/vite";
//
// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//         tailwindcss(),
//     ],
//     server: {
//         cors: true,
//     },
//     build: {
//         outDir: 'public/build',
//         manifest: true,
//     },
// });


import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: 'public',
            buildDirectory: 'build',
        }),
    ],
    build: {
        outDir: 'public/build',
        manifest: 'manifest.json', // Explicitly set manifest file name
        emptyOutDir: true, // Clear build directory before generating
    },
});
