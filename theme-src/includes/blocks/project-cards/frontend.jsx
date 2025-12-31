(() => {
  const SELECTOR = {
    root: "[data-carousel]",
    track: "[data-carousel-track]",
    prev: "[data-carousel-prev]",
    next: "[data-carousel-next]",
  };

  const clamp = (n, min, max) => Math.max(min, Math.min(max, n));

  function getScrollState(track) {
    // Use a small epsilon because scrollLeft rarely lands on exact integers with smooth scrolling
    const eps = 2;
    const max = track.scrollWidth - track.clientWidth;
    const left = track.scrollLeft;

    return {
      max: Math.max(0, max),
      atStart: left <= eps,
      atEnd: left >= max - eps,
    };
  }

  function getStep(track) {
    // Find the first <li> and use its width + gap as the scroll step
    const first = track.querySelector(":scope > li");
    if (!first) return track.clientWidth;

    const firstRect = first.getBoundingClientRect();
    const styles = getComputedStyle(track);
    const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;

    // If cards are variable width, this still feels right in practice
    return firstRect.width + gap;
  }

  function updateButtons(track, prevBtn, nextBtn) {
    const { atStart, atEnd } = getScrollState(track);
    if (prevBtn) prevBtn.disabled = atStart;
    if (nextBtn) nextBtn.disabled = atEnd;
  }

  function scrollByStep(track, dir) {
    const step = getStep(track);
    const target = clamp(
      track.scrollLeft + dir * step,
      0,
      track.scrollWidth - track.clientWidth
    );

    track.scrollTo({ left: target, behavior: "smooth" });
  }

  function initCarousel(root) {
    const track = root.querySelector(SELECTOR.track);
    if (!track) return;

    const prevBtn = root.querySelector(SELECTOR.prev);
    const nextBtn = root.querySelector(SELECTOR.next);

    // Initial state
    updateButtons(track, prevBtn, nextBtn);

    // Buttons
    prevBtn?.addEventListener("click", () => scrollByStep(track, -1));
    nextBtn?.addEventListener("click", () => scrollByStep(track, 1));

    // Keep buttons updated while scrolling
    let raf = 0;
    const onScroll = () => {
      cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => updateButtons(track, prevBtn, nextBtn));
    };
    track.addEventListener("scroll", onScroll, { passive: true });

    // Keyboard support (only when focused)
    track.addEventListener("keydown", (e) => {
      if (e.key === "ArrowLeft") {
        e.preventDefault();
        scrollByStep(track, -1);
      } else if (e.key === "ArrowRight") {
        e.preventDefault();
        scrollByStep(track, 1);
      } else if (e.key === "Home") {
        e.preventDefault();
        track.scrollTo({ left: 0, behavior: "smooth" });
      } else if (e.key === "End") {
        e.preventDefault();
        track.scrollTo({ left: track.scrollWidth, behavior: "smooth" });
      }
    });

    // Resize: step/gap can change with breakpoints, so re-evaluate state
    const ro = new ResizeObserver(() => updateButtons(track, prevBtn, nextBtn));
    ro.observe(track);

    // If images load and affect layout, update button state again
    window.addEventListener(
      "load",
      () => updateButtons(track, prevBtn, nextBtn),
      {
        once: true,
      }
    );
  }

  function initAll() {
    document.querySelectorAll(SELECTOR.root).forEach(initCarousel);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAll, { once: true });
  } else {
    initAll();
  }
})();
