/* global wp */
(function () {
  const TAXONOMY = "expertise";
  const ICON_KEY = "fnesl_term_icon_svg_id";

  const isOnExpertiseTermScreen = () => {
    // WP term screens include these params
    const params = new URLSearchParams(window.location.search);
    const taxonomy = params.get("taxonomy");
    return taxonomy === TAXONOMY;
  };

  const ready = (fn) => {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", fn, { once: true });
    } else fn();
  };

  ready(() => {
    if (!isOnExpertiseTermScreen()) return;
    if (!window.wp?.media) {
      console.warn(
        "wp.media not available. Did you enqueue wp_enqueue_media()?",
      );
      return;
    }

    const field = document.getElementById("fnesl-term-icon");
    const preview = document.getElementById("fnesl-term-icon-preview");
    const btnUpload = document.getElementById("fnesl-term-icon-upload");
    const btnRemove = document.getElementById("fnesl-term-icon-remove");

    if (!field || !btnUpload) return;

    let frame;

    const setIcon = (id, url) => {
      field.value = id ? String(id) : "";
      if (preview) {
        if (url) {
          preview.src = url;
          preview.style.display = "";
        } else {
          preview.src = "";
          preview.style.display = "none";
        }
      }
      if (btnRemove) btnRemove.style.display = id ? "" : "none";
    };

    btnUpload.addEventListener("click", (e) => {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: "Choose an SVG Icon",
        button: { text: "Use this SVG" },
        multiple: false,
        library: { type: "image/svg+xml" },
      });

      frame.on("select", () => {
        const att = frame.state().get("selection").first().toJSON();
        setIcon(att.id, att.url);
      });

      frame.open();
    });

    if (btnRemove) {
      btnRemove.addEventListener("click", (e) => {
        e.preventDefault();
        setIcon("", "");
      });
    }
  });
})();
