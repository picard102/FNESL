/* global wp */
(function () {
  const { registerPlugin } = wp.plugins;
  const { PluginDocumentSettingPanel } = wp.editPost;
  const { TextControl, Button } = wp.components;
  const { useSelect, useDispatch } = wp.data;
  const { useMemo } = wp.element;

  const META_URL = "affiliation_url";
  const META_FULL = "affiliation_svg_logo_id";
  const META_1C = "affiliation_svg_logo_1c_id";

  function pickMediaSVG(onSelect) {
    // Use the classic media frame (works fine inside Gutenberg)
    const frame = wp.media({
      title: "Select an SVG",
      button: { text: "Use this SVG" },
      multiple: false,
      library: { type: "image/svg+xml" },
    });

    frame.on("select", function () {
      const attachment = frame.state().get("selection").first().toJSON();
      onSelect(attachment);
    });

    frame.open();
  }

  function AttachmentRow({ label, attachmentId, onChangeId }) {
    const attachment = useSelect(
      (select) => {
        if (!attachmentId) return null;
        return select("core").getMedia(attachmentId);
      },
      [attachmentId],
    );

    const previewUrl = attachment?.source_url || "";
    const filename = attachment?.title?.rendered || "";

    return wp.element.createElement(
      "div",
      { style: { marginBottom: "14px" } },
      wp.element.createElement(
        "div",
        { style: { fontWeight: 600, marginBottom: "6px" } },
        label,
      ),

      previewUrl
        ? wp.element.createElement(
            "div",
            {
              style: {
                height: "100px",
                padding: "5px",
                border: "1px solid #ccd0d4",
                backgroundColor: "#fefefe",
                backgroundImage:
                  "linear-gradient(45deg,#e5e5e5 25%,transparent 25%)," +
                  "linear-gradient(-45deg,#e5e5e5 25%,transparent 25%)," +
                  "linear-gradient(45deg,transparent 75%,#e5e5e5 75%)," +
                  "linear-gradient(-45deg,transparent 75%,#e5e5e5 75%)",
                backgroundSize: "16px 16px",
                backgroundPosition: "0 0,0 8px,8px -8px,-8px 0",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                overflow: "hidden",
                marginBottom: "6px",
              },
            },
            wp.element.createElement("img", {
              src: previewUrl,
              alt: "",
              style: {
                maxWidth: "100%",
                maxHeight: "100%",
                width: "auto",
                height: "auto",
                display: "block",
              },
            }),
          )
        : wp.element.createElement(
            "div",
            { style: { color: "#666", marginBottom: "6px" } },
            "No SVG selected.",
          ),

      previewUrl
        ? wp.element.createElement(
            "div",
            {
              style: {
                fontSize: "11px",
                color: "#555",
                wordBreak: "break-all",
                marginBottom: "8px",
              },
            },
            previewUrl,
          )
        : null,

      wp.element.createElement(
        "div",
        { style: { display: "flex", gap: "8px", flexWrap: "wrap" } },
        wp.element.createElement(
          Button,
          {
            variant: "secondary",
            onClick: () =>
              pickMediaSVG((att) => {
                onChangeId(att.id || 0);
              }),
          },
          attachmentId ? "Change SVG" : "Select SVG",
        ),
        wp.element.createElement(
          Button,
          {
            variant: "secondary",
            isDestructive: true,
            disabled: !attachmentId,
            onClick: () => onChangeId(0),
          },
          "Remove",
        ),
      ),
    );
  }

  function AffiliationPanel() {
    const postType = useSelect(
      (select) => select("core/editor").getCurrentPostType(),
      [],
    );
    const meta = useSelect(
      (select) => select("core/editor").getEditedPostAttribute("meta") || {},
      [],
    );
    const { editPost } = useDispatch("core/editor");

    // Safety: only show on affiliation posts
    if (postType !== "affiliation") return null;

    const urlValue = meta[META_URL] || "";
    const fullId = Number(meta[META_FULL] || 0);
    const oneId = Number(meta[META_1C] || 0);

    const setMeta = (key, value) => {
      editPost({ meta: { ...meta, [key]: value } });
    };

    return wp.element.createElement(
      PluginDocumentSettingPanel,
      {
        name: "affiliation-details-panel",
        title: "Affiliation Details",
        className: "affiliation-details-panel",
      },

      wp.element.createElement(TextControl, {
        label: "Affiliation URL",
        value: urlValue,
        placeholder: "https://example.com",
        onChange: (v) => setMeta(META_URL, v),
      }),

      wp.element.createElement("div", { style: { height: "10px" } }),

      wp.element.createElement(AttachmentRow, {
        label: "Full Color SVG",
        attachmentId: fullId,
        onChangeId: (id) => setMeta(META_FULL, Number(id) || 0),
      }),

      wp.element.createElement(AttachmentRow, {
        label: "Single Color SVG (optional)",
        attachmentId: oneId,
        onChangeId: (id) => setMeta(META_1C, Number(id) || 0),
      }),
    );
  }

  registerPlugin("affiliation-details-sidebar", {
    render: AffiliationPanel,
  });
})();
