import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import {
  useBlockProps,
  InspectorControls,
  useInnerBlocksProps,
  InnerBlocks,
} from "@wordpress/block-editor";
import { PanelBody, RangeControl, SelectControl } from "@wordpress/components";

const GAP_OPTIONS = [
  { label: __( "None (0)",      "fnesl" ), value: "none" },
  { label: __( "XS (0.5rem)",  "fnesl" ), value: "xs"   },
  { label: __( "SM (1rem)",    "fnesl" ), value: "sm"   },
  { label: __( "MD (1.5rem)",  "fnesl" ), value: "md"   },
  { label: __( "LG (2rem)",    "fnesl" ), value: "lg"   },
  { label: __( "XL (3rem)",    "fnesl" ), value: "xl"   },
];

const GAP_MAP = {
  none: "0",
  xs:   "0.5rem",
  sm:   "1rem",
  md:   "1.5rem",
  lg:   "2rem",
  xl:   "3rem",
};

registerBlockType( "fnesl/responsive-grid", {
  edit: ( { attributes, setAttributes } ) => {
    const { colsBase, colsSm, colsMd, colsLg, colsXl, cols2xl, gap } = attributes;

    // Show the largest explicitly-set breakpoint value as the editor preview.
    const previewCols = colsLg || colsMd || colsSm || colsBase || 1;

    const blockProps = useBlockProps( {
      style: {
        display:             "grid",
        gridTemplateColumns: `repeat(${ previewCols }, minmax(0, 1fr))`,
        gap:                 GAP_MAP[ gap ] ?? "1.5rem",
      },
    } );

    const innerBlocksProps = useInnerBlocksProps( blockProps );

    const BreakpointControl = ( { label, attrKey, value, required = false } ) => (
      <RangeControl
        label={ label }
        value={ value }
        min={ required ? 1 : 0 }
        max={ 12 }
        onChange={ ( v ) => setAttributes( { [ attrKey ]: v ?? 0 } ) }
        allowReset={ ! required }
        resetFallbackValue={ 0 }
        help={
          ! required && value === 0
            ? __( "Inherits from previous breakpoint", "fnesl" )
            : undefined
        }
      />
    );

    return (
      <>
        <InspectorControls>
          <PanelBody title={ __( "Grid Columns", "fnesl" ) } initialOpen={ true }>
            <p style={ { margin: "0 0 12px", fontSize: "11px", color: "#757575" } }>
              { __( "Set 0 to inherit from the previous breakpoint.", "fnesl" ) }
            </p>

            <BreakpointControl
              label={ __( "Base (all screens)", "fnesl" ) }
              attrKey="colsBase"
              value={ colsBase }
              required
            />
            <BreakpointControl
              label={ __( "SM · 640px+", "fnesl" ) }
              attrKey="colsSm"
              value={ colsSm }
            />
            <BreakpointControl
              label={ __( "MD · 768px+", "fnesl" ) }
              attrKey="colsMd"
              value={ colsMd }
            />
            <BreakpointControl
              label={ __( "LG · 1024px+", "fnesl" ) }
              attrKey="colsLg"
              value={ colsLg }
            />
            <BreakpointControl
              label={ __( "XL · 1280px+", "fnesl" ) }
              attrKey="colsXl"
              value={ colsXl }
            />
            <BreakpointControl
              label={ __( "2XL · 1536px+", "fnesl" ) }
              attrKey="cols2xl"
              value={ cols2xl }
            />
          </PanelBody>

          <PanelBody title={ __( "Gap", "fnesl" ) } initialOpen={ false }>
            <SelectControl
              label={ __( "Gap between items", "fnesl" ) }
              value={ gap }
              options={ GAP_OPTIONS }
              onChange={ ( v ) => setAttributes( { gap: v } ) }
            />
          </PanelBody>
        </InspectorControls>

        <div { ...innerBlocksProps } />
      </>
    );
  },

  save: () => <InnerBlocks.Content />,
} );
