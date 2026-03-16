<?php
/**
 * Render: Project Cards (React mount)
 *
 * - Outputs ONLY a mount point + JSON config in data-config.
 * - React fetches and renders the carousel using ProjectCard.
 *
 * Important:
 * - DO NOT declare named/global functions here (file can be included multiple times).
 * - Echo output only.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attrs = wp_parse_args( $attributes ?? [], [
	'orderMode'   => 'custom',
	'order'       => 'ASC',
	'postsToShow' => 12,
] );

$limit = max( 1, min( 100, (int) $attrs['postsToShow'] ) );

$config = [
	'perPage' => $limit,
];

$uid = 'fnesl-project-cards-' . wp_unique_id();
?>

<div
	id="<?php echo esc_attr( $uid ); ?>"
	data-project-cards
	data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
>
	<!-- pre-hydration placeholder -->
</div>
