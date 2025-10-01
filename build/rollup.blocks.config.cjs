// rollup.config.js
import path from "path";
import fg from "fast-glob";
import resolve from "@rollup/plugin-node-resolve";
import commonjs from "@rollup/plugin-commonjs";
import { babel } from "@rollup/plugin-babel";

const srcRoot = path.resolve(__dirname, "../theme-src/includes/blocks");
const outRoot = path.resolve(__dirname, "../theme-dist/includes/blocks");

// find all block entry files
const entries = fg.sync("**/index.jsx", { cwd: srcRoot }).map((f) => {
  return {
    input: path.resolve(srcRoot, f),
    output: {
      file: path.resolve(outRoot, f.replace(/\.jsx$/, ".js")),
      format: "iife",
      sourcemap: true,
      globals: {
        "@wordpress/blocks": "wp.blocks",
        "@wordpress/element": "wp.element",
        "@wordpress/components": "wp.components",
        "@wordpress/block-editor": "wp.blockEditor",
        "@wordpress/i18n": "wp.i18n",
        "@wordpress/data": "wp.data",
        "@wordpress/editor": "wp.editor",
      },
    },
    external: [
      "@wordpress/blocks",
      "@wordpress/element",
      "@wordpress/components",
      "@wordpress/block-editor",
      "@wordpress/i18n",
      "@wordpress/data",
      "@wordpress/editor",
    ],
    plugins: [
      resolve({ extensions: [".js", ".jsx"], browser: true }),
      commonjs(),
      babel({
        presets: ["@babel/preset-react"],
        babelHelpers: "bundled",
        extensions: [".js", ".jsx"],
      }),
    ],
  };
});

export default entries;
