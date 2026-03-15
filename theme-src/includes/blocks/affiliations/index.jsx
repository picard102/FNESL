import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  RangeControl,
  CheckboxControl,
  Spinner,
} from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { useMemo } from "@wordpress/element";

// ─── Card preview component ──────────────────────────────────────────────────
// Mirrors the front-end li layout. Each card independently resolves its logo
// via getMedia so loading states are per-card rather than blocking the whole grid.

const AffiliationCard = ( { affiliation } ) => {
  const logoId = affiliation.meta?.affiliation_svg_logo_id || 0;
  const url    = affiliation.meta?.affiliation_url || "";
  const title  = affiliation.title?.rendered || `#${ affiliation.id }`;

  // undefined = still resolving, null = no logo set, string = src URL
  const logoSrc = useSelect(
    ( select ) => {
      if ( ! logoId ) return null;
      const media = select( "core" ).getMedia( logoId );
      return media === undefined ? undefined : ( media?.source_url ?? null );
    },
    [ logoId ],
  );

  return (
    <li
      style={ {
        background: "#fff",
        borderRadius: "2px",
        padding: "24px",
        display: "grid",
        gridTemplateColumns: "1fr 2fr",
        gap: "24px",
        listStyle: "none",
      } }
    >
      {/* ── Logo column ── */}
      <div
        style={ {
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        } }
      >
        { logoSrc === undefined ? (
          <Spinner />
        ) : logoSrc ? (
          <img
            src={ logoSrc }
            alt={ `${ title } logo` }
            style={ {
              width: "100%",
              maxHeight: "100px",
              height: "auto",
              objectFit: "contain",
            } }
          />
        ) : (
          <div
            style={ {
              width: "100%",
              height: "80px",
              border: "1px dashed #ccc",
              borderRadius: "4px",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              fontSize: "11px",
              color: "#aaa",
            } }
          >
            { __( "No logo", "fnesl" ) }
          </div>
        ) }
      </div>

      {/* ── Text column ── */}
      <div
        style={ {
          display: "flex",
          flexDirection: "column",
          justifyContent: "center",
          alignItems: "flex-start",
        } }
      >
        <h3
          style={ { fontSize: "1.25rem", marginTop: 0, marginBottom: "8px" } }
          dangerouslySetInnerHTML={ { __html: title } }
        />

      </div>
    </li>
  );
};

// ─── Block registration ───────────────────────────────────────────────────────

registerBlockType( "fnesl/affiliations", {
  edit: ( { attributes, setAttributes } ) => {
    const { mode, count, pickedIds } = attributes;

    const affiliations = useSelect(
      ( select ) =>
        select( "core" ).getEntityRecords( "postType", "affiliation", {
          per_page: 100,
          orderby: "title",
          order: "asc",
          status: "publish",
          context: "edit", // ensures meta fields are included in the response
        } ),
      [],
    );

    const blockProps = useBlockProps();

    const previewItems = useMemo( () => {
      if ( ! affiliations ) return [];
      if ( mode === "pick" ) {
        return affiliations.filter( ( a ) => pickedIds.includes( a.id ) );
      }
      const sorted = [ ...affiliations ];
      if ( mode === "random" ) {
        sorted.sort( () => Math.random() - 0.5 );
      } else {
        sorted.sort( ( a, b ) => new Date( b.date ) - new Date( a.date ) );
      }
      return sorted.slice( 0, count );
    }, [ affiliations, mode, count, pickedIds ] );

    const togglePicked = ( id, checked ) => {
      const next = checked
        ? [ ...pickedIds, id ]
        : pickedIds.filter( ( v ) => v !== id );
      setAttributes( { pickedIds: next } );
    };

    return (
      <>
        <InspectorControls>
          <PanelBody title={ __( "Display", "fnesl" ) } initialOpen={ true }>
            <SelectControl
              label={ __( "Mode", "fnesl" ) }
              value={ mode }
              options={ [
                { label: __( "Latest", "fnesl" ),        value: "latest" },
                { label: __( "Random", "fnesl" ),        value: "random" },
                { label: __( "Pick specific", "fnesl" ), value: "pick"   },
              ] }
              onChange={ ( v ) => setAttributes( { mode: v } ) }
            />

            { mode !== "pick" && (
              <RangeControl
                label={ __( "Number to show", "fnesl" ) }
                value={ count }
                min={ 1 }
                max={ 12 }
                onChange={ ( v ) => setAttributes( { count: v } ) }
              />
            ) }

            { mode === "pick" && (
              <div>
                <p style={ { marginBottom: "8px", fontWeight: 600 } }>
                  { __( "Select affiliations", "fnesl" ) }
                </p>
                { ! affiliations ? (
                  <Spinner />
                ) : affiliations.length === 0 ? (
                  <p>{ __( "No affiliations found.", "fnesl" ) }</p>
                ) : (
                  affiliations.map( ( a ) => (
                    <CheckboxControl
                      key={ a.id }
                      label={ a.title?.rendered || `#${ a.id }` }
                      checked={ pickedIds.includes( a.id ) }
                      onChange={ ( checked ) => togglePicked( a.id, checked ) }
                    />
                  ) )
                ) }
              </div>
            ) }
          </PanelBody>
        </InspectorControls>

        <div { ...blockProps }>
          { ! affiliations ? (
            <div style={ { padding: "24px", textAlign: "center" } }>
              <Spinner />
            </div>
          ) : previewItems.length === 0 ? (
            <p style={ { color: "#999", fontStyle: "italic", padding: "16px" } }>
              { mode === "pick"
                ? __( "No affiliations selected.", "fnesl" )
                : __( "No affiliations found.", "fnesl" ) }
            </p>
          ) : (
            <ul
              style={ {
                display: "grid",
                gridTemplateColumns: "repeat(2, 1fr)",
                gap: "24px",
                margin: 0,
                padding: 0,
              } }
            >
              { previewItems.map( ( a ) => (
                <AffiliationCard key={ a.id } affiliation={ a } />
              ) ) }
            </ul>
          ) }
        </div>
      </>
    );
  },

  save: () => null,
} );
