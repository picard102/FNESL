import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("fnesl/hello-world", {
  title: __("Hello World", "fnesl"),
  icon: "smiley",
  category: "widgets",
  edit: () => <p>{__("Hello from the editor!", "fnesl")}</p>,
  save: () => <p>{__("Hello from the frontend!", "fnesl")}</p>,
});
