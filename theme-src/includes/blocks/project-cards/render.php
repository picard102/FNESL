<?php
/**
 * Render: Project Cards (viewport-bleed track, content-aligned start)
 *
 * Important:
 * - DO NOT declare named/global functions here (file can be included multiple times).
 * - Echo output only.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$attrs = wp_parse_args( $attributes ?? [], [
	'orderMode'         => 'custom', // custom | date | title
	'order'             => 'ASC',    // ASC | DESC
	'postsToShow'       => 12,
	'showFeaturedImage' => true,
] );

$order_mode = (string) $attrs['orderMode'];
$order      = strtoupper( (string) $attrs['order'] ) === 'DESC' ? 'DESC' : 'ASC';
$limit      = max( 1, min( 100, (int) $attrs['postsToShow'] ) );

$args = [
	'post_type'           => 'project',
	'posts_per_page'      => $limit,
	'post_status'         => 'publish',
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
];

if ( $order_mode === 'title' ) {
	$args['orderby'] = 'title';
	$args['order']   = $order;
} elseif ( $order_mode === 'date' ) {
	$args['orderby'] = 'date';
	$args['order']   = $order;
} else {
	$args['orderby'] = [ 'menu_order' => $order, 'title' => 'ASC' ];
}

$q = new WP_Query( $args );

if ( ! $q->have_posts() ) {
	echo '<!-- fnesl/project-cards: no projects found -->';
	wp_reset_postdata();
	return;
}

/**
 * Primary expertise label:
 * - If the project has a fnesl/project-hero-v2 block with attrs.selectedExpertise > 0, use that term.
 * - Else fallback to first assigned expertise term.
 */
$fnesl_primary_expertise_label = function ( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id <= 0 ) {
		return '';
	}

	$content = get_post_field( 'post_content', $post_id );
	if ( is_string( $content ) && $content !== '' ) {
		$blocks = parse_blocks( $content );
		if ( is_array( $blocks ) ) {
			foreach ( $blocks as $b ) {
				if ( ( $b['blockName'] ?? '' ) !== 'fnesl/project-hero-v2' ) {
					continue;
				}

				$selected = (int) ( $b['attrs']['selectedExpertise'] ?? 0 );
				if ( $selected > 0 ) {
					$t = get_term( $selected, 'expertise' );
					if ( $t && ! is_wp_error( $t ) && ! empty( $t->name ) ) {
						return $t->name;
					}
				}

				break; // hero found; fall through to taxonomy fallback
			}
		}
	}

	$terms = get_the_terms( $post_id, 'expertise' );
	if ( is_wp_error( $terms ) || empty( $terms ) || ! is_array( $terms ) ) {
		return '';
	}
	return ! empty( $terms[0]->name ) ? $terms[0]->name : '';
};

$uid      = 'fnesl-project-cards-' . wp_unique_id();
$track_id = $uid . '-track';

/**
 * Use WIDE size for alignment because your page content is inside an alignwide group.
 * That makes “edge of content area” match the rest of your layout.
 */
$wide_size = 'var(--wp--style--global--content-size, 72rem)';
$gutter    = 'var(--wp--style--root--padding-left, 1rem)';
?>

