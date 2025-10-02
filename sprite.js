import path from "path";
import fs from "fs";
import chokidar from "chokidar";
import { fileURLToPath } from "url";
import SVGSpriter from "svg-sprite";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const svgInputDir = path.resolve(__dirname, "../theme-src/assets/icons");
const svgOutputDir = path.resolve(__dirname, "../theme-dist/assets");

function buildSprite() {
  fs.mkdirSync(svgOutputDir, { recursive: true });

  if (!fs.existsSync(svgInputDir)) {
    console.warn(`⚠️ No SVG icons folder found: ${svgInputDir}`);
    return;
  }

  const spriter = new SVGSpriter({
    dest: svgOutputDir,
    mode: { symbol: { sprite: "../sprite.svg", example: true } },
    shape: { id: { generator: (name) => path.basename(name, ".svg") } },
  });

  fs.readdirSync(svgInputDir).forEach((file) => {
    if (file.endsWith(".svg")) {
      const fullPath = path.join(svgInputDir, file);
      spriter.add(fullPath, file, fs.readFileSync(fullPath, "utf8"));
    }
  });

  spriter.compile((error, result) => {
    if (error) {
      console.error("❌ Failed to generate SVG sprite:", error);
    } else {
      for (const mode in result) {
        for (const resource in result[mode]) {
          const outputPath = result[mode][resource].path;
          fs.mkdirSync(path.dirname(outputPath), { recursive: true });
          fs.writeFileSync(outputPath, result[mode][resource].contents);
          console.log(`✅ SVG sprite written: ${outputPath}`);
        }
      }
    }
  });
}

function run() {
  console.log("🚀 sprite.js starting…");
  buildSprite(); // initial build

  if (process.argv.includes("--watch")) {
    console.log("👀 Watching SVG icons in", svgInputDir);
    chokidar.watch(svgInputDir, { ignoreInitial: true }).on("all", () => {
      console.log("♻️ Rebuilding sprite…");
      buildSprite();
    });
  }
}

run();
