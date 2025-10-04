import { registerBlockType } from "@wordpress/blocks";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, SelectControl, Spinner } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";

const Edit = ({ attributes, setAttributes }) => {
  const { selectedExpertise } = attributes;
  const blockProps = useBlockProps({ className: "project-expertise-header" });

  // Get assigned expertise term IDs live from editor (no save required)
  const assignedTermIds = useSelect((select) => {
    const ids = select("core/editor").getEditedPostAttribute("expertise");
    return Array.isArray(ids) ? ids : [];
  }, []);

  // Fetch all expertise terms (needed to map IDs → names)
  const allTerms = useSelect(
    (select) =>
      select("core").getEntityRecords("taxonomy", "expertise", {
        per_page: 100,
        _fields: ["id", "name", "parent", "slug"],
      }),
    []
  );

  const isLoading = allTerms === undefined;
  const safeAll = allTerms || [];

  // Only top-level terms that are actually assigned
  const topLevelAssigned = safeAll.filter(
    (t) => t.parent === 0 && assignedTermIds.includes(t.id)
  );

  // Clear selection if the term is no longer assigned
  useEffect(() => {
    if (selectedExpertise && !assignedTermIds.includes(selectedExpertise)) {
      setAttributes({ selectedExpertise: null });
    }
  }, [assignedTermIds, selectedExpertise]);

  // Auto-select when there’s exactly one assigned top-level term
  useEffect(() => {
    if (!selectedExpertise && topLevelAssigned.length === 1) {
      setAttributes({ selectedExpertise: topLevelAssigned[0].id });
    }
  }, [topLevelAssigned, selectedExpertise]);

  const selectedLabel =
    safeAll.find((t) => t.id === selectedExpertise)?.name || "";

  return (
    <>
      <InspectorControls>
        <PanelBody title={__("Expertise Settings", "fnesl")}>
          {isLoading && <Spinner />}
          {!isLoading && topLevelAssigned.length > 1 && (
            <SelectControl
              label={__("Select Expertise", "fnesl")}
              value={selectedExpertise ?? ""}
              options={[
                { label: __("— Select —", "fnesl"), value: "" },
                ...topLevelAssigned.map((t) => ({
                  label: t.name,
                  value: t.id,
                })),
              ]}
              onChange={(val) =>
                setAttributes({
                  selectedExpertise: val ? parseInt(val, 10) : null,
                })
              }
            />
          )}
          {!isLoading && topLevelAssigned.length === 0 && (
            <p>{__("No expertise terms assigned yet.", "fnesl")}</p>
          )}
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        {isLoading && <Spinner />}

        {!isLoading && topLevelAssigned.length === 0 && (
          <span className="expertise-label">
            {__("No expertise assigned yet", "fnesl")}
          </span>
        )}

        {!isLoading && selectedExpertise && (
          <span className="expertise-label">
            {" "}
            <svg className="aspect-square h-5 fill-current" aria-hidden="true">
              <use
                xlinkHref={`#exp-${
                  safeAll.find((t) => t.id === selectedExpertise)?.slug
                }`}
              />
            </svg>
            {selectedLabel}
          </span>
        )}

        {!isLoading && !selectedExpertise && topLevelAssigned.length === 1 && (
          <span className="expertise-label">
            <svg className="aspect-square h-5 fill-current" aria-hidden="true">
              <use xlinkHref={`#exp-${topLevelAssigned[0].slug}`} />
            </svg>
             {topLevelAssigned[0].name}
          </span>
        )}

        {!isLoading && !selectedExpertise && topLevelAssigned.length > 1 && (
          <span className="expertise-label">
            {__("Select an expertise term", "fnesl")}
          </span>
        )}
      </div>
    </>
  );
};

registerBlockType("fnesl/project-expertise-header", {
  edit: Edit,
  save: () => null, // front-end handled by PHP render.php
});
