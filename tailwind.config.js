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
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                botanical: {
                    50: '#f4f7f5',
                    100: '#e4ece6',
                    200: '#c9dad0',
                    300: '#a3c0b0',
                    400: '#76a18b',
                    500: '#54856f',
                    600: '#406a58',
                    700: '#345547',
                    800: '#2b443a',
                    900: '#263932',
                    950: '#13201c',
                },
            },
            boxShadow: {
                'paper-inner': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
                'paper-layered': '-16px 0 28px -10px rgba(0, 0, 0, 0.25), -6px 0 12px -4px rgba(0, 0, 0, 0.15)',
                'card-premium': '0 25px 60px -15px rgba(18, 38, 28, 0.22), 0 0 1px 1px rgba(255, 255, 255, 0.8)',
                'glass-pill': '0 8px 32px 0 rgba(31, 38, 135, 0.15)',
            },
        },
    },

    plugins: [forms],
};
