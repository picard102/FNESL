import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  InnerBlocks,
  PanelColorSettings,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  Button,
  ToggleControl,
} from "@wordpress/components";
import { useEntityProp } from "@wordpress/core-data";
import { useSelect } from "@wordpress/data";

registerBlockType("fnesl/project-hero-v2", {
  title: __("Project Hero (v2)", "fnesl"),
  description: __(
    "Dynamic project hero with featured expertise, automatic title, and background image/video support.",
    "fnesl"
  ),
  icon: "format-image",
  category: "fnesl",
  supports: { align: ["wide"] },

  attributes: {
    backgroundType: { type: "string", default: "image" },
    backgroundImage: { type: "object" },
    backgroundVideo: { type: "object" },
    blurLevel: { type: "string", default: "xs" },
    showOverlay: { type: "boolean", default: true },
    verticalAlign: { type: "string", default: "bottom" },
    titleSize: { type: "string", default: "4xl" },
    textAlign: { type: "string", default: "left" },
    selectedExpertise: { type: "integer", default: null },
    backgroundColor: {
      type: "string",
      default: "var(--wp--preset--color--primary-500)",
    },
    textColor: { type: "string" },
  },

  edit: ({ attributes, setAttributes }) => {
    const {
      backgroundType,
      backgroundImage,
      backgroundVideo,
      blurLevel,
      showOverlay,
      verticalAlign,
      titleSize,
      textAlign,
      selectedExpertise,
      backgroundColor,
      textColor,
    } = attributes;

    // dynamic project title
    const [title] =
      typeof useEntityProp === "function"
        ? useEntityProp("postType", "project", "title")
        : [""];

    // get expertise taxonomy
    const terms = useSelect(
      (select) =>
        select("core").getEntityRecords("taxonomy", "expertise", {
          per_page: -1,
        }),
      []
    );

    const expertiseOptions = terms
      ? [
          { label: __("Auto (use first assigned)", "fnesl"), value: 0 },
          ...terms.map((t) => ({ label: t.name, value: t.id })),
        ]
      : [{ label: __("Loading…", "fnesl"), value: 0 }];

    // helper for blur class
    const blurClass =
      blurLevel === "xs"
        ? "fnesl-blur-xs"
        : blurLevel === "sm"
        ? "fnesl-blur-sm"
        : "fnesl-blur-none";

    // block props for editor selection and palette colors
    const blockProps = useBlockProps({
      className: `project-hero-editor has-${textAlign}-text alignwide`,
      style: {
        backgroundColor:
          backgroundColor || "var(--wp--preset--color--primary-500)",
        color: textColor || "inherit",
        position: "relative",
        overflow: "hidden",
      },
    });

    return (
      <>
        <InspectorControls>
          {/* 🎨 Color Controls */}
          <PanelColorSettings
            title={__("Colors", "fnesl")}
            colorSettings={[
              {
                value: backgroundColor,
                onChange: (color) => setAttributes({ backgroundColor: color }),
                label: __("Background Color", "fnesl"),
              },
              {
                value: textColor,
                onChange: (color) => setAttributes({ textColor: color }),
                label: __("Text Color", "fnesl"),
              },
            ]}
          />

          {/* 🖼 Background Media */}
          <PanelBody title={__("Background Media", "fnesl")} initialOpen={true}>
            <SelectControl
              label={__("Background Type", "fnesl")}
              value={backgroundType}
              options={[
                { label: __("Image Only", "fnesl"), value: "image" },
                {
                  label: __("Video (with Image Fallback)", "fnesl"),
                  value: "video",
                },
              ]}
              onChange={(v) => setAttributes({ backgroundType: v })}
            />

            {/* Always show image uploader */}
            <MediaUploadCheck>
              <MediaUpload
                onSelect={(media) => setAttributes({ backgroundImage: media })}
                allowedTypes={["image"]}
                render={({ open }) => (
                  <Button onClick={open} variant="secondary">
                    {backgroundImage?.url
                      ? __("Replace Image", "fnesl")
                      : __("Select Image", "fnesl")}
                  </Button>
                )}
              />
              {backgroundImage?.url && (
                <img
                  src={backgroundImage.url}
                  alt=""
                  style={{
                    width: "100%",
                    marginTop: "10px",
                    borderRadius: "4px",
                  }}
                />
              )}
            </MediaUploadCheck>

            {backgroundType === "video" && (
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) =>
                    setAttributes({ backgroundVideo: media })
                  }
                  allowedTypes={["video"]}
                  render={({ open }) => (
                    <Button onClick={open} variant="secondary" className="mt-2">
                      {backgroundVideo?.url
                        ? __("Replace Video", "fnesl")
                        : __("Select Video", "fnesl")}
                    </Button>
                  )}
                />
                {backgroundVideo?.url && (
                  <video
                    src={backgroundVideo.url}
                    poster={backgroundImage?.url || ""}
                    style={{
                      width: "100%",
                      marginTop: "10px",
                      borderRadius: "4px",
                    }}
                    muted
                    autoPlay
                    loop
                  />
                )}
              </MediaUploadCheck>
            )}
          </PanelBody>

          {/* ⚙️ Layout Settings */}
          <PanelBody title={__("Layout Settings", "fnesl")} initialOpen={false}>
            <SelectControl
              label={__("Vertical Align", "fnesl")}
              value={verticalAlign}
              options={[
                { label: __("Top", "fnesl"), value: "top" },
                { label: __("Center", "fnesl"), value: "center" },
                { label: __("Bottom", "fnesl"), value: "bottom" },
              ]}
              onChange={(v) => setAttributes({ verticalAlign: v })}
            />

            {/* 👇 Blur selector */}
            <SelectControl
              label={__("Blur Level", "fnesl")}
              value={blurLevel}
              options={[
                { label: __("None", "fnesl"), value: "none" },
                { label: __("XS", "fnesl"), value: "xs" },
                { label: __("SM", "fnesl"), value: "sm" },
              ]}
              onChange={(v) => setAttributes({ blurLevel: v })}
            />

            <ToggleControl
              label={__("Show Overlay", "fnesl")}
              checked={showOverlay}
              onChange={(v) => setAttributes({ showOverlay: v })}
            />
          </PanelBody>

          {/* ✏️ Headline */}
          <PanelBody title={__("Headline", "fnesl")} initialOpen={false}>
            <SelectControl
              label={__("Text Alignment", "fnesl")}
              value={textAlign}
              options={[
                { label: __("Left", "fnesl"), value: "left" },
                { label: __("Center", "fnesl"), value: "center" },
                { label: __("Right", "fnesl"), value: "right" },
              ]}
              onChange={(v) => setAttributes({ textAlign: v })}
            />
            <SelectControl
              label={__("Headline Size", "fnesl")}
              value={titleSize}
              options={[
                { label: "3XL", value: "3xl" },
                { label: "4XL", value: "4xl" },
                { label: "5XL", value: "5xl" },
                { label: "6XL", value: "6xl" },
              ]}
              onChange={(v) => setAttributes({ titleSize: v })}
            />
          </PanelBody>

          {/* 🧠 Featured Expertise */}
          <PanelBody
            title={__("Featured Expertise", "fnesl")}
            initialOpen={false}
          >
            <SelectControl
              label="Expertise Term"
              value={selectedExpertise || 0}
              options={expertiseOptions}
              onChange={(v) =>
                setAttributes({ selectedExpertise: parseInt(v) })
              }
            />
          </PanelBody>
        </InspectorControls>

        {/* ✨ Block Preview */}
        <div {...blockProps}>
          {/* Background media */}
          {backgroundVideo?.url ? (
            <video
              src={backgroundVideo.url}
              poster={backgroundImage?.url || ""}
              className={`absolute inset-0 object-cover w-full h-full opacity-40 ${blurClass}`}
              muted
              autoPlay
              loop
            />
          ) : backgroundImage?.url ? (
            <img
              src={backgroundImage.url}
              alt=""
              className={`absolute inset-0 object-cover w-full h-full opacity-40 ${blurClass}`}
            />
          ) : null}

          {/* Overlay */}
          {showOverlay && (
            <div
              className="absolute inset-0 pointer-events-none"
              style={{
                background:
                  "linear-gradient(180deg, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0.35) 60%, rgba(0,0,0,0.45) 100%)",
              }}
            />
          )}

          {/* Content */}
          <div
            className={`relative z-10 alignwide flex flex-col justify-${verticalAlign} py-20`}
          >
            <p className="text-sm opacity-80 mb-2">
              {terms && selectedExpertise
                ? terms.find((t) => t.id === selectedExpertise)?.name
                : __("Expertise: Auto", "fnesl")}
            </p>

            <h1 className={`text-${titleSize} text-${textAlign}`}>
              {title || __("Project Title", "fnesl")}
            </h1>

            {/* Inner blocks for user content */}
            <div className="mt-6">
              <InnerBlocks
                templateLock={false}
                renderAppender={InnerBlocks.ButtonBlockAppender}
              />
            </div>
          </div>
        </div>
      </>
    );
  },

  save: () => <InnerBlocks.Content />,
});
