<?php
/**
 * Render callback for News Cards block.
 *
 * $attributes keys:
 *   mode       'latest' | 'random' | 'pick'
 *   count      int
 *   pickedIds  int[]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mode       = $attributes['mode'] ?? 'latest';
$count      = max( 1, (int) ( $attributes['count'] ?? 2 ) );
$picked_ids = array_filter( array_map( 'intval', $attributes['pickedIds'] ?? [] ) );

$query_args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $count,
];

if ( $mode === 'pick' && ! empty( $picked_ids ) ) {
	$query_args['post__in']       = $picked_ids;
	$query_args['orderby']        = 'post__in';
	$query_args['posts_per_page'] = count( $picked_ids );
} elseif ( $mode === 'random' ) {
	$query_args['orderby'] = 'rand';
} else {
	// latest (default)
	$query_args['orderby'] = 'date';
	$query_args['order']   = 'DESC';
}

$newsposts = get_posts( $query_args );

if ( empty( $newsposts ) ) {
	return '';
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'container',
] );
?>
<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<ul class="grid grid-cols-2 gap-6">
		<?php foreach ( $newsposts as $newspost ) : ?>
		<li class="bg-white rounded-sm p-3 flex flex-col gap-6">

			<div class="flex items-center justify-center fill-current aspect-video bg-primary-900 grid grid-cols-1 grid-rows-1 overflow-hidden rounded-sm relative w-full">
				<div class="text-white text-xs col-start-1 row-start-1 self-start flex items-center gap-2 z-10 m-3">
					<div class="rounded-full bg-current w-2 h-2"></div>
					News
					<div class="rounded-sm bg-primary-500 w-0.5 h-3 mx-2"></div>
					<?php echo esc_html( get_the_date( 'M d, Y', $newspost->ID ) ); ?>
				</div>
				<?php echo wp_get_attachment_image(
					get_post_thumbnail_id( $newspost->ID ),
					'medium',
					false,
					[ 'class' => 'w-full h-full object-cover col-start-1 row-start-1 opacity-50' ]
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="flex flex-col justify-center items-start self-start">
				<h3 class="text-lg text-primary-600 mb-2">
					<?php echo esc_html( get_the_title( $newspost->ID ) ); ?>
				</h3>
				<div class="text-sm text-primary-900">
					<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $newspost->ID ) ) ); ?>
				</div>
				<a
					href="<?php echo esc_url( get_permalink( $newspost->ID ) ); ?>"
					class="mt-6 text-sm text-primary-500 flex gap-3"
					rel="noopener noreferrer"
				>
					<span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
						<svg class="aspect-square h-3 fill-current" aria-hidden="true">
							<use xlink:href="#icons_arrow_east"></use>
						</svg>
					</span>
					Read More
				</a>
			</div>

		</li>
		<?php endforeach; ?>
	</ul>
</div>
