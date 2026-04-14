import {
  createRoot,
  useCallback,
  useEffect,
  useRef,
  useState,
} from "@wordpress/element";

const WIDE = "var(--wp--style--global--content-size, 72rem)";
const GUTTER = "var(--wp--style--root--padding-left, 1rem)";
const PADDING = `max(${GUTTER}, calc((100vw - min(100vw, ${WIDE})) / 2 + ${GUTTER}))`;

function clamp(n, min, max) {
  return Math.max(min, Math.min(max, n));
}

function AffiliationCard({ item }) {
  return (
    <article className="bg-white rounded-sm p-6 grid grid-cols-[1fr_2fr] gap-6 h-full">
      <div className="flex items-center justify-center">
        {item.url ? (
          <a
            href={item.url}
            target="_blank"
            rel="noopener noreferrer"
            className="block w-full"
            dangerouslySetInnerHTML={{ __html: item.logo_html || "" }}
          />
        ) : (
          <div dangerouslySetInnerHTML={{ __html: item.logo_html || "" }} />
        )}
      </div>

      <div className="flex flex-col justify-center items-start self-start">
        <h3 className="text-xl mb-2">{item.title || ""}</h3>
        <div
          className="text-sm text-primary-900"
          dangerouslySetInnerHTML={{ __html: item.description_html || "" }}
        />

        {item.url && (
          <a
            href={item.url}
            className="mt-6 text-sm text-primary-500 flex gap-3"
            target="_blank"
            rel="noopener noreferrer"
          >
            <span className="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
              <svg className="aspect-square h-3 fill-current" aria-hidden="true">
                <use xlinkHref="#icons_arrow_east"></use>
              </svg>
            </span>
            Visit Website
          </a>
        )}
      </div>
    </article>
  );
}

function App({ items }) {
  const trackRef = useRef(null);
  const [atStart, setAtStart] = useState(true);
  const [atEnd, setAtEnd] = useState(false);

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
    const step = first
      ? first.getBoundingClientRect().width + gap
      : track.clientWidth;

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
    if (!track || !items.length) return;
    updateState();
    track.addEventListener("scroll", updateState, { passive: true });
    const ro = new ResizeObserver(updateState);
    ro.observe(track);
    window.addEventListener("load", updateState, { once: true });

    return () => {
      track.removeEventListener("scroll", updateState);
      ro.disconnect();
    };
  }, [items, updateState]);

  if (!items.length) return null;

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
          aria-label="Previous affiliations"
        >
          <span aria-hidden="true">‹</span>
        </button>
        <button
          type="button"
          className="inline-flex h-10 w-10 items-center justify-center rounded-full border-2 border-current text-current focus:outline-none ring-offset-1 focus:ring-2 disabled:opacity-40 disabled:cursor-not-allowed"
          onClick={() => scrollByStep(1)}
          disabled={atEnd}
          aria-label="Next affiliations"
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
          aria-label="Affiliations"
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
          {items.map((item) => (
            <li
              key={item.id}
              className="snap-start shrink-0 w-[min(90vw,720px)] lg:w-[720px] list-none"
            >
              <AffiliationCard item={item} />
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

function mountAll() {
  document.querySelectorAll("[data-affiliations-carousel]").forEach((el) => {
    const config = JSON.parse(el.getAttribute("data-config") || "{}");
    createRoot(el).render(<App items={config.items || []} />);
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountAll, { once: true });
} else {
  mountAll();
}
