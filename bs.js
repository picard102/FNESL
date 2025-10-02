import browserSync from "browser-sync";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const srcDir = path.resolve(__dirname, "theme-src");

console.log("[bs.js] Watching srcDir:", srcDir);

const bs = browserSync.create();

bs.init({
  proxy: "fnesl.local",
  https: {
    key: "/Applications/MAMP/Library/OpenSSL/certs/fnesl.local.key",
    cert: "/Applications/MAMP/Library/OpenSSL/certs/fnesl.local.crt",
  },
  files: [
    path.join(srcDir, "**/*.php"),
    path.join(srcDir, "**/*.js"),
    path.join(srcDir, "**/*.css"),
    path.join(srcDir, "**/*.{jpg,jpeg,png,gif,webp,svg,html}"),
  ],
  reloadDelay: 500, // wait 500ms before reloading

  reloadDebounce: 500,
  injectChanges: false, // do full reload except CSS hot-injection
  open: false,
  port: 2519, // 👈 fixed port for the client script
});
