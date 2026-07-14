import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Overwrite indigo dengan warna khas Blibli Blue
                indigo: {
                    50: '#e6f4fb',
                    100: '#cceafd',
                    200: '#99d4fb',
                    300: '#66bef9',
                    400: '#33a7f7',
                    500: '#0095DA', // Blibli Blue Primary
                    600: '#0082be',
                    700: '#006fa3',
                    800: '#005c87',
                    900: '#004b6d',
                    950: '#003852',
                },
                // Overwrite orange dengan warna khas Blibli Yellow
                orange: {
                    50: '#fff9e6',
                    100: '#fff0b3',
                    200: '#ffe780',
                    300: '#ffdd4d',
                    400: '#ffd31a',
                    500: '#FFC72C', // Blibli Yellow
                    600: '#e6b01b',
                    700: '#cc9a14',
                    800: '#99730f',
                    900: '#664c0a',
                    950: '#332605',
                }
            }
        },
    },

    plugins: [forms],
};
