import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#4F46E5',
                    dark: '#3730A3',
                },
                secondary: '#0EA5E9',
                success: '#10B981',
                warning: '#F59E0B',
                danger: '#EF4444',
                dark: '#1E293B',
                light: '#F8FAFC',
            },
        },
    },

    plugins: [forms],
};
