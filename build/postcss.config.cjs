// Change from import/export to CommonJS require/module.exports
const tailwind = require("@tailwindcss/postcss");
const autoprefixer = require("autoprefixer");
const nesting = require("postcss-nesting");

module.exports = {
  plugins: [tailwind(), autoprefixer(), nesting()],
};
