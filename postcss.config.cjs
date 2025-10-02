// build/postcss.config.cjs
const tailwind = require("@tailwindcss/postcss");
const autoprefixer = require("autoprefixer");
const nesting = require("postcss-nesting");

module.exports = {
  plugins: [
    // Debug plugin: logs which file is being processed
    (root, result) => {
      console.log("[PostCSS] Processing:", result.opts.from);
    },
    tailwind(), // ✅ Run Tailwind v4
    nesting, // ✅ PostCSS nesting
    autoprefixer(), // ✅ Vendor prefixes
  ],
};
