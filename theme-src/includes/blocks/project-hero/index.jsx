import { registerBlockType } from "@wordpress/blocks";
import {
  InspectorControls,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { PanelBody, Button, SelectControl } from "@wordpress/components";

import "./style.scss";


registerBlockType("fnesl/project-hero", {
  edit: ({ attributes, setAttributes }) => {
    const { backgroundType, backgroundImage, backgroundVideo, fallbackImage } =
      attributes;

    const blockProps = useBlockProps({
      className: "project-hero",
      style:
        backgroundType === "image" && backgroundImage
          ? { backgroundImage: `url(${backgroundImage.url})` }
          : {},
    });

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
                    setAttributes({ backgroundImage: media })
                  }
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <Button onClick={open} isSecondary>
                      {backgroundImage
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
                      setAttributes({ backgroundVideo: media })
                    }
                    allowedTypes={["video"]}
                    render={({ open }) => (
                      <Button onClick={open} isSecondary>
                        {backgroundVideo
                          ? __("Replace Video", "fnesl")
                          : __("Select Video", "fnesl")}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>

                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={(media) =>
                      setAttributes({ fallbackImage: media })
                    }
                    allowedTypes={["image"]}
                    render={({ open }) => (
                      <Button onClick={open} isSecondary>
                        {fallbackImage
                          ? __("Replace Fallback Image", "fnesl")
                          : __("Select Fallback Image", "fnesl")}
                      </Button>
                    )}
                  />
                </MediaUploadCheck>
              </>
            )}
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          {backgroundType === "video" && backgroundVideo && (
            <video
              autoPlay
              muted
              loop
              playsInline
              poster={fallbackImage ? fallbackImage.url : ""}
              className="project-hero__video"
            >
              <source src={backgroundVideo.url} type={backgroundVideo.mime} />
            </video>
          )}
          <div className="project-hero__inner">
            <InnerBlocks />
          </div>
        </div>
      </>
    );
  },

  save: ({ attributes }) => {
    const { backgroundType, backgroundImage, backgroundVideo, fallbackImage } =
      attributes;

    const blockProps = useBlockProps.save({
      className: "project-hero",
      style:
        backgroundType === "image" && backgroundImage
          ? { backgroundImage: `url(${backgroundImage.url})` }
          : {},
    });

    return (
      <div {...blockProps}>
        {backgroundType === "video" && backgroundVideo && (
          <video
            autoPlay
            muted
            loop
            playsInline
            poster={fallbackImage ? fallbackImage.url : ""}
            className="project-hero__video"
          >
            <source src={backgroundVideo.url} type={backgroundVideo.mime} />
          </video>
        )}
        <div className="project-hero__inner">
          <InnerBlocks.Content />
        </div>
      </div>
    );
  },
});
