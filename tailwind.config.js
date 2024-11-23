import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		 './storage/framework/views/*.php',
		 './resources/views/**/*.blade.php',
		 "./vendor/robsontenorio/mary/src/View/Components/**/*.php"
	],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            'navbar': '#fce969',
            "brand": {
                50: "#FFFDF0",
                100: "#FEFAE1",
                200: "#FEF6C3",
                300: "#FDF1A5",
                400: "#FDED87",
                500: "#FCE969",
                600: "#FBDE23",
                700: "#D2B704",
                800: "#8C7A03",
                900: "#463D01",
                950: "#231E01"
            },
            "brand-yellow": "#fce969",
            "brand-cyan": "#588B8B",
            "brand-tangerine": "#F28F3B",
            "brand-jasper": "#C8553D",
            "brand-purple": "E8D7FF"

        }
    },

    plugins: [
		forms,
		require("daisyui")
	],
};
