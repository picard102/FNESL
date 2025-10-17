<?php
/**
 * Render callback for FNESL Project Hero v2 block
 */

$background_type  = $attributes['backgroundType'] ?? 'image';
$background_image = $attributes['backgroundImage']['url'] ?? '';
$background_image_id  = $attributes['backgroundImage']['id'] ?? null;


$background_image_thumb = $background_image_id
	? wp_get_attachment_image_url( $background_image_id, 'medium' )
	: $background_image;

$background_video = $attributes['backgroundVideo']['url'] ?? '';
$blur_level       = $attributes['blurLevel'] ?? 'none';
$show_overlay     = $attributes['showOverlay'] ?? true;
$vertical_align   = $attributes['verticalAlign'] ?? 'bottom';
$font_size = $attributes['fontSize'] ?? '2xl';
$font_size_class = preg_replace('/^(\d+)/', '$1-', $font_size); // adds dash after leading digits

$text_align       = $attributes['textAlign'] ?? 'left';
$selected_expertise = $attributes['selectedExpertise'] ?? null;
$background_color = $attributes['backgroundColor'] ?? 'var(--wp--preset--color--primary)';
$text_color       = $attributes['textColor'] ?? 'var(--wp--preset--color--white)';

$post_id = get_the_ID();
$title   = get_the_title( $post_id );

// get first assigned expertise if auto
if ( ! $selected_expertise ) {
	$expertise_terms = get_the_terms( $post_id, 'expertise' );
	if ( $expertise_terms && ! is_wp_error( $expertise_terms ) ) {
		$selected_expertise = $expertise_terms[0]->term_id ?? null;
	}
}

$expertise_name = '';
if ( $selected_expertise ) {
	$term = get_term( $selected_expertise );
	if ( $term && ! is_wp_error( $term ) ) {
		$expertise_name = esc_html( $term->name );
	}
}

// blur class helper
$blur_class = match ( $blur_level ) {
	'xs'   => 'fnesl-blur-xs',
	'sm'   => 'fnesl-blur-sm',
	default => 'fnesl-blur-none',
};
?>















<div class="relative mx-auto p-2 project-hero alignfull  ">


	<?php if ( $background_image ) :
		echo '<img decoding="async"
			src="' . esc_url( $background_image_thumb ) . '"
			alt=""
			class=" opacity-20 blur-xs -z-1 absolute inset-0 object-cover pointer-events-none object-center h-screen w-full saturate-0  mask-alpha mask-b-from-20% mask-b-to-60%"
			aria-hidden="true"
			decoding="async"
			>';
 endif; ?>



	<div class="max-w-[1536px] mx-auto rounded-b-lg relative isolate flex justify-between flex-col overflow-hidden  px-2 py-2.5  border border-white/10 sm:px-6  min-h-120 h-[70vh]  " style="background-color: <?php echo esc_attr( $background_color ); ?>; color: <?php echo esc_attr( $text_color ); ?>;">


	<?php if ( $show_overlay ) :

echo '<div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
	<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[46rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
</div>
<div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
	<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[36rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
</div>';



	endif; ?>

<?php if ( $background_image || $background_video ) : ?>
	<div class="project-hero__media absolute inset-0 -z-10 pointer-events-none <?php echo esc_attr( $blur_class ); ?>">
		<?php if ( $background_image ) : ?>
			<img decoding="async"
				src="<?php echo esc_url( $background_image ); ?>"
				alt=""
				class=""
				aria-hidden="true"
				data-fallback="true">
		<?php endif; ?>

		<?php if ( $background_type === 'video' && $background_video ) : ?>
			<video
				autoplay muted loop playsinline
				poster="<?php echo esc_url( $background_image ); ?>"
				class="opacity-0"
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
							video.classList.add('opacity-100');
							img.classList.add('opacity-0');
						});
					}
				})();
			</script>
		<?php endif; ?>
	</div>
<?php endif; ?>




<div class="project-hero__inner z-10 text-<?php echo esc_attr( $text_align ); ?>">

</div>



		<div class="project-hero__inner mb-3 z-10  sm:px-6  text-<?php echo esc_attr( $text_align ); ?>">

			<?php if ( $expertise_name ) : ?>
				<div class="flex gap-2 items-center mb-2 expertise-label ">
					<svg class="aspect-square h-5 fill-current" aria-hidden="true"><use xlink:href="#exp-<?php echo sanitize_title( $expertise_name ); ?>"></use></svg>
					<span class="text-md"><?php echo esc_html( $expertise_name ); ?></span>
				</div>
			<?php endif; ?>

<h1 class="wp-block-heading has-text-align-<?php echo esc_attr( $text_align ); ?> has-<?php echo esc_attr( $font_size_class ); ?>-font-size text-balance " style="font-weight:500;line-height:1">
				<?php echo esc_html( $title ); ?>
			</h1>


		</div>




	</div>
</div>
