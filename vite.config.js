// vite.config.js (now at project root: /Sites/fnesl/vite.config.js)
import { defineConfig } from "vite";
import path from "path";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";

console.log("✅ Vite running with root at theme-src");

export default defineConfig({
  root: path.resolve(__dirname, "theme-src"),
  plugins: [
    react(),
    tailwindcss({
      // Tailwind config will be auto-detected since it's also at project root
      config: path.resolve(__dirname, "tailwind.config.js"),
    }),
  ],
  base: "",
  build: {
    outDir: path.resolve(__dirname, "theme-dist"),
    emptyOutDir: false, // 🚀 prevents clearing dist
    manifest: true,
    rollupOptions: {
      input: {
        theme: path.resolve(__dirname, "theme-src/js/theme.entry.js"),
        banner: path.resolve(__dirname, "theme-src/css/banner.entry.css"),
      },
    },
  },
});
