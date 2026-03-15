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

// ─── Card preview component ───────────────────────────────────────────────────
// Mirrors the front-end li layout. Each card independently resolves its
// featured image via getMedia.

const NewsCard = ( { post } ) => {
  const thumbnailId = post.featured_media || 0;
  const title       = post.title?.rendered || `#${ post.id }`;
  const date        = post.date
    ? new Date( post.date ).toLocaleDateString( "en-US", {
        month: "short",
        day:   "2-digit",
        year:  "numeric",
      } )
    : "";

  // undefined = still resolving, null = no image, string = src URL
  const imageSrc = useSelect(
    ( select ) => {
      if ( ! thumbnailId ) return null;
      const media = select( "core" ).getMedia( thumbnailId );
      return media === undefined
        ? undefined
        : ( media?.media_details?.sizes?.medium?.source_url
            ?? media?.source_url
            ?? null );
    },
    [ thumbnailId ],
  );

  return (
    <li
      style={ {
        background: "#fff",
        borderRadius: "2px",
        padding: "12px",
        display: "flex",
        flexDirection: "column",
        gap: "24px",
        listStyle: "none",
      } }
    >
      {/* ── Image / header ── */}
      <div
        style={ {
          position: "relative",
          aspectRatio: "16 / 9",
          background: "#1e293b",
          borderRadius: "2px",
          overflow: "hidden",
          display: "grid",
          gridTemplateColumns: "1fr",
          gridTemplateRows: "1fr",
        } }
      >



        {/* Featured image */}
        { imageSrc === undefined ? (
          <div
            style={ {
              gridColumn: 1,
              gridRow: 1,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              opacity: 0.4,
            } }
          >
            <Spinner />
          </div>
        ) : imageSrc ? (
          <img
            src={ imageSrc }
            alt=""
            style={ {
              gridColumn: 1,
              gridRow: 1,
              width: "100%",
              height: "100%",
              objectFit: "cover",
              opacity: 0.5,
            } }
          />
        ) : (
          /* No image — subtle placeholder so card shape is clear */
          <div
            style={ {
              gridColumn: 1,
              gridRow: 1,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              fontSize: "11px",
              color: "#666",
              opacity: 0.6,
            } }
          >
            { __( "No featured image", "fnesl" ) }
          </div>
        ) }
      </div>

      {/* ── Text column ── */}
      <div
        style={ {
          display: "flex",
          flexDirection: "column",
          alignItems: "flex-start",
        } }
      >
        <h3
          style={ {
            fontSize: "1.125rem",
            marginTop: 0,
            marginBottom: "8px",
            color: "var(--wp--preset--color--primary-600, #1e40af)",
          } }
          dangerouslySetInnerHTML={ { __html: title } }
        />

      </div>
    </li>
  );
};

// ─── Block registration ───────────────────────────────────────────────────────

registerBlockType( "fnesl/news-cards", {
  edit: ( { attributes, setAttributes } ) => {
    const { mode, count, pickedIds } = attributes;

    const posts = useSelect(
      ( select ) =>
        select( "core" ).getEntityRecords( "postType", "post", {
          per_page: 100,
          orderby: "date",
          order: "desc",
          status: "publish",
          _fields: "id,title,date,excerpt,featured_media",
        } ),
      [],
    );

    const blockProps = useBlockProps();

    const previewItems = useMemo( () => {
      if ( ! posts ) return [];
      if ( mode === "pick" ) {
        return posts.filter( ( p ) => pickedIds.includes( p.id ) );
      }
      const sorted = [ ...posts ];
      if ( mode === "random" ) {
        sorted.sort( () => Math.random() - 0.5 );
      }
      // "latest" — already sorted by date desc from the API
      return sorted.slice( 0, count );
    }, [ posts, mode, count, pickedIds ] );

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
                  { __( "Select posts", "fnesl" ) }
                </p>
                { ! posts ? (
                  <Spinner />
                ) : posts.length === 0 ? (
                  <p>{ __( "No posts found.", "fnesl" ) }</p>
                ) : (
                  posts.map( ( p ) => (
                    <CheckboxControl
                      key={ p.id }
                      label={ p.title?.rendered || `#${ p.id }` }
                      checked={ pickedIds.includes( p.id ) }
                      onChange={ ( checked ) => togglePicked( p.id, checked ) }
                    />
                  ) )
                ) }
              </div>
            ) }
          </PanelBody>
        </InspectorControls>

        <div { ...blockProps }>
          { ! posts ? (
            <div style={ { padding: "24px", textAlign: "center" } }>
              <Spinner />
            </div>
          ) : previewItems.length === 0 ? (
            <p style={ { color: "#999", fontStyle: "italic", padding: "16px" } }>
              { mode === "pick"
                ? __( "No posts selected.", "fnesl" )
                : __( "No posts found.", "fnesl" ) }
            </p>
          ) : (
            <ul
              style={ {
                display: "grid",
                gridTemplateColumns: "repeat(3, 1fr)",
                gap: "24px",
                margin: 0,
                padding: 0,
              } }
            >
              { previewItems.map( ( p ) => (
                <NewsCard key={ p.id } post={ p } />
              ) ) }
            </ul>
          ) }
        </div>
      </>
    );
  },

  save: () => null,
} );
