import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Kanit", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: "#ffeb2e", // ใช้ bg-primary
                    light: "#3B82F6", // ใช้ bg-primary-light
                    dark: "#1E3A8A", // ใช้ bg-primary-dark
                },
                secondary: {
                    DEFAULT: "#003a62", // ใช้ bg-primary
                    light: "#3B82F6", // ใช้ bg-primary-light
                    dark: "#1E3A8A", // ใช้ bg-primary-dark
                },
            },
        },
    },

    plugins: [forms],
};
