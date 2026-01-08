import { registerBlockType } from "@wordpress/blocks";
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import {
  PanelBody,
  CheckboxControl,
  SelectControl,
  TextControl,
} from "@wordpress/components";

const TAXONOMIES = [
  { slug: "expertise", label: "Expertise" },
  { slug: "partners", label: "Partners" },
  { slug: "location", label: "Location" },
  { slug: "client", label: "Client" },
  { slug: "awards", label: "Awards" },
];

registerBlockType("fnesl/project-archive", {
  edit({ attributes, setAttributes }) {
    const { showFilters, filterMode, heading } = attributes;

    const blockProps = useBlockProps({ className: "fnesl-pa" });

    const toggleTax = (slug, checked) => {
      const next = new Set(Array.isArray(showFilters) ? showFilters : []);
      if (checked) next.add(slug);
      else next.delete(slug);
      setAttributes({ showFilters: Array.from(next) });
    };

    return (
      <>
        <InspectorControls>
          <PanelBody title="Filters" initialOpen>
            {TAXONOMIES.map((t) => (
              <CheckboxControl
                key={t.slug}
                label={`Show ${t.label} filter`}
                checked={(showFilters || []).includes(t.slug)}
                onChange={(checked) => toggleTax(t.slug, checked)}
              />
            ))}

            <SelectControl
              label="Filter matching"
              value={filterMode}
              options={[
                { label: "Match all selected (AND)", value: "and" },
                { label: "Match any selected (OR)", value: "or" },
              ]}
              onChange={(v) => setAttributes({ filterMode: v })}
              help="AND = project must match all chosen taxonomy selections. OR = match any."
            />
          </PanelBody>

          <PanelBody title="Heading" initialOpen={false}>
            <TextControl
              label="Heading text"
              value={heading || ""}
              onChange={(v) => setAttributes({ heading: v })}
              placeholder="Projects"
            />
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          <div className="fnesl-pa__editor-note">
            <strong>Project Archive</strong>
            <p>
              Front end renders a filterable grid of Projects. Select which
              taxonomies appear as filters in the sidebar.
            </p>
            <p className="fnesl-pa__editor-meta">
              Filters shown:{" "}
              {(showFilters || []).length
                ? (showFilters || []).join(", ")
                : "none"}
            </p>
          </div>
        </div>
      </>
    );
  },

  save() {
    return null;
  },
});
