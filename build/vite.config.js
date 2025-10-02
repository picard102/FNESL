import { defineConfig } from "vite";
import path from "path";
import react from "@vitejs/plugin-react";

// Recreate __dirname for path.resolve
import { fileURLToPath } from "url";
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename); // <-- This is the /build directory

console.log("Vite build running from:", __dirname);


export default defineConfig({
  plugins: [react()],
  root: path.resolve(__dirname, "../theme-src"),
  base: "",
  css: {
    postcss: path.resolve(__dirname, "./postcss.config.cjs"),
  },

  build: {
    outDir: path.resolve(__dirname, "../theme-dist/assets"),
    emptyOutDir: false,
    manifest: true,
    rollupOptions: {
      input: {
        theme: path.resolve(__dirname, "../theme-src/js/theme.entry.js"),
      },
    },
  },
});
