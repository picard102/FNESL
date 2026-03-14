import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  CheckboxControl,
  Spinner,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useMemo } from "@wordpress/element";

registerBlockType("fnesl/affiliations", {
  edit: ({ attributes, setAttributes }) => {
    const { mode, count, pickedIds } = attributes;

    const affiliations = useSelect(
      (select) =>
        select("core").getEntityRecords("postType", "affiliation", {
          per_page: 100,
          orderby: "title",
          order: "asc",
          status: "publish",
        }),
      [],
    );

    const blockProps = useBlockProps({
      className: "affiliations-editor-preview",
    });

    const previewItems = useMemo(() => {
      if (!affiliations) return [];
      if (mode === "pick") {
        return affiliations.filter((a) => pickedIds.includes(a.id));
      }
      const sorted = [...affiliations];
      if (mode === "random") {
        sorted.sort(() => Math.random() - 0.5);
      } else {
        // latest: sort by date desc (API already returns this if we request it,
        // but here we just show the first `count` for preview)
      }
      return sorted.slice(0, count);
    }, [affiliations, mode, count, pickedIds]);

    const togglePicked = (id, checked) => {
      const next = checked
        ? [...pickedIds, id]
        : pickedIds.filter((v) => v !== id);
      setAttributes({ pickedIds: next });
    };

    return (
      <>
        <InspectorControls>
          <PanelBody title={__("Display", "fnesl")} initialOpen={true}>
            <SelectControl
              label={__("Mode", "fnesl")}
              value={mode}
              options={[
                { label: __("Latest", "fnesl"), value: "latest" },
                { label: __("Random", "fnesl"), value: "random" },
                { label: __("Pick specific", "fnesl"), value: "pick" },
              ]}
              onChange={(v) => setAttributes({ mode: v })}
            />

            {mode !== "pick" && (
              <RangeControl
                label={__("Number to show", "fnesl")}
                value={count}
                min={1}
                max={12}
                onChange={(v) => setAttributes({ count: v })}
              />
            )}

            {mode === "pick" && (
              <div>
                <p style={{ marginBottom: "8px", fontWeight: 600 }}>
                  {__("Select affiliations", "fnesl")}
                </p>
                {!affiliations ? (
                  <Spinner />
                ) : affiliations.length === 0 ? (
                  <p>{__("No affiliations found.", "fnesl")}</p>
                ) : (
                  affiliations.map((a) => (
                    <CheckboxControl
                      key={a.id}
                      label={a.title?.rendered || `#${a.id}`}
                      checked={pickedIds.includes(a.id)}
                      onChange={(checked) => togglePicked(a.id, checked)}
                    />
                  ))
                )}
              </div>
            )}
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          <div className="affiliations-editor-preview__header">
            <strong>{__("Affiliations Block", "fnesl")}</strong>
            <span className="affiliations-editor-preview__meta">
              {mode === "pick"
                ? `${pickedIds.length} selected`
                : `${mode} · ${count}`}
            </span>
          </div>

          {!affiliations ? (
            <Spinner />
          ) : previewItems.length === 0 ? (
            <p style={{ color: "#999", fontStyle: "italic" }}>
              {mode === "pick"
                ? __("No affiliations selected.", "fnesl")
                : __("No affiliations found.", "fnesl")}
            </p>
          ) : (
            <ul className="affiliations-editor-preview__list">
              {previewItems.map((a) => (
                <li key={a.id} className="affiliations-editor-preview__item">
                  {a.title?.rendered || `#${a.id}`}
                </li>
              ))}
            </ul>
          )}
        </div>
      </>
    );
  },

  save: () => null,
});
