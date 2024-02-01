/** @type {import('tailwindcss').Config} */




export default {


    content: [

        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        './storage/framework/views/*.php',
        './vendor/usernotnull/tall-toasts/config/**/*.php',
        './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}

