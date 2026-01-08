import { useEffect, useMemo, useState } from "@wordpress/element";
import { fetchProjectArchive } from "./api";

export function useProjectArchive({ show, mode, perPage }) {
  const [filters, setFilters] = useState(null);
  const [selected, setSelected] = useState({});
  const [projects, setProjects] = useState([]);
  const [total, setTotal] = useState(0);
  const [totalPages, setTotalPages] = useState(1);
  const [page, setPage] = useState(1);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const hasSelected = useMemo(
    () =>
      Object.values(selected).some((arr) => Array.isArray(arr) && arr.length),
    [selected]
  );

  function toggleTerm(tax, termId) {
    setSelected((prev) => {
      const cur = new Set(Array.isArray(prev[tax]) ? prev[tax] : []);
      if (cur.has(termId)) cur.delete(termId);
      else cur.add(termId);
      return { ...prev, [tax]: Array.from(cur) };
    });
  }

  function clearAll() {
    setSelected({});
  }

  async function run({
    nextPage = 1,
    append = false,
    includeFilters = false,
  } = {}) {
    setLoading(true);
    setError("");
    try {
      const data = await fetchProjectArchive({
        page: nextPage,
        perPage,
        mode,
        terms: selected,
        show,
        includeFilters,
      });

      const nextProjects = Array.isArray(data?.projects) ? data.projects : [];
      setProjects((prev) =>
        append ? prev.concat(nextProjects) : nextProjects
      );
      setTotal(Number(data?.total || 0));
      setTotalPages(Number(data?.totalPages || 1));
      setPage(Number(data?.page || nextPage));

      if (includeFilters) setFilters(data?.filters || {});
    } catch (e) {
      setError(e?.message || "Request failed.");
    } finally {
      setLoading(false);
    }
  }

  // bootstrap
  useEffect(() => {
    run({ nextPage: 1, append: false, includeFilters: true });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // re-run on filters change
  useEffect(() => {
    if (filters === null) return;
    run({ nextPage: 1, append: false, includeFilters: false });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [JSON.stringify(selected)]);

  return {
    filters,
    selected,
    projects,
    total,
    totalPages,
    page,
    loading,
    error,
    hasSelected,
    toggleTerm,
    clearAll,
    loadMore: () =>
      run({ nextPage: page + 1, append: true, includeFilters: false }),
  };
}
