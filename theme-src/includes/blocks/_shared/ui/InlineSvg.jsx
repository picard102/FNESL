import { useEffect, useMemo, useState } from "@wordpress/element";

const SVG_CACHE = new Map();

function sanitizeSvg(svg) {
  if (!svg) return "";

  // Remove <script> blocks + on* handlers
  svg = svg.replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, "");
  svg = svg.replace(/\son\w+="[^"]*"/gi, "");
  svg = svg.replace(/\son\w+='[^']*'/gi, "");

  // Remove <style> blocks
  svg = svg.replace(/<style[\s\S]*?>[\s\S]*?<\/style>/gi, "");

  // Remove inline style=""
  svg = svg.replace(/\sstyle=(["'])([\s\S]*?)\1/gi, "");

  // Remove solid fills (keep none/currentColor/gradients)
  svg = svg.replace(
    /\sfill=(["'])(?!none|currentColor|url\()([\s\S]*?)\1/gi,
    "",
  );

  // Remove solid strokes (keep none/currentColor/gradients)
  svg = svg.replace(
    /\sstroke=(["'])(?!none|currentColor|url\()([\s\S]*?)\1/gi,
    "",
  );

  return svg.trim();
}

function injectClassIntoSvg(svg, className) {
  if (!svg) return "";

  // Ensure there's an <svg ...> root
  if (!/<svg\b/i.test(svg)) return "";

  // If svg already has a class attr, merge; otherwise add.
  if (/<svg\b[^>]*\bclass=([\'"])/i.test(svg)) {
    return svg.replace(
      /(<svg\b[^>]*\bclass=)([\'"])(.*?)(\2)/i,
      (m, start, quote, existing, endQuote) => {
        const merged = `${existing} ${className}`.trim();
        return `${start}${quote}${merged}${endQuote}`;
      },
    );
  }

  return svg.replace(/<svg\b/i, `<svg class="${className}"`);
}

export function InlineSvg({ url, className = "" }) {
  const [raw, setRaw] = useState("");

  useEffect(() => {
    let alive = true;

    if (!url) {
      setRaw("");
      return;
    }

    if (SVG_CACHE.has(url)) {
      setRaw(SVG_CACHE.get(url));
      return;
    }

    fetch(url, { credentials: "same-origin" })
      .then((r) => (r.ok ? r.text() : ""))
      .then((text) => {
        const cleaned = sanitizeSvg(text);
        SVG_CACHE.set(url, cleaned);
        if (alive) setRaw(cleaned);
      })
      .catch(() => {
        if (alive) setRaw("");
      });

    return () => {
      alive = false;
    };
  }, [url]);

  const markup = useMemo(
    () => injectClassIntoSvg(raw, className),
    [raw, className],
  );

  if (!markup) return null;

  return (
    <span
      aria-hidden="true"
      className="inline-flex"
      dangerouslySetInnerHTML={{ __html: markup }}
    />
  );
}
