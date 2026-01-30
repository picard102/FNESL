import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  PanelColorSettings,
  InnerBlocks,
} from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  Button,
  ToggleControl,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useEffect, useMemo } from "@wordpress/element";

registerBlockType("fnesl/home-hero", {
  edit: ({ attributes, setAttributes }) => {
    const {
      backgroundType,
      backgroundImage,
      backgroundVideo,
      blurLevel,
      showOverlay,
      featuredProjectMode,
      featuredProjectId,
      backgroundColor,
      textColor,
    } = attributes;

    // Auto-clear video when switching back to image mode
    useEffect(() => {
      if (backgroundType === "image" && backgroundVideo?.url) {
        setAttributes({ backgroundVideo: null });
      }
      // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [backgroundType]);

    // Fetch projects for the selector (reasonable cap; adjust if needed)
    const projects = useSelect(
      (select) =>
        select("core").getEntityRecords("postType", "project", {
          per_page: 100,
          orderby: "title",
          order: "asc",
          status: "publish",
        }),
      [],
    );

    const projectOptions = useMemo(() => {
      if (!projects) return [{ label: __("Loading…", "fnesl"), value: 0 }];

      return [
        { label: __("Select a project…", "fnesl"), value: 0 },
        ...projects.map((p) => ({
          label: p?.title?.rendered ? stripHtml(p.title.rendered) : `#${p.id}`,
          value: p.id,
        })),
      ];
    }, [projects]);

    const blurClass =
      blurLevel === "sm"
        ? "fnesl-blur-sm"
        : blurLevel === "none"
          ? "fnesl-blur-none"
          : "fnesl-blur-xs";

    const selectedProject = useMemo(() => {
      if (!projects?.length) return null;
      if (featuredProjectMode === "select" && featuredProjectId) {
        return projects.find((p) => p.id === featuredProjectId) || null;
      }
      if (featuredProjectMode === "random") {
        // editor preview only (stable-ish selection):
        return projects[0] || null;
      }
      return null;
    }, [projects, featuredProjectMode, featuredProjectId]);

    const blockProps = useBlockProps({
      className: "home-hero-editor alignfull",
      style: {
        backgroundColor:
          backgroundColor || "var(--wp--preset--color--primary-500)",
        color: textColor || "var(--wp--preset--color--white)",
      },
    });

    const TEMPLATE = [
      [
        "core/heading",
        {
          level: 1,
          content: "Engineering with Purpose.<br> Empowering Communities.",
        },
      ],
      [
        "core/paragraph",
        {
          content:
            "We are a 100% Indigenous-owned civil engineering and community planning firm delivering infrastructure solutions that strengthen First Nations and municipalities across Canada.",
        },
      ],
      [
        "core/buttons",
        {},
        [["core/button", { text: "Our Services", url: "#services" }]],
      ],
    ];

    return (
      <>
        <InspectorControls>
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
                  className="home-hero-editor__preview-image"
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
                    className="home-hero-editor__preview-video"
                    muted
                    autoPlay
                    loop
                  />
                )}
              </MediaUploadCheck>
            )}
          </PanelBody>

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
              checked={!!showOverlay}
              onChange={(v) => setAttributes({ showOverlay: !!v })}
            />
          </PanelBody>

          <PanelBody
            title={__("Featured Project", "fnesl")}
            initialOpen={false}
          >
            <SelectControl
              label={__("Mode", "fnesl")}
              value={featuredProjectMode || "none"}
              options={[
                { label: __("None", "fnesl"), value: "none" },
                { label: __("Random", "fnesl"), value: "random" },
                { label: __("Pick a Project", "fnesl"), value: "select" },
              ]}
              onChange={(v) => setAttributes({ featuredProjectMode: v })}
            />

            {featuredProjectMode === "select" && (
              <SelectControl
                label={__("Project", "fnesl")}
                value={featuredProjectId || 0}
                options={projectOptions}
                onChange={(v) =>
                  setAttributes({ featuredProjectId: parseInt(v, 10) || 0 })
                }
              />
            )}
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          {/* Background preview */}
          {backgroundType === "video" && backgroundVideo?.url ? (
            <video
              src={backgroundVideo.url}
              poster={backgroundImage?.url || ""}
              className={`home-hero-editor__media ${blurClass}`}
              muted
              autoPlay
              loop
            />
          ) : backgroundImage?.url ? (
            <img
              src={backgroundImage.url}
              alt=""
              className={`home-hero-editor__media ${blurClass}`}
            />
          ) : null}

          {/* Editor layout preview */}
          <div className="home-hero-editor__layout">
            <div className="home-hero-editor__content">
              <InnerBlocks template={TEMPLATE} templateLock={false} />
            </div>

            <div className="home-hero-editor__side">
              <div className="home-hero-editor__side-label">
                {__("Featured Project", "fnesl")}
              </div>

              {featuredProjectMode === "none" ? (
                <div className="home-hero-editor__side-empty">
                  {__("None", "fnesl")}
                </div>
              ) : selectedProject ? (
                <div className="home-hero-editor__card">
                  <div
                    className="home-hero-editor__card-title"
                    dangerouslySetInnerHTML={{
                      __html:
                        selectedProject.title?.rendered ||
                        __("Project", "fnesl"),
                    }}
                  />
                  <div className="home-hero-editor__card-meta">
                    {featuredProjectMode === "random"
                      ? __("Random (preview shows first match)", "fnesl")
                      : __("Selected", "fnesl")}
                  </div>
                </div>
              ) : (
                <div className="home-hero-editor__side-empty">
                  {__("Choose a project (or switch to Random).", "fnesl")}
                </div>
              )}
            </div>
          </div>
        </div>
      </>
    );
  },

  save: () => <InnerBlocks.Content />,
});

function stripHtml(html) {
  return (html || "").replace(/<[^>]*>/g, "").trim();
}
