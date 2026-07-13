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
                // Overwrite indigo dengan warna khas Shopee Orange
                indigo: {
                    50: '#ffeee8',
                    100: '#ffd0c4',
                    200: '#ffb09e',
                    300: '#ff8a72',
                    400: '#ff6245',
                    500: '#f05335',
                    600: '#ee4d2d', // Shopee Orange
                    700: '#d44327',
                    800: '#b0341c',
                    900: '#8c2815',
                    950: '#6b1c0d',
                }
            }
        },
    },

    plugins: [forms],
};
