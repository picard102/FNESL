console.log("[Tailwind] Config loaded");

export default {
  content: ["./theme-src/**/*.{js,jsx,scss,php,html}"],
  theme: {

      colors: {
        primary: {
          400: "#579fb8",
        },
      },

  },
  plugins: [
    function ({ addUtilities }) {
      console.log("[Tailwind] Plugins running");
      addUtilities({});
    },
  ],
};