<div class="w-full" data-carousel="<?php echo esc_attr( $uid ); ?>">
	<!-- Controls: aligned to the same content edge as everything else -->
	<div
		class="mx-auto mb-3 flex items-center justify-end gap-2"
		style="max-width: <?php echo esc_attr( $wide_size ); ?>; padding-inline: <?php echo esc_attr( $gutter ); ?>;"
	>
		<button
			type="button"
			class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-black/10 bg-white/80 text-black shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-black/20 disabled:opacity-40 disabled:cursor-not-allowed"
			data-carousel-prev
			aria-label="Previous projects"
			aria-controls="<?php echo esc_attr( $track_id ); ?>"
			disabled
		>
			<span aria-hidden="true">‹</span>
		</button>

		<button
			type="button"
			class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-black/10 bg-white/80 text-black shadow-sm hover:bg-white focus:outline-none focus:ring-2 focus:ring-black/20 disabled:opacity-40 disabled:cursor-not-allowed"
			data-carousel-next
			aria-label="Next projects"
			aria-controls="<?php echo esc_attr( $track_id ); ?>"
			disabled
		>
			<span aria-hidden="true">›</span>
		</button>
	</div>

	<!-- Full-bleed strip inside normal flow -->
	<div class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen overflow-visible">
		<ul
			id="<?php echo esc_attr( $track_id ); ?>"
			class="
				flex gap-6 py-3
				overflow-x-auto
				snap-x snap-mandatory
				[-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden
				outline-none
			"
			style="
				/* Offset so first card starts at the content edge (wide column) */
				padding-left: max(<?php echo esc_attr( $gutter ); ?>, calc((100vw - min(100vw, <?php echo esc_attr( $wide_size ); ?>)) / 2 + <?php echo esc_attr( $gutter ); ?>));
				padding-right: max(<?php echo esc_attr( $gutter ); ?>, calc((100vw - min(100vw, <?php echo esc_attr( $wide_size ); ?>)) / 2 + <?php echo esc_attr( $gutter ); ?>));
				scroll-padding-left: max(<?php echo esc_attr( $gutter ); ?>, calc((100vw - min(100vw, <?php echo esc_attr( $wide_size ); ?>)) / 2 + <?php echo esc_attr( $gutter ); ?>));
				scroll-padding-right: max(<?php echo esc_attr( $gutter ); ?>, calc((100vw - min(100vw, <?php echo esc_attr( $wide_size ); ?>)) / 2 + <?php echo esc_attr( $gutter ); ?>));
			"
			tabindex="0"
			role="region"
			aria-label="Projects"
			data-carousel-track
		>

		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
				<?php
					$post_id = get_the_ID();
					$link    = get_permalink( $post_id );
					$title   = get_the_title( $post_id );

					$img_url = '';
					if ( ! empty( $attrs['showFeaturedImage'] ) && has_post_thumbnail( $post_id ) ) {
						$img_url = get_the_post_thumbnail_url( $post_id, 'large' );
					}

					$primary_expertise = $fnesl_primary_expertise_label( $post_id );

					if ( ! $img_url ) {
						continue;
					}
				?>

				<li class="snap-start shrink-0 w-[280px] sm:w-[320px] lg:w-[350px] aspect-[8/12]">
					<a href="<?php echo esc_url( $link ); ?>"
						class="group h-full overflow-hidden rounded-lg  transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 grid grid-cols-1 grid-rows-2 border border-primary-300 bg-primary-400 text-white !no-underline ring-offset-primary-200
 "
						aria-label="<?php echo esc_attr( $title ); ?>"
					>

							<div class="relative  col-start-1 row-start-1 row-end-3  overflow-hidden  mask-alpha mask-b-from-50% mask-b-to-95%">
								<img
									src="<?php echo esc_url( $img_url ); ?>"
									alt=""
									loading="lazy"
									decoding="async"
									class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
								/>
							</div>

						<div class="p-6 col-start-1 row-start-2 row-end-2 flex flex-col isolate justify-end bg-gradient-to-t from-primary-900/70 to-black/0 text-white ">
							<?php if ( $primary_expertise ) : ?>

								<div class="flex gap-2 items-center mb-2 expertise-label decoration-white">
					<svg class="aspect-square h-5 fill-current" aria-hidden="true"><use xlink:href="#exp-<?php echo sanitize_title( $primary_expertise ); ?>"></use></svg>
					<span class="text-sm">	<?php echo esc_html( $primary_expertise ); ?></span>
				</div>

							<?php endif; ?>

							<h3 class="text-balance text-xl font-medium leading-snug 	">
								<?php echo esc_html( $title ); ?>
							</h3>
						</div>
					</a>
				</li>

			<?php endwhile; ?>




		</ul>
	</div>
</div>

<?php
wp_reset_postdata();