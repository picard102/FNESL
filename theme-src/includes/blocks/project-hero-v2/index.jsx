import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  PanelColorSettings,
  useSetting,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  Button,
  ToggleControl,
} from "@wordpress/components";
import { useEntityProp } from "@wordpress/core-data";
import { useSelect } from "@wordpress/data";
import { useEffect } from "@wordpress/element";

registerBlockType("fnesl/project-hero-v2", {
  edit: ({ attributes, setAttributes }) => {
    const {
      backgroundType,
      backgroundImage,
      backgroundVideo,
      blurLevel,
      showOverlay,
      textAlign,
      selectedExpertise,
      backgroundColor,
      textColor,
      fontSize,
    } = attributes;

    const [title] =
      typeof useEntityProp === "function"
        ? useEntityProp("postType", "project", "title")
        : [""];

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

    // Auto-clear video when switching back to image mode
    useEffect(() => {
      if (backgroundType === "image" && backgroundVideo?.url) {
        setAttributes({ backgroundVideo: null });
      }
    }, [backgroundType]);

    const normalizeFontSlug = (slug) => slug.replace(/^(\d+)/, "$1-");

    const blockProps = useBlockProps({
      className: `project-hero-editor alignfull text-${textAlign}`,
      style: {
        backgroundColor: backgroundColor || "var(--wp--preset--color--primary)",
        color: textColor || "var(--wp--preset--color--white)",
      },
    });

    if (!fontSize) setAttributes({ fontSize: "xl" });

    const fontSizes = useSetting("typography.fontSizes") || [];

    return (
      <>
        <InspectorControls>
          {/* 🎨 Colors */}
          <PanelColorSettings
            title={__("Colors", "fnesl")}
            initialOpen={false}
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

          {/* ✏️ Typography */}
          <PanelBody title={__("Typography", "fnesl")} initialOpen={false}>
            <SelectControl
              label={__("Headline Size", "fnesl")}
              value={fontSize}
              options={fontSizes.map((f) => ({
                label: f.name,
                value: f.slug,
              }))}
              onChange={(newSlug) =>
                setAttributes({ fontSize: newSlug || "xl" })
              }
            />
          </PanelBody>

          {/* 🖼 Background */}
          <PanelBody
            title={__("Background Media", "fnesl")}
            initialOpen={false}
          >
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
                  className="project-hero-editor__preview-image"
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
                    <Button onClick={open} variant="secondary">
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
                    className="project-hero-editor__preview-video"
                    muted
                    autoPlay
                    loop
                  />
                )}
              </MediaUploadCheck>
            )}
          </PanelBody>

          {/* ⚙️ Layout */}
          <PanelBody title={__("Layout Settings", "fnesl")} initialOpen={false}>
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

        {/* ✨ Editor Preview */}
        <div {...blockProps}>
          {/* Background media */}
          {backgroundType === "video" && backgroundVideo?.url ? (
            <video
              src={backgroundVideo.url}
              poster={backgroundImage?.url || ""}
              className="project-hero-editor__media"
              muted
              autoPlay
              loop
            />
          ) : backgroundImage?.url ? (
            <img
              src={backgroundImage.url}
              alt=""
              className="project-hero-editor__media"
            />
          ) : null}

          {/* Content */}
          <div
            className={`project-hero-editor__inner has-text-align-${textAlign}`}
          >
            <p className="project-hero-editor__expertise has-lg-font-size">
              {selectedExpertise
                ? terms?.find((t) => t.id === selectedExpertise)?.name
                : __("Expertise: Auto", "fnesl")}
            </p>

            <h1
              className={`has-${normalizeFontSlug(fontSize)}-font-size`}
              dangerouslySetInnerHTML={{
                __html: title || __("Project Title", "fnesl"),
              }}
            />
          </div>
        </div>
      </>
    );
  },

  save: () => null,
});
