import { registerBlockType } from "@wordpress/blocks";
import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
  InnerBlocks,
  BlockControls,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import {
  PanelBody,
  Button,
  SelectControl,
  ToggleControl,
  ToolbarGroup,
  ToolbarButton,
} from "@wordpress/components";
import { useState } from "@wordpress/element";

import "./style.css";

registerBlockType("fnesl/project-hero", {
  edit: ({ attributes, setAttributes }) => {
    const {
      backgroundType,
      backgroundImage,
      backgroundVideo,
      fallbackImage,
      blurLevel,
      showOverlay,
      verticalAlign,
    } = attributes;

    const [videoReady, setVideoReady] = useState(false);

    // Outer wrapper (full width)
    const blockProps = useBlockProps({
      className: "project-hero alignfull",
    });

    // Blur levels
    const blurClass =
      blurLevel === 0 ? "blur-none" : blurLevel === 1 ? "blur-xs" : "blur-sm";

    // Vertical alignment
    const alignClass =
      verticalAlign === "top"
        ? "is-align-top"
        : verticalAlign === "bottom"
        ? "is-align-bottom"
        : "is-align-center";

    return (
      <>
        {/* Toolbar for alignment */}
        <BlockControls>
          <ToolbarGroup>
            <ToolbarButton
              icon="arrow-up-alt2"
              label={__("Align Top", "fnesl")}
              isPressed={verticalAlign === "top"}
              onClick={() => setAttributes({ verticalAlign: "top" })}
            />
            <ToolbarButton
              icon="minus"
              label={__("Align Center", "fnesl")}
              isPressed={verticalAlign === "center"}
              onClick={() => setAttributes({ verticalAlign: "center" })}
            />
            <ToolbarButton
              icon="arrow-down-alt2"
              label={__("Align Bottom", "fnesl")}
              isPressed={verticalAlign === "bottom"}
              onClick={() => setAttributes({ verticalAlign: "bottom" })}
            />
          </ToolbarGroup>
        </BlockControls>

        {/* Sidebar settings */}
        <InspectorControls>
          <PanelBody title={__("Background Settings", "fnesl")}>
            <SelectControl
              label={__("Background Type", "fnesl")}
              value={backgroundType}
              options={[
                { label: __("Image", "fnesl"), value: "image" },
                { label: __("Video", "fnesl"), value: "video" },
              ]}
              onChange={(val) => setAttributes({ backgroundType: val })}
            />

            {backgroundType === "image" && (
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={(media) =>
                    setAttributes({
                      backgroundImage: {
                        id: media.id,
                        url: media.url,
                        mime: media.mime,
                      },
                    })
                  }
                  allowedTypes={["image"]}
                  value={backgroundImage?.id}
                  render={({ open }) => (
                    <Button onClick={open} isSecondary>
                      {backgroundImage?.url
                        ? __("Replace Image", "fnesl")
                        : __("Select Image", "fnesl")}
                    </Button>
                  )}
                />
              </MediaUploadCheck>
            )}

            {backgroundType === "video" && (
              <>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) =>
                      setAttributes({
                        backgroundVideo: {
                          id: media.id,
                          url: media.url,
                          mime: media.mime,
                        },
                      })
                    }
                    allowedTypes={["video"]}
                    value={backgroundVideo?.id}
                    render={({ open }) => (
                      <Button onClick={open} isSecondary>
                        {backgroundVideo?.url
                          ? __("Replace Video", "fnesl")
                          : __("Select Video", "fnesl")}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>

                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) =>
                      setAttributes({
                        fallbackImage: {
                          id: media.id,
                          url: media.url,
                          mime: media.mime,
                        },
                      })
                    }
                    allowedTypes={["image"]}
                    value={fallbackImage?.id}
                    render={({ open }) => (
                      <Button onClick={open} isSecondary>
                        {fallbackImage?.url
                          ? __("Replace Fallback Image", "fnesl")
                          : __("Select Fallback Image", "fnesl")}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
              </>
            )}
          </PanelBody>

          <PanelBody title={__("Effects", "fnesl")} initialOpen={false}>
            <SelectControl
              label={__("Blur Level", "fnesl")}
              value={blurLevel}
              options={[
                { label: __("None", "fnesl"), value: 0 },
                { label: __("Level 1", "fnesl"), value: 1 },
                { label: __("Level 2", "fnesl"), value: 2 },
              ]}
              onChange={(val) =>
                setAttributes({ blurLevel: parseInt(val, 10) })
              }
            />

            <ToggleControl
              label={__("Show Overlay", "fnesl")}
              checked={showOverlay}
              onChange={(val) => setAttributes({ showOverlay: val })}
            />
          </PanelBody>
        </InspectorControls>

        {/* Front-end layout */}
        <div {...blockProps}>
          <div className={`project-hero__media ${blurClass}`}>
            {backgroundType === "video" && backgroundVideo?.url && (
              <>
                {fallbackImage?.url && (
                  <img
                    src={fallbackImage.url}
                    alt=""
                    className={videoReady ? "opacity-0" : "opacity-100"}
                    aria-hidden="true"
                  />
                )}
                <video
                  key={backgroundVideo.id}
                  autoPlay
                  muted
                  loop
                  playsInline
                  poster={fallbackImage?.url || ""}
                  className={videoReady ? "opacity-100" : "opacity-0"}
                  onCanPlay={() => setVideoReady(true)}
                >
                  <source
                    src={backgroundVideo.url}
                    type={backgroundVideo.mime}
                  />
                </video>
              </>
            )}

            {backgroundType === "image" && backgroundImage?.url && (
              <img src={backgroundImage.url} alt="" />
            )}
          </div>

          {/* Inner constrained content */}
          <div
            className={`project-hero__inner ${alignClass}`}
            data-wp-layout="constrained"
          >
            <InnerBlocks renderAppender={InnerBlocks.ButtonBlockAppender} />
          </div>

          {showOverlay && <div className="project-hero__overlay"></div>}
        </div>
      </>
    );
  },

  save: ({ attributes }) => {
    const {
      backgroundType,
      backgroundImage,
      backgroundVideo,
      fallbackImage,
      blurLevel,
      showOverlay,
      verticalAlign,
    } = attributes;

    const blockProps = useBlockProps.save({
      className: "project-hero alignfull",
    });

    const blurClass =
      blurLevel === 0 ? "blur-none" : blurLevel === 1 ? "blur-xs" : "blur-sm";

    const alignClass =
      verticalAlign === "top"
        ? "is-align-top"
        : verticalAlign === "bottom"
        ? "is-align-bottom"
        : "is-align-center";

    return (
      <div {...blockProps}>
        <div className={`project-hero__media ${blurClass}`}>
          {backgroundType === "video" && backgroundVideo?.url ? (
            <>
              {fallbackImage?.url && (
                <img
                  src={fallbackImage.url}
                  alt=""
                  className="opacity-100"
                  data-fallback
                  aria-hidden="true"
                />
              )}
              <video
                autoPlay
                muted
                loop
                playsInline
                poster={fallbackImage?.url || ""}
                className="opacity-0"
                data-video
              >
                <source src={backgroundVideo.url} type={backgroundVideo.mime} />
              </video>
              <script
                dangerouslySetInnerHTML={{
                  __html: `
                    (function(){
                      var video=document.currentScript.previousElementSibling;
                      var img=video.previousElementSibling;
                      if(video && img){
                        video.addEventListener('canplay',function(){
                          video.classList.remove('opacity-0');
                          video.classList.add('opacity-100');
                          if(img){img.classList.add('opacity-0');}
                        });
                      }
                    })();
                  `,
                }}
              />
            </>
          ) : null}

          {backgroundType === "image" && backgroundImage?.url && (
            <img src={backgroundImage.url} alt="" />
          )}
        </div>

        {/* Inner constrained content */}
        <div
          className={`project-hero__inner ${alignClass}`}
          data-wp-layout="constrained"
        >
          <InnerBlocks.Content />
        </div>

        {showOverlay && <div className="project-hero__overlay"></div>}
      </div>
    );
  },
});
