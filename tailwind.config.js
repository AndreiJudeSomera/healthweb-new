import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",

    "./resources/js/**/*.js",
    "./resources/js/**/*.ts",
    "./resources/js/**/*.vue",
    "./resources/js/**/*.jsx",
    "./resources/js/**/*.tsx",
  ],

  safelist: [
    "border-blue-950",
    "text-blue-950",
    "hover:bg-indigo-100",

    "border-amber-800",
    "text-amber-800",
    "hover:bg-amber-100",

    "border-red-800",
    "text-red-800",
    "hover:bg-red-100",

    "bg-gray-500",
    "opacity-50",
    "opacity-25",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans],
        roboto: ["Roboto", "sans-serif"],
      },
    },
  },
};
