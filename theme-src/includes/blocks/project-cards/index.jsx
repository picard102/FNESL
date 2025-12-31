import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  ToggleControl,
  RangeControl,
  Notice,
} from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";
import { useEffect, useMemo, useState } from "@wordpress/element";

const buildPath = ({ orderMode, order, postsToShow }) => {
  const perPage = Math.min(postsToShow || 12, 100);
  const restOrder = order === "DESC" ? "desc" : "asc";

  let orderby = "menu_order";
  if (orderMode === "title") orderby = "title";
  if (orderMode === "date") orderby = "date";

  const params = new URLSearchParams({
    per_page: String(perPage),
    order: restOrder,
    orderby,
    _embed: "1",
  });

  return `/wp/v2/project?${params.toString()}`;
};

registerBlockType("fnesl/project-cards", {
  edit({ attributes, setAttributes }) {
    const { orderMode, order, postsToShow, showExcerpt, showFeaturedImage } =
      attributes;

    const path = useMemo(
      () => buildPath({ orderMode, order, postsToShow }),
      [orderMode, order, postsToShow]
    );

    const [projects, setProjects] = useState(null);
    const [error, setError] = useState("");

    useEffect(() => {
      let isMounted = true;
      setProjects(null);
      setError("");

      apiFetch({ path })
        .then((res) => {
          if (!isMounted) return;
          setProjects(Array.isArray(res) ? res : []);
        })
        .catch((e) => {
          if (!isMounted) return;
          setError(e?.message || "Request failed.");
          setProjects([]);
        });

      return () => {
        isMounted = false;
      };
    }, [path]);

    const blockProps = useBlockProps({ className: "fnesl-pc" });

    return (
      <>
        <InspectorControls>
          <PanelBody title="Query" initialOpen>
            <SelectControl
              label="Order by"
              value={orderMode}
              options={[
                { label: "Custom (Project Order)", value: "custom" },
                { label: "Date", value: "date" },
                { label: "Title", value: "title" },
              ]}
              onChange={(v) => setAttributes({ orderMode: v })}
            />

            <SelectControl
              label="Direction"
              value={order}
              options={[
                { label: "Ascending", value: "ASC" },
                { label: "Descending", value: "DESC" },
              ]}
              onChange={(v) => setAttributes({ order: v })}
            />

            <RangeControl
              label="Max projects"
              min={1}
              max={100}
              value={postsToShow}
              onChange={(v) => setAttributes({ postsToShow: v || 1 })}
            />
          </PanelBody>

          <PanelBody title="Display" initialOpen={false}>
            <ToggleControl
              label="Show featured image"
              checked={!!showFeaturedImage}
              onChange={(v) => setAttributes({ showFeaturedImage: !!v })}
            />
            <ToggleControl
              label="Show excerpt"
              checked={!!showExcerpt}
              onChange={(v) => setAttributes({ showExcerpt: !!v })}
            />
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          {projects === null && (
            <p className="fnesl-pc__status">Loading projects…</p>
          )}

          {!!error && (
            <Notice status="warning" isDismissible={false}>
              {error}
            </Notice>
          )}

          {Array.isArray(projects) && projects.length === 0 && (
            <p className="fnesl-pc__status">No projects found.</p>
          )}

          {Array.isArray(projects) && projects.length > 0 && (
            <ul className="fnesl-pc__items">
              {projects.map((p) => {
                const thumb =
                  p?._embedded?.["wp:featuredmedia"]?.[0]?.media_details?.sizes
                    ?.medium?.source_url ||
                  p?._embedded?.["wp:featuredmedia"]?.[0]?.media_details?.sizes
                    ?.thumbnail?.source_url ||
                  p?._embedded?.["wp:featuredmedia"]?.[0]?.source_url ||
                  null;

                return (
                  <li key={p.id} className="fnesl-pc__item">
                    {showFeaturedImage && thumb && (
                      <img className="fnesl-pc__thumb" src={thumb} alt="" />
                    )}

                    <div className="fnesl-pc__content">
                      <div
                        className="fnesl-pc__title"
                        dangerouslySetInnerHTML={{
                          __html: p.title?.rendered || "",
                        }}
                      />

                      {showExcerpt && p.excerpt?.rendered && (
                        <div
                          className="fnesl-pc__excerpt"
                          dangerouslySetInnerHTML={{
                            __html: p.excerpt.rendered,
                          }}
                        />
                      )}
                    </div>
                  </li>
                );
              })}
            </ul>
          )}
        </div>
      </>
    );
  },

  save() {
    return null;
  },
});
