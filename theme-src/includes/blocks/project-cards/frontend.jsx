import {
  createRoot,
  useRef,
  useEffect,
  useState,
  useCallback,
} from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { ProjectCard } from "../_shared/ui/ProjectCard";

const WIDE = "var(--wp--style--global--content-size, 72rem)";
const GUTTER = "var(--wp--style--root--padding-left, 1rem)";
const PADDING = `max(${GUTTER}, calc((100vw - min(100vw, ${WIDE})) / 2 + ${GUTTER}))`;

function clamp(n, min, max) {
  return Math.max(min, Math.min(max, n));
}

function App({ config }) {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const trackRef = useRef(null);
  const [atStart, setAtStart] = useState(true);
  const [atEnd, setAtEnd] = useState(false);

  useEffect(() => {
    apiFetch({
      path: "/fnesl/v1/project-archive",
      method: "POST",
      data: { perPage: config.perPage || 12, includeFilters: false },
    })
      .then((data) =>
        setProjects(Array.isArray(data?.projects) ? data.projects : [])
      )
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const updateState = useCallback(() => {
    const track = trackRef.current;
    if (!track) return;
    const eps = 2;
    const max = track.scrollWidth - track.clientWidth;
    setAtStart(track.scrollLeft <= eps);
    setAtEnd(track.scrollLeft >= max - eps);
  }, []);

  const scrollByStep = useCallback((dir) => {
    const track = trackRef.current;
    if (!track) return;
    const first = track.querySelector(":scope > li");
    const gap = first
      ? parseFloat(getComputedStyle(track).columnGap || "0") || 0
      : 0;
    const step = first ? first.getBoundingClientRect().width + gap : track.clientWidth;
    track.scrollTo({
      left: clamp(
        track.scrollLeft + dir * step,
        0,
        track.scrollWidth - track.clientWidth
      ),
      behavior: "smooth",
    });
  }, []);

  useEffect(() => {
    const track = trackRef.current;
    if (!track || !projects.length) return;
    updateState();
    track.addEventListener("scroll", updateState, { passive: true });
    const ro = new ResizeObserver(updateState);
    ro.observe(track);
    window.addEventListener("load", updateState, { once: true });
    return () => {
      track.removeEventListener("scroll", updateState);
      ro.disconnect();
    };
  }, [projects, updateState]);

  if (loading || !projects.length) return null;

  return (
    <div className="w-full">
      <div
        className="mx-auto mb-3 flex items-center justify-end gap-2"
        style={{ maxWidth: WIDE, paddingInline: GUTTER }}
      >
        <button
          type="button"
          className="inline-flex h-10 w-10 items-center justify-center rounded-full border-2 border-current text-current focus:outline-none focus:ring-2 ring-offset-1 disabled:opacity-40 disabled:cursor-not-allowed"
          onClick={() => scrollByStep(-1)}
          disabled={atStart}
          aria-label="Previous projects"
        >
          <span aria-hidden="true">‹</span>
        </button>
        <button
          type="button"
          className="inline-flex h-10 w-10 items-center justify-center rounded-full border-2 border-current text-current focus:outline-none ring-offset-1 focus:ring-2 disabled:opacity-40 disabled:cursor-not-allowed"
          onClick={() => scrollByStep(1)}
          disabled={atEnd}
          aria-label="Next projects"
        >
          <span aria-hidden="true">›</span>
        </button>
      </div>

      <div className="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen overflow-visible">
        <ul
          ref={trackRef}
          className="flex gap-6 py-3 overflow-x-auto snap-x snap-mandatory [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden outline-none"
          style={{
            paddingLeft: PADDING,
            paddingRight: PADDING,
            scrollPaddingLeft: PADDING,
            scrollPaddingRight: PADDING,
          }}
          tabIndex={0}
          role="region"
          aria-label="Projects"
          onKeyDown={(e) => {
            if (e.key === "ArrowLeft") {
              e.preventDefault();
              scrollByStep(-1);
            } else if (e.key === "ArrowRight") {
              e.preventDefault();
              scrollByStep(1);
            } else if (e.key === "Home") {
              e.preventDefault();
              trackRef.current?.scrollTo({ left: 0, behavior: "smooth" });
            } else if (e.key === "End") {
              e.preventDefault();
              trackRef.current?.scrollTo({
                left: trackRef.current.scrollWidth,
                behavior: "smooth",
              });
            }
          }}
        >
          {projects.map((p) => (
            <li
              key={p.id}
              className="snap-start shrink-0 w-[280px] sm:w-[320px] lg:w-[350px]"
            >
              <ProjectCard project={p} />
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

function mountAll() {
  document.querySelectorAll("[data-project-cards]").forEach((el) => {
    const config = JSON.parse(el.getAttribute("data-config") || "{}");
    createRoot(el).render(<App config={config} />);
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountAll, { once: true });
} else {
  mountAll();
}
