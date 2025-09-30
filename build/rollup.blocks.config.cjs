const path = require("path");
const fg = require("fast-glob");
const resolve = require("@rollup/plugin-node-resolve");
const commonjs = require("@rollup/plugin-commonjs");
const babel = require("@rollup/plugin-babel").babel;

// Root paths
const srcRoot = path.resolve(__dirname, "../theme-src/includes/blocks");
const outRoot = path.resolve(__dirname, "../theme-dist/includes/blocks");

// Discover all block entrypoints
const entries = fg
  .sync("**/index.jsx", { cwd: srcRoot })
  .map((file) => path.resolve(srcRoot, file));

module.exports = {
  input: entries,
  output: {
    dir: outRoot,
    format: "iife", // browser-friendly
    sourcemap: true,
    globals: {
      "@wordpress/blocks": "wp.blocks",
      "@wordpress/element": "wp.element",
      "@wordpress/components": "wp.components",
      "@wordpress/block-editor": "wp.blockEditor",
      "@wordpress/i18n": "wp.i18n",
      "@wordpress/data": "wp.data",
      "@wordpress/editor": "wp.editor",
      "@wordpress/icons": "wp.icons",
    },
    entryFileNames: (chunkInfo) => {
      // preserve folder structure
      const relPath = path.relative(
        srcRoot,
        chunkInfo.facadeModuleId.replace(/\.jsx$/, "")
      );
      return `${relPath}.js`;
    },
  },
  plugins: [
    resolve({
      extensions: [".js", ".jsx"],
    }),
    commonjs(),
    babel({
      // 🚀 FIX: Revert to @babel/preset-react but explicitly force classic runtime
      presets: [
        [
          // Using the standard preset, as the WP preset causes conflicts
          "@babel/preset-react",
          {
            // CRUCIAL: Forces JSX to be compiled to React.createElement(name, props, children)
            runtime: "classic",


          },
        ],
      ],
      // 🚀 FIX: Switch back to 'bundled' helpers for a simpler build
      babelHelpers: "bundled",

      extensions: [".js", ".jsx"],
      // Keep this to prevent processing node_modules
      exclude: "node_modules/**",
    }),
  ],
  external: [
    "@wordpress/blocks",
    "@wordpress/element",
    "@wordpress/components",
    "@wordpress/block-editor",
    "@wordpress/i18n",
    "@wordpress/data",
    "@wordpress/editor",
    "@wordpress/icons",
  ],
};