import { defineConfig } from "vite";
import path from "path";
import { fileURLToPath } from "url";

// Recreate __dirname in ESM
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
  root: path.resolve(__dirname, "../theme-src"),
  base: "",
  build: {
    outDir: path.resolve(__dirname, "../theme-dist"),
    emptyOutDir: false,
    manifest: true,
    rollupOptions: {
      input: {
        theme: path.resolve(__dirname, "../theme-src/js/theme.entry.js"),
      },
    },
  },
});