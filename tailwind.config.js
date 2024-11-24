import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#fce969",
                    "primary-content": "#041509",
                    "secondary": "#ce89fb",
                    "secondary-content": "#0f0715",
                    "accent": "#f5c173",
                    "accent-content": "#150e05",
                    "neutral": "#262931",
                    "neutral-content": "#cfd0d2",
                    "base-100": "#172f3c",
                    "base-200": "#122733",
                    "base-300": "#0e202a",
                    "base-content": "#ccd1d5",
                    "info": "#2563EB",
                    "info-content": "#d2e2ff",
                    "success": "#16A34A",
                    "success-content": "#000a02",
                    "warning": "#D97706",
                    "warning-content": "#110500",
                    "error": "#922626",
                    "error-content": "#ffd9d4"
                }
            },
            "light", "dark", "cupcake"],
    },
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
            "brand-purple": "#E8D7FF"

        }
    },

    plugins: [
		forms,
		require("daisyui"),
        require('flowbite/plugin')
	],
};
