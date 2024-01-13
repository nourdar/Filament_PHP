import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin'; // <-- import

export default defineConfig({
    // build: {
    //     outDir: './public_html/build/'
    // },

    plugins: [
        laravel({
            publicDirectory: 'public_html',
            input: [
                'resources/css/app.css',

                'resources/js/app.js',

            ],
            refresh: false,


        }),

        livewire({  // <-- add livewire plugin
            refresh: ['resources/css/app.css'],  // <-- will refresh css (tailwind ) as well
        }),
    ],
});
