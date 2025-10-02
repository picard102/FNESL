// rollup.blocks.config.mjs
import path from "path";
import fg from "fast-glob";
import resolve from "@rollup/plugin-node-resolve";
import commonjs from "@rollup/plugin-commonjs";
import babel from "@rollup/plugin-babel";
import fs from "fs";

import tailwind from "@tailwindcss/postcss";
import autoprefixer from "autoprefixer";
import { fileURLToPath } from "url";

import postcss from "rollup-plugin-postcss";
import postcssConfig from "./postcss.config.cjs"; // ✅ load shared config


// Recreate __dirname in ESM
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// ✅ Since config is now at project root:
const srcRoot = path.resolve(__dirname, "theme-src/includes/blocks");
const outRoot = path.resolve(__dirname, "theme-dist/includes/blocks");

console.log("[Rollup] Config loaded from:", __dirname);
console.log("[Rollup] srcRoot:", srcRoot);
console.log("[Rollup] outRoot:", outRoot);

// WP externals
const wpExternals = [
  "wp-blocks",
  "wp-element",
  "wp-i18n",
  "wp-components",
  "wp-block-editor",
];

// Generate asset.php alongside each block bundle
function assetPhpPlugin() {
  return {
    name: "asset-php",
    generateBundle(options, bundle) {
      for (const [fileName] of Object.entries(bundle)) {
        if (!fileName.endsWith(".js")) continue;

        const outFile = options.file
          ? options.file
          : path.resolve(options.dir, fileName);

        const assetPath = outFile.replace(/\.js$/, ".asset.php");
        const version = Date.now();
        const content = `<?php return array(
  'dependencies' => array(
    'wp-blocks',
    'wp-element',
    'wp-i18n',
    'wp-components',
    'wp-block-editor'
  ),
  'version' => '${version}',
);`;

        fs.mkdirSync(path.dirname(assetPath), { recursive: true });
        fs.writeFileSync(assetPath, content, "utf8");
        console.log(`📝 Wrote asset file: ${assetPath}`);
      }
    },
  };
}

export default () => {
  // ✅ Glob relative to srcRoot now
  const entryFiles = fg.sync("**/index.@(js|jsx)", { cwd: srcRoot });

  console.log("📦 Found blocks:", entryFiles);

  if (!entryFiles.length) {
    console.warn("[Rollup] ⚠️ No block entry files found in:", srcRoot);
  }

  return entryFiles.map((f) => {
    const blockDir = path.dirname(f);

    console.log(`[Rollup] Building block: ${blockDir}, entry: ${f}`);

    return {
      input: path.resolve(srcRoot, f),
      output: {
        file: path.resolve(outRoot, `${blockDir}/index.js`),
        format: "iife",
        sourcemap: true,
        globals: {
          "@wordpress/blocks": "wp.blocks",
          "@wordpress/element": "wp.element",
          "@wordpress/components": "wp.components",
          "@wordpress/block-editor": "wp.blockEditor",
          "@wordpress/i18n": "wp.i18n",
        },
      },
      external: wpExternals,
      plugins: [
        resolve({ extensions: [".js", ".jsx"], browser: true }),
        commonjs(),
        babel({
          presets: [["@babel/preset-react", { runtime: "classic" }]],
          babelHelpers: "bundled",
          extensions: [".js", ".jsx"],
        }),
        postcss({
          extract: true,
          minimize: true,
          sourceMap: true,
          extensions: [".css", ".scss"],
          use: ["sass"],
          plugins: [
            (root, result) => {
              console.log(
                "[Rollup/PostCSS] Processing block CSS:",
                result.opts.from
              );
              console.log(
                "[Rollup/PostCSS] First 200 chars:",
                root.toString().slice(0, 200)
              );
            },
            ...postcssConfig.plugins,
          ],
        }),
        assetPhpPlugin(),
      ],
    };
  });
};
