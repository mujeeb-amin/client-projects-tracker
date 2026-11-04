
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/studio/vendor.min.css',
                'resources/css/studio/app.min.css',
                'resources/css/studio/custom.css',

                'resources/js/studio/vendor.min.js',
                'resources/js/studio/app.min.js',
            ],
            refresh: true,
        }),
    ],
});
