/** @type {import('tailwindcss').Config} */
export default {
  content: [],
  darkMode: "class",
  theme: {
    extend: {
      transitionProperty: {
        "border-bg": "border-color, background-color",
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
};
