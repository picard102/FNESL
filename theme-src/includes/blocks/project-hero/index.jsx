import { registerBlockType } from "@wordpress/blocks";
import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import {
  PanelBody,
  Button,
  SelectControl,
  ToggleControl,
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
    } = attributes;

    const [videoReady, setVideoReady] = useState(false);

    const blockProps = useBlockProps({
      className: "project-hero relative w-full aspect-video overflow-hidden",
    });

    // Map blur levels to Tailwind classes
    const blurClass =
      blurLevel === 0 ? "blur-none" : blurLevel === 1 ? "blur-xs" : "blur-sm"; // none / medium / strong

    return (
      <>
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

        <div {...blockProps}>
          {/* Media wrapper */}
          <div className={`project-hero__media absolute -inset-2 pointer-events-none  ${blurClass}`}>
            {backgroundType === "video" && backgroundVideo?.url && (
              <>
                {/* Fallback image */}
                {fallbackImage?.url && (
                  <img
                    src={fallbackImage.url}
                    alt=""
                    className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ${
                      videoReady ? "opacity-0" : "opacity-100"
                    }`}
                    aria-hidden="true"
                  />
                )}

                {/* Video */}
                <video
                  key={backgroundVideo.id}
                  autoPlay
                  muted
                  loop
                  playsInline
                  poster={fallbackImage?.url || ""}
                  className={`project-hero__video absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ${
                    videoReady ? "opacity-100" : "opacity-0"
                  }`}
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
              <img
                src={backgroundImage.url}
                alt=""
                className="absolute inset-0 w-full h-full object-cover"
              />
            )}
          </div>

          <div className="relative z-20 text-white p-8">
            <InnerBlocks />
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
    } = attributes;

    const blockProps = useBlockProps.save({
      className: "project-hero relative w-full aspect-video overflow-hidden",
    });

    // Use same blurClass logic on frontend
    const blurClass =
      blurLevel === 0 ? "blur-none" : blurLevel === 1 ? "blur-xs" : "blur-sm";

    return (
      <div {...blockProps}>
        {/* Media wrapper */}
        <div className={`project-hero__media absolute -inset-2 pointer-events-none  ${blurClass}`}>
          {backgroundType === "video" && backgroundVideo?.url ? (
            <>
              {fallbackImage?.url && (
                <img
                  src={fallbackImage.url}
                  alt=""
                  className="absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-700"
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
                className="project-hero__video absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
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
            <img
              src={backgroundImage.url}
              alt=""
              className="absolute inset-0 w-full h-full object-cover"
            />
          )}
        </div>

        <div className="relative z-20 text-white p-8">
          <InnerBlocks.Content />
        </div>
        {showOverlay && <div className="project-hero__overlay"></div>}
      </div>
    );
  },
});
