<?php
/**
 * Render callback for Affiliations block.
 *
 * $attributes keys:
 *   heading     string
 *   subheading  string
 *   mode        'latest' | 'random' | 'pick'
 *   displayType 'grid' | 'carousel'
 *   count       int
 *   pickedIds   int[]
 */

$mode       = $attributes['mode'] ?? 'latest';
$display    = $attributes['displayType'] ?? 'grid';
$count      = max( 1, (int) ( $attributes['count'] ?? 2 ) );
$picked_ids = array_filter( array_map( 'intval', $attributes['pickedIds'] ?? [] ) );

$query_args = [
	'post_type'      => 'affiliation',
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

$affiliations = get_posts( $query_args );

if ( empty( $affiliations ) ) {
	return '';
}

$items = [];
foreach ( $affiliations as $affiliation ) {
	$logo_id = (int) get_post_meta( $affiliation->ID, 'affiliation_svg_logo_id', true );
	$url     = (string) get_post_meta( $affiliation->ID, 'affiliation_url', true );

	if ( ! $logo_id ) {
		continue;
	}

	$logo_html = wp_get_attachment_image(
		$logo_id,
		'full',
		false,
		[
			'class' => 'w-full max-h-[100px] h-auto object-contain',
			'alt'   => esc_attr( get_the_title( $affiliation->ID ) ) . ' logo',
		]
	);

	if ( ! $logo_html ) {
		continue;
	}

	$items[] = [
		'id'               => (int) $affiliation->ID,
		'title'            => get_the_title( $affiliation->ID ),
		'description_html' => wp_kses_post( wpautop( get_post_field( 'post_content', $affiliation->ID ) ) ),
		'url'              => $url ? esc_url( $url ) : '',
		'logo_html'        => $logo_html,
	];
}

if ( empty( $items ) ) {
	return '';
}

$wrapper_attrs = get_block_wrapper_attributes( [
	'class' => 'container',
] );
?>
<div <?php echo $wrapper_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( 'carousel' === $display ) : ?>
		<div
			data-affiliations-carousel
			data-config="<?php echo esc_attr( wp_json_encode( [ 'items' => $items ] ) ); ?>"
		></div>
	<?php else : ?>
	<ul class="grid grid-cols-2 gap-6">
		<?php foreach ( $items as $item ) : ?>
		<li class="bg-white rounded-sm p-6 grid grid-cols-[1fr_2fr] gap-6">
			<div class="flex items-center justify-center">
				<?php if ( $item['url'] ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="block w-full">
						<?php echo $item['logo_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php else : ?>
					<?php echo $item['logo_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
			<div class="flex flex-col justify-center items-start self-start">
				<h3 class="text-xl mb-2"><?php echo esc_html( $item['title'] ); ?></h3>
				<div class="text-sm text-primary-900">
					<?php echo $item['description_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( $item['url'] ) : ?>
				<a
					href="<?php echo esc_url( $item['url'] ); ?>"
					class="mt-6 text-sm text-primary-500 flex gap-3"
					target="_blank"
					rel="noopener noreferrer"
				>
					<span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
						<svg class="aspect-square h-3 fill-current" aria-hidden="true">
							<use xlink:href="#icons_arrow_east"></use>
						</svg>
					</span>
					Visit Website
				</a>
				<?php endif; ?>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
