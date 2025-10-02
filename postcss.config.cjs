// build/postcss.config.cjs
const tailwind = require("@tailwindcss/postcss");
const autoprefixer = require("autoprefixer");
const nesting = require("postcss-nesting");

module.exports = {
  plugins: [
    // Debug plugin: logs which file is being processed
    (root, result) => {
      console.log("[PostCSS] Processing:", result.opts.from);
      console.log(
        "[PostCSS] First 200 chars:\n",
        root.toString().slice(0, 200)
      );
    },
    tailwind(), // ✅ Run Tailwind v4
    nesting, // ✅ PostCSS nesting
    autoprefixer(), // ✅ Vendor prefixes
  ],
};
