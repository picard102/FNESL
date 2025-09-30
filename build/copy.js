import fs from "fs-extra";
import path from "path";
import chokidar from "chokidar";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const srcDir = path.resolve(__dirname, "../theme-src");
const distDir = path.resolve(__dirname, "../theme-dist");

const excludeDirs = [
  "scss",
  "js",
  path.join("assets", "icons"),
  ".vite",
  "deps_temp_folders",
];
const excludePatterns = [/\.timestamp-/];

function shouldCopy(src) {
  const rel = path.relative(srcDir, src);
  if (!rel) return true; // root
  if (
    rel.startsWith("scss") ||
    rel.startsWith("js") ||
    rel.startsWith("assets/icons")
  ) {
    return false;
  }
  return true;
}

async function copyAll() {
  await fs.copy(srcDir, distDir, { filter: shouldCopy });
  console.log("✅ Static files copied");
}

async function copyOne(filePath) {
  const rel = path.relative(srcDir, filePath);
  if (!shouldCopy(filePath)) return;
  const destPath = path.join(distDir, rel);
  await fs.copy(filePath, destPath);
  console.log(`➕ Updated: ${rel}`);
}

async function run() {
  console.log("🚀 copy.js starting…");
  try {
    await copyAll(); // initial copy
  } catch (err) {
    console.error("❌ Initial copy failed:", err);
  }

  if (process.argv.includes("--watch")) {
    console.log("👀 Watching static files in", srcDir);
    chokidar
      .watch(srcDir, { ignoreInitial: true })
      .on("all", async (event, filePath) => {
        const rel = path.relative(srcDir, filePath);
        try {
          if (event === "unlink" || event === "unlinkDir") {
            const destPath = path.join(distDir, rel);
            await fs.remove(destPath);
            console.log(`🗑️ Removed: ${rel}`);
          } else {
            await copyOne(filePath);
          }
        } catch (err) {
          console.error(`❌ Error handling ${rel}:`, err);
        }
      });
  }
}

run();
