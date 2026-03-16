<?php
/**
 * Render callback for fnesl/responsive-grid block.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Rendered inner blocks HTML.
 */

$cols_base = max( 1, intval( $attributes['colsBase'] ?? 1 ) );
$cols_sm   = intval( $attributes['colsSm']   ?? 0 );
$cols_md   = intval( $attributes['colsMd']   ?? 0 );
$cols_lg   = intval( $attributes['colsLg']   ?? 0 );
$cols_xl   = intval( $attributes['colsXl']   ?? 0 );
$cols_2xl  = intval( $attributes['cols2xl']  ?? 0 );
$gap       = sanitize_key( $attributes['gap'] ?? 'md' );

$gap_map = [
	'none' => '0',
	'xs'   => '0.5rem',
	'sm'   => '1rem',
	'md'   => '1.5rem',
	'lg'   => '2rem',
	'xl'   => '3rem',
];

$gap_value = $gap_map[ $gap ] ?? '1.5rem';
$block_id  = 'rg-' . wp_unique_id();

// Build scoped CSS for this block instance.
$css  = ".{$block_id}{display:grid;grid-template-columns:repeat({$cols_base},minmax(0,1fr));gap:{$gap_value};}";

if ( $cols_sm > 0 ) {
	$css .= "@media(min-width:640px){.{$block_id}{grid-template-columns:repeat({$cols_sm},minmax(0,1fr));}}";
}
if ( $cols_md > 0 ) {
	$css .= "@media(min-width:768px){.{$block_id}{grid-template-columns:repeat({$cols_md},minmax(0,1fr));}}";
}
if ( $cols_lg > 0 ) {
	$css .= "@media(min-width:1024px){.{$block_id}{grid-template-columns:repeat({$cols_lg},minmax(0,1fr));}}";
}
if ( $cols_xl > 0 ) {
	$css .= "@media(min-width:1280px){.{$block_id}{grid-template-columns:repeat({$cols_xl},minmax(0,1fr));}}";
}
if ( $cols_2xl > 0 ) {
	$css .= "@media(min-width:1536px){.{$block_id}{grid-template-columns:repeat({$cols_2xl},minmax(0,1fr));}}";
}

$wrapper_attrs = get_block_wrapper_attributes( [ 'class' => $block_id ] );
?>
<style><?php echo $css; ?></style>
<div <?php echo $wrapper_attrs; ?>><?php echo $content; ?></div>
