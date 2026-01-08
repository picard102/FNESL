<?php
/**
 * Render: Project Archive (React mount)
 *
 * - Outputs ONLY a mount point + JSON config in data-config
 * - React fetches filters/projects from the single JSON endpoint:
 *   POST /wp-json/fnesl/v1/project-archive
 *
 * Important:
 * - DO NOT declare named/global functions here (file can be included multiple times).
 * - Echo output only.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attrs = wp_parse_args( $attributes ?? [], [
	'showFilters' => [ 'expertise', 'partners', 'location', 'client', 'awards' ],
	'filterMode'  => 'and', // and | or
	'heading'     => 'Projects',
	'perPage'     => 12,    // internal page size for pagination (can be hidden in editor)
] );

// Sanitize + normalize
$allowed_tax = [ 'expertise', 'partners', 'location', 'client', 'awards' ];

$show = $attrs['showFilters'];
if ( is_string( $show ) ) {
	$show = array_filter( array_map( 'trim', explode( ',', $show ) ) );
}
$show = is_array( $show ) ? $show : [];
$show = array_values( array_intersect( $allowed_tax, array_map( 'sanitize_key', $show ) ) );

$mode = ( (string) ( $attrs['filterMode'] ?? 'and' ) === 'or' ) ? 'or' : 'and';

$heading = trim( (string) ( $attrs['heading'] ?? '' ) );

// Per page (required for pagination to exist)
$per_page = (int) ( $attrs['perPage'] ?? 12 );
$per_page = max( 1, min( 100, $per_page > 0 ? $per_page : 12 ) );

$config = [
	'show'    => $show,
	'mode'    => $mode,
	'perPage' => $per_page,
	'heading' => $heading,
];

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'w-full',
] );

$uid = 'fnesl-project-archive-' . wp_unique_id();
?>

<div
	id="<?php echo esc_attr( $uid ); ?>"
<?php echo $wrapper_attrs; ?>
	data-project-archive
	data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
>
	<!-- Fallback / pre-hydration UI (no JS / slow JS) -->
	<div class="rounded-2xl border border-black/10 bg-white/60 p-4 text-black/70">
		Loading projects…
	</div>
</div>