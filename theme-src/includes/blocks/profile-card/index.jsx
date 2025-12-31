import { registerBlockType } from "@wordpress/blocks";
import { SelectControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useBlockProps } from "@wordpress/block-editor";

registerBlockType("fnesl/profile-card", {
  edit({ attributes, setAttributes }) {
    const { profileId } = attributes;

    const profiles = useSelect(
      (select) =>
        select("core").getEntityRecords("postType", "profile", {
          per_page: -1,
        }),
      []
    );

    const options = [
      { label: "Select Profile", value: 0 },
      ...(profiles || []).map((p) => ({
        label: p.title.rendered,
        value: p.id,
      })),
    ];

    return (
      <div {...useBlockProps()}>
        <SelectControl
          label="Profile"
          value={profileId || 0}
          options={options}
          onChange={(value) =>
            setAttributes({ profileId: parseInt(value, 10) })
          }
        />
      </div>
    );
  },

  save() {
    return null; // server render
  },
});


