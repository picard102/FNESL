<?php
/**
 * Render callback for FNESL Project Hero v2 block
 */

/**
 * Render callback for FNESL Home Hero block
 *
 * @var array  $attributes
 * @var string $content  InnerBlocks HTML
 */

$background_type     = $attributes['backgroundType'] ?? 'image';
$background_image    = $attributes['backgroundImage']['url'] ?? '';
$background_image_id = $attributes['backgroundImage']['id'] ?? null;

$background_image_thumb = $background_image_id
	? wp_get_attachment_image_url( $background_image_id, 'medium' )
	: $background_image;

$background_video  = $attributes['backgroundVideo']['url'] ?? '';
$blur_level        = $attributes['blurLevel'] ?? 'xs';
$show_overlay      = ! empty( $attributes['showOverlay'] );
$background_color  = $attributes['backgroundColor'] ?? 'var(--wp--preset--color--primary-700)';
$text_color        = $attributes['textColor'] ?? 'var(--wp--preset--color--white)';

$featured_mode     = $attributes['featuredProjectMode'] ?? 'none';
$featured_id       = (int) ( $attributes['featuredProjectId'] ?? 0 );

// blur class helper
switch ( $blur_level ) {
	case 'sm':
		$blur_class = 'fnesl-blur-sm';
		break;
	case 'none':
		$blur_class = 'fnesl-blur-none';
		break;
	case 'xs':
	default:
		$blur_class = 'fnesl-blur-xs';
		break;
}

/**
 * Resolve featured project post ID
 */
$project_id = 0;

if ( 'select' === $featured_mode && $featured_id > 0 ) {
	$project_id = $featured_id;
} elseif ( 'random' === $featured_mode ) {
	$q = new WP_Query( array(
		'post_type'           => 'project',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'orderby'             => 'rand',
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	) );

	if ( $q->have_posts() ) {
		$project_id = (int) $q->posts[0]->ID;
	}
	wp_reset_postdata();
}

// Featured card data
$project_title  = '';
$project_url    = '';
$project_img    = '';
$expertise_term = null;
$expertise_name = '';

if ( $project_id ) {
	$project_title = (string) get_the_title( $project_id );
	$project_url   = (string) get_permalink( $project_id );

	$thumb_id = get_post_thumbnail_id( $project_id );
	if ( $thumb_id ) {
		$project_img = (string) wp_get_attachment_image_url( $thumb_id, 'large' );
	}

	$terms = get_the_terms( $project_id, 'expertise' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$first = $terms[0] ?? null;
		if ( $first && ! is_wp_error( $first ) ) {
			$expertise_term = $first;
			$expertise_name = (string) $first->name;
		}
	}
}

// Left content fallback if InnerBlocks empty
$has_inner = (bool) strlen( trim( (string) $content ) );

?>

<div class="mx-auto alignfull ">

<div class="max-w-[1800px]  mx-auto p-2 ">

<div class="relative bg-primary-700  text-white rounded-md h-[85dvh] max-h-[800px]  justify-between flex flex-col isolate mx-auto "
		style="background-color: <?php echo esc_attr( $background_color ); ?>; color: <?php echo esc_attr( $text_color ); ?>;" >

		<?php get_template_part( 'parts/menu', null, null ); ?>

		<div class="container py-12 px-6 grid grid-cols-3 items-end ">

			<div class=" prose flex flex-col items-start col-span-2  gap-4">
				<?php if ( $has_inner ) : ?>
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<h1 class="text-5xl mb-6 text-pretty">Engineering with Purpose.<br> Empowering Communities.</h1>
					<p class="mb-6 max-w-prose text-balance">We are a 100% Indigenous-owned civil engineering and community planning firm delivering infrastructure solutions that strengthen First Nations and municipalities across Canada.</p>
					<a href="#services" class="bg-white text-primary-500 px-6 py-3 rounded-full font-semibold shadow-md hover:shadow-lg transition">Our Services</a>
				<?php endif; ?>
			</div>

			<?php if ( 'none' !== $featured_mode ) : ?>
				<div class="max-w-xs hidden md:block -mb-18 justify-self-end">
					<div class="text-sm uppercase mb-4 font-semibold">Featured Project</div>

					<?php if ( $project_id && $project_url ) :
						$icon_data    = $expertise_term ? fnesl_get_expertise_icon_for_term( $expertise_term ) : null;
						$project_json = wp_json_encode( array(
							'id'            => $project_id,
							'link'          => $project_url,
							'title'         => $project_title,
							'image'         => $project_img,
							'expertiseName' => $expertise_name,
							'expertiseIcon' => $icon_data ? array( 'url' => $icon_data['url'] ) : null,
						) );
					?>
						<div data-home-hero-card data-project="<?php echo esc_attr( $project_json ); ?>"></div>
					<?php else : ?>
						<div class="bg-white text-primary-600 p-4 rounded-xs border border-primary-300">
							<?php echo ( 'random' === $featured_mode )
								? esc_html__( 'No projects found to feature.', 'fnesl' )
								: esc_html__( 'No project selected.', 'fnesl' ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

		<div class="  absolute inset-0 -z-10 overflow-hidden rounded-md  " aria-hidden="true">

<?php if ( 'video' === $background_type && $background_video ) : ?>
	<video autoplay muted loop playsinline
		poster="<?php echo esc_url( $background_image_thumb ); ?>"
		class="opacity-30 absolute inset-0 object-cover h-full w-full <?php echo esc_attr( $blur_class ); ?> "
		data-video="true">
		<source src="<?php echo esc_url( $background_video ); ?>" type="video/mp4">
	</video>

	<script>
		(function(){
			var video=document.currentScript.previousElementSibling;
			var img=video.previousElementSibling;
			if(video && img){
				video.addEventListener('canplay',function(){
					video.classList.remove('opacity-0');
					video.classList.add('opacity-30');
					img.classList.add('opacity-0');
				});
			}
		})();
	</script>
<?php else : ?>
	<?php if ( $background_image_thumb ) : ?>
		<img decoding="async"
			src="<?php echo esc_url( $background_image_thumb ); ?>"
			alt=""
			class="opacity-30 absolute inset-0 object-cover h-full w-full <?php echo esc_attr( $blur_class ); ?> "
			aria-hidden="true">
	<?php endif; ?>
<?php endif; ?>

<div aria-hidden="true" class="absolute  bottom-0   mix-blend-overlay opacity-10 ">
	<svg class=" fill-current w-[900%] aspect-[164/33] " aria-hidden="true">
		<use xlink:href="#logo-top"></use>
	</svg>
</div>

	<?php if ( $show_overlay ) : ?>
	<div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
		<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[46rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
	</div>
	<div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
		<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[36rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
	</div>
	<?php endif; ?>

		</div>

	</div>

</div>

</div>
