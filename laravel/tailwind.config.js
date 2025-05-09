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
        },
    },

    plugins: [forms],

    safelist: [
        'text-green-700',
        'border-green-300',
        'bg-green-50',
        'dark:bg-gray-600',
        'dark:border-green-800',
        'dark:text-green-400',
        'text-gray-900',
        'border-gray-300',
        'bg-gray-100',
        'dark:border-gray-700',
        'dark:text-gray-400',
        'text-blue-700',
        'border-blue-300',
        'bg-blue-100',
        'dark:border-blue-800',
        'dark:text-blue-400',
        'w-full', 'border-t-4',
        'rounded-b',
        'shadow-md',
        'p-4',
        'rounded-lg',
        'border',
        'cursor-pointer',
        'text-red-500',
        'text-xs',
        'italic',
        'border-red-500',
    ],
};
