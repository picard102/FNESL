import browserSync from "browser-sync";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const srcDir = path.resolve(__dirname, "theme-src");
const distDir = path.resolve(__dirname, "theme-dist");

// ✅ mkcert files (relative to this bs.js file)
const certPath = path.resolve(__dirname, "./fnesl.ddev.site+2.pem");
const keyPath = path.resolve(__dirname, "./fnesl.ddev.site+2-key.pem");

console.log("[bs.js] Watching srcDir:", srcDir);
console.log("[bs.js] Watching distDir:", distDir);
console.log("[bs.js] Using cert:", certPath);
console.log("[bs.js] Using key:", keyPath);

const bs = browserSync.create();

bs.init({
  proxy: "https://fnesl.ddev.site",

  // ✅ Trusted HTTPS (mkcert)
  https: {
    cert: certPath,
    key: keyPath,
  },

  files: [
    path.join(srcDir, "**/*.php"),
    path.join(srcDir, "**/*.{js,jsx,css,scss}"),
    path.join(srcDir, "**/*.css"),
    path.join(srcDir, "**/*.{jpg,jpeg,png,gif,webp,svg,html}"),
    path.join(distDir, "**/*.php"),
    path.join(distDir, "**/*.{js,css}"),
  ],
  watchOptions: {
    ignoreInitial: true,
    usePolling: true,
    interval: 300,
  },

  reloadDelay: 500,
  reloadDebounce: 500,
  injectChanges: false,
  open: false,
  port: 2519,
});
