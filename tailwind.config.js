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
                sans: ['Hanken Grotesk', ...defaultTheme.fontFamily.sans],
                display: ['Hanken Grotesk', ...defaultTheme.fontFamily.sans],
                mono: ['Space Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                navy: {
                    DEFAULT: '#0A2640',
                    50: '#E8EDF2',
                    100: '#D1DBE5',
                    200: '#A3B7CB',
                    300: '#7593B1',
                    400: '#476F97',
                    500: '#1A4B7D',
                    600: '#143D66',
                    700: '#0F2F4F',
                    800: '#0A2640',
                    900: '#061829',
                },
                accent: {
                    DEFAULT: '#E8663D',
                    50: '#FEF3EE',
                    100: '#FDE7DD',
                    200: '#FBCFBB',
                    300: '#F9B799',
                    400: '#F08E6B',
                    500: '#E8663D',
                    600: '#D04E25',
                    700: '#A33D1D',
                    800: '#762C15',
                    900: '#491B0D',
                },
                slate: {
                    DEFAULT: '#2C3F54',
                    400: '#8693A8',
                    500: '#51607A',
                    700: '#2C3F54',
                },
                clinical: '#42A070',
            },
            borderRadius: {
                DEFAULT: '5px',
                sm: '3px',
                md: '5px',
                lg: '8px',
                xl: '5px',
                '2xl': '5px',
                'pill': '9999px',
            },
        },
    },

    plugins: [forms],
};
