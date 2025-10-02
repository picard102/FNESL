// build/postcss.config.cjs
const tailwind = require("@tailwindcss/postcss");
const autoprefixer = require("autoprefixer");
const nesting = require("postcss-nesting");

module.exports = {
  plugins: [ autoprefixer(), nesting()],
};
