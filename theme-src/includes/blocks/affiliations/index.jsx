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

const AffiliationCard = ( { affiliation, contentStyle } ) => {
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
        gridTemplateColumns:
          contentStyle === "logo" ? "1fr" : "1fr 2fr",
        gap: contentStyle === "logo" ? "0" : "24px",
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
      { contentStyle !== "logo" && (
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
      ) }
    </li>
  );
};

// ─── Block registration ───────────────────────────────────────────────────────

registerBlockType( "fnesl/affiliations", {
  edit: ( { attributes, setAttributes } ) => {
    const { mode, displayType, contentStyle, groupTermId, count, pickedIds } = attributes;

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

    const groups = useSelect(
      ( select ) =>
        select( "core" ).getEntityRecords( "taxonomy", "placement", {
          per_page: 100,
          hide_empty: false,
          orderby: "name",
          order: "asc",
        } ),
      [],
    );

    const blockProps = useBlockProps();

    const taxonomyAffiliations = useMemo( () => {
      if ( ! affiliations ) return [];
      if ( ! groupTermId ) return [];
      return affiliations.filter( ( a ) =>
        Array.isArray( a.placement ) &&
        a.placement.includes( groupTermId )
      );
    }, [ affiliations, groupTermId ] );

    const previewItems = useMemo( () => {
      if ( ! affiliations ) return [];
      if ( mode === "pick" ) {
        return affiliations.filter( ( a ) => pickedIds.includes( a.id ) );
      }

      if ( mode === "taxonomy" ) {
        return taxonomyAffiliations.slice( 0, count );
      }

      const sorted = [ ...affiliations ];
      if ( mode === "random" ) {
        sorted.sort( () => Math.random() - 0.5 );
      } else {
        sorted.sort( ( a, b ) => new Date( b.date ) - new Date( a.date ) );
      }
      return sorted.slice( 0, count );
    }, [ affiliations, taxonomyAffiliations, mode, count, pickedIds ] );

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
                { label: __( "By taxonomy", "fnesl" ),   value: "taxonomy" },
                { label: __( "Pick specific", "fnesl" ), value: "pick"   },
              ] }
              onChange={ ( v ) => setAttributes( { mode: v } ) }
            />

            <SelectControl
              label={ __( "Layout", "fnesl" ) }
              value={ displayType }
              options={ [
                { label: __( "Grid", "fnesl" ), value: "grid" },
                { label: __( "Carousel", "fnesl" ), value: "carousel" },
              ] }
              onChange={ ( v ) => setAttributes( { displayType: v } ) }
            />

            <SelectControl
              label={ __( "Card style", "fnesl" ) }
              value={ contentStyle }
              options={ [
                { label: __( "Regular", "fnesl" ), value: "full" },
                { label: __( "Logo only", "fnesl" ), value: "logo" },
              ] }
              onChange={ ( v ) => setAttributes( { contentStyle: v } ) }
            />

            { mode === "taxonomy" && (
              <SelectControl
                label={ __( "Placement", "fnesl" ) }
                value={ String( groupTermId ) }
                options={ [
                  { label: __( "Select a placement", "fnesl" ), value: "0" },
                  ...( groups || [] ).map( ( group ) => ( {
                    label: group.name,
                    value: String( group.id ),
                  } ) ),
                ] }
                onChange={ ( v ) =>
                  setAttributes( { groupTermId: Number( v ) || 0 } )
                }
              />
            ) }

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
          { ! affiliations || ! groups ? (
            <div style={ { padding: "24px", textAlign: "center" } }>
              <Spinner />
            </div>
          ) : previewItems.length === 0 ? (
            <p style={ { color: "#999", fontStyle: "italic", padding: "16px" } }>
              { mode === "pick"
                ? __( "No affiliations selected.", "fnesl" )
                : mode === "taxonomy"
                  ? __( "No affiliations found for the selected group.", "fnesl" )
                  : __( "No affiliations found.", "fnesl" ) }
            </p>
          ) : (
            <ul
              style={ {
                display: "grid",
                gridTemplateColumns:
                  displayType === "carousel"
                    ? "repeat(auto-fit, minmax(320px, 1fr))"
                    : "repeat(2, 1fr)",
                gap: "24px",
                margin: 0,
                padding: 0,
                overflowX: displayType === "carousel" ? "auto" : "visible",
                gridAutoFlow: displayType === "carousel" ? "column" : "row",
                gridAutoColumns:
                  displayType === "carousel" ? "minmax(320px, 420px)" : "auto",
              } }
            >
              { previewItems.map( ( a ) => (
                <AffiliationCard
                  key={ a.id }
                  affiliation={ a }
                  contentStyle={ contentStyle }
                />
              ) ) }
            </ul>
          ) }
        </div>
      </>
    );
  },

  save: () => null,
} );
