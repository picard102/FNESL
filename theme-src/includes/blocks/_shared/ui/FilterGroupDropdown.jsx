import { useMemo } from "@wordpress/element";

function buildTermTree(terms = []) {
  const byId = new Map();
  const roots = [];

  terms.forEach((t) => byId.set(String(t.id), { ...t, children: [] }));

  byId.forEach((node) => {
    const parentKey = String(node.parent ?? 0);

    const hasParent =
      node.parent !== null &&
      node.parent !== undefined &&
      node.parent !== 0 &&
      node.parent !== "0";

    if (hasParent && byId.has(parentKey)) {
      byId.get(parentKey).children.push(node);
    } else {
      roots.push(node);
    }
  });

  const sort = (a, b) => (a.name || "").localeCompare(b.name || "");
  const sortDeep = (nodes) => {
    nodes.sort(sort);
    nodes.forEach((n) => n.children?.length && sortDeep(n.children));
  };
  sortDeep(roots);

  return roots;
}

function flattenTree(nodes, depth = 0, out = []) {
  nodes.forEach((n) => {
    out.push({ ...n, depth });
    if (n.children?.length) flattenTree(n.children, depth + 1, out);
  });
  return out;
}

function indentLabel(name, depth) {
  // visual indentation (works fine in most browsers)
  const pad = "\u00A0\u00A0".repeat(depth * 2);
  return `${pad}${name}`;
}

export function FilterGroupSelect({
  label,
  terms,
  selectedIds,
  onChange,
  disabled,
}) {
  const tree = useMemo(() => buildTermTree(terms), [terms]);
  const flat = useMemo(() => flattenTree(tree), [tree]);

  // single-select: pick the first selected id, otherwise "" (Any)
  const value = selectedIds?.length ? String(selectedIds[0]) : "";

  return (
    <div className="rounded-2xl border border-black/10 bg-white/60 p-4">
      <label className="block font-semibold text-sm mb-3 text-black/80">
        {label}
      </label>

      <select
        className="w-full rounded-xl border border-black/15 bg-white px-3 py-2 text-sm hover:bg-black/[0.03] disabled:opacity-50 disabled:cursor-not-allowed"
        value={value}
        onChange={(e) => {
          const next = e.target.value;
          onChange(next === "" ? null : Number(next));
        }}
        disabled={disabled}
      >
        <option value="">Any</option>
        {flat.map((t) => (
          <option key={t.id} value={String(t.id)}>
            {indentLabel(t.name, t.depth)}
          </option>
        ))}
      </select>
    </div>
  );
}
