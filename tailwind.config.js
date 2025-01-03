import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.tsx",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            white: "#ffffff",
            red: "#f87171",
            green: "#4ade80",
            primary: "#3b5998",
            neutral: "#404040",
            "neutral-light": "#e5e5e5",
        },
    },
    daisyui: {
        themes: [
            {
                myTheme: {
                    primary: "#3b5998", // Overrides DaisyUI's primary color
                    secondary: "#f87171", // Add secondary color if needed
                    accent: "#4ade80", // Add accent color if needed
                    neutral: "#404040", // Overrides neutral color
                    info: "#3abff8", // Optional: custom info color
                    success: "#4ade80", // Optional: custom success color
                    warning: "#fbbf24", // Optional: custom warning color
                    error: "#f87171", // Optional: custom error color
                    "neutral-light": "#e5e5e5",
                },
            },
        ],
    },
    plugins: [require("@tailwindcss/typography"), require("daisyui")],
};
