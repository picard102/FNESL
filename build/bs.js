import browserSync from "browser-sync";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const bs = browserSync.create();

bs.init({
  proxy: "fnesl.local",
  https: {
    key: "/Applications/MAMP/Library/OpenSSL/certs/fnesl.local.key",
    cert: "/Applications/MAMP/Library/OpenSSL/certs/fnesl.local.crt",
  },
  files: [
    "../theme-dist/**/*.php",
    "../theme-dist/**/*.js",
    "../theme-dist/**/*.css",
    "../theme-dist/**/*.{jpg,jpeg,png,gif,webp,svg,html}",
  ],
  reloadDebounce: 500,
  injectChanges: false, // do full reload except CSS hot-injection
  open: false,
  port: 2519, // 👈 fixed port for the client script
});
