import { createRoot, useMemo } from "@wordpress/element";
import { ProjectCard } from "../_shared/ui/ProjectCard";
import { FilterGroupSelect } from "../_shared/ui/FilterGroupDropdown";
import { useProjectArchive } from "../_shared/projects/useProjectArchive";

const TAX_LABELS = {
  expertise: "Expertise",
  partners: "Partners",
  location: "Location",
  client: "Client",
  awards: "Awards",
};

function safeParseJsonAttr(el, attrName) {
  try {
    return JSON.parse(el.getAttribute(attrName) || "{}");
  } catch {
    return {};
  }
}

function normalizeShow(show) {
  return Array.isArray(show) ? show.map(String).filter(Boolean) : [];
}

function normalizeMode(mode) {
  return mode === "or" ? "or" : "and";
}

function normalizePerPage(n) {
  const v = Number(n);
  return Number.isFinite(v) && v > 0
    ? Math.max(1, Math.min(100, Math.floor(v)))
    : 12;
}

function App({ config }) {
  const show = useMemo(() => normalizeShow(config.show), [config.show]);
  const mode = useMemo(() => normalizeMode(config.mode), [config.mode]);
  const perPage = useMemo(
    () => normalizePerPage(config.perPage),
    [config.perPage]
  );
  const heading = String(config.heading || "").trim();

  const archive = useProjectArchive({ show, mode, perPage });

  const canLoadMore = !archive.loading && archive.page < archive.totalPages;
  console.log(archive.filters);
  return (
    <div className="w-full">
      {heading ? (
        <h2 className="text-3xl font-semibold tracking-tight text-primary-700 mb-6">
          {heading}
        </h2>
      ) : null}

      <div className="flex items-center justify-between gap-3 mb-5">
        <div className="text-sm text-black/70">{archive.total} projects</div>

        <button
          type="button"
          className="rounded-xl border border-current px-4 py-2 text-sm hover:bg-black/[0.03] disabled:opacity-50 disabled:cursor-not-allowed"
          onClick={archive.clearAll}
          disabled={!archive.hasSelected || archive.loading}
        >
          Clear filters
        </button>
      </div>

      {archive.error ? (
        <div className="mb-6 rounded-2xl border border-red-500/20 bg-red-50 px-4 py-3 text-sm text-red-900">
          {archive.error}
        </div>
      ) : null}

      <div className="grid gap-4 mb-8">
        {archive.filters &&
          show
            .filter(
              (tax) =>
                Array.isArray(archive.filters?.[tax]) &&
                archive.filters[tax].length
            )
            .map((tax) => (
              <FilterGroupSelect
                key={tax}
                label={TAX_LABELS[tax] || tax}
                terms={archive.filters[tax]}
                selectedIds={
                  Array.isArray(archive.selected?.[tax])
                    ? archive.selected[tax]
                    : []
                }
                disabled={archive.loading}
                onChange={(termIdOrNull) => {
                  // Clear existing selection for this taxonomy, then set the new one
                  const current = Array.isArray(archive.selected?.[tax])
                    ? archive.selected[tax]
                    : [];

                  // turn off all currently selected terms (single-select implies 0 or 1, but safe anyway)
                  current.forEach((id) => archive.toggleTerm(tax, id));

                  // if user picked a term, toggle it on
                  if (termIdOrNull) archive.toggleTerm(tax, termIdOrNull);
                }}
              />
            ))}
      </div>

      {archive.loading && archive.projects.length === 0 ? (
        <div className="rounded-2xl border border-black/10 bg-white/60 p-4 text-black/70">
          Loading…
        </div>
      ) : null}

      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        {archive.projects.map((p) => (
          <ProjectCard key={p.id} project={p} />
        ))}
      </div>

      <div className="mt-8 flex justify-center">
        <button
          type="button"
          className="rounded-xl border border-black/15 bg-white px-5 py-2.5 text-sm hover:bg-black/[0.03] disabled:opacity-50 disabled:cursor-not-allowed"
          onClick={archive.loadMore}
          disabled={!canLoadMore}
        >
          {archive.page < archive.totalPages
            ? archive.loading
              ? "Loading…"
              : "Load more"
            : "No more projects"}
        </button>
      </div>
    </div>
  );
}

function mountAll() {
  document.querySelectorAll("[data-project-archive]").forEach((el) => {
    const config = safeParseJsonAttr(el, "data-config");
    createRoot(el).render(<App config={config} />);
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountAll, { once: true });
} else {
  mountAll();
}
