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
$background_color  = $attributes['backgroundColor'] ?? 'var(--wp--preset--color--primary-500)';
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
$expertise_name = '';
$expertise_slug = '';

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
		if ( $first ) {
			$expertise_name = (string) $first->name;
			$expertise_slug = sanitize_title( $first->name );
		}
	}
}

// Left content fallback if InnerBlocks empty
$has_inner = (bool) strlen( trim( (string) $content ) );

?>





<div class="mx-auto alignfull ">

<div class="max-w-[1800px]  mx-auto p-2 ">

<div class="relative bg-primary-500  text-white rounded-md h-[85dvh] max-h-[800px]  justify-between flex flex-col isolate mx-auto "
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
				<div class=" max-w-xs hidden md:block -mb-18 justify-self-end ">
					<div class="text-sm uppercase mb-4 font-semibold ">Featured Project</div>

					<?php if ( $project_id && $project_url ) : ?>
						<a
							href="<?php echo esc_url( $project_url ); ?>"
							class="group h-full overflow-hidden rounded-xs  transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 grid grid-cols-1 grid-rows-2  bg-white  text-white !no-underline ring-offset-primary-200 aspect-[7/9] p-1 border border-primary-300"
							aria-label="<?php echo esc_attr( $project_title ); ?>"
						>
							<div class="relative  col-start-1 row-start-1 row-end-3  overflow-hidden   border border-primary-300  rounded-xs">
								<?php if ( $project_img ) : ?>
									<img
										src="<?php echo esc_url( $project_img ); ?>"
										alt=""
										loading="lazy"
										decoding="async"
										class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
									>
								<?php else : ?>
									<div class="h-full w-full bg-primary-200/20"></div>
								<?php endif; ?>
							</div>

							<div class="p-3 col-start-1 row-start-3 row-end-3 flex flex-col isolate  text-primary-600 items-start ">
								<?php if ( $expertise_name ) : ?>
									<div class="flex items-center expertise-label decoration-white col-start-1 row-start-1 isolate z-10 mt-2 mb-2">
										<svg class="aspect-square h-4 fill-current mr-2" aria-hidden="true">
											<use xlink:href="#exp-<?php echo esc_attr( $expertise_slug ); ?>"></use>
										</svg>
										<span class="text-xs pl-2 border-l "><?php echo esc_html( $expertise_name ); ?></span>
									</div>
								<?php endif; ?>

								<h3 class="text-balance text-xl font-medium leading-tight">
									<?php echo esc_html( $project_title ); ?>
								</h3>

								<p class="mt-6 text-sm text-primary-500 flex gap-3">
									<span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
										<svg class="aspect-square h-3 fill-current" aria-hidden="true"><use xlink:href="#icons_arrow_east"></use></svg>
									</span>
									View Project
								</p>
							</div>
						</a>
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
