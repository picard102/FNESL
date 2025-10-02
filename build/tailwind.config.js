console.log("[Tailwind] Config loaded");

export default {
  content: ["../theme-src/**/*.{js,jsx,scss,php,html}"],
  theme: {
    extend: {},
  },
  experimental: {
    applyComplexClasses: true, // 👈 allows @apply for things like bg-red-500
  },
  plugins: [
    function ({ addUtilities }) {
      console.log("[Tailwind] Plugins running");
      addUtilities({});
    },
  ],
};
