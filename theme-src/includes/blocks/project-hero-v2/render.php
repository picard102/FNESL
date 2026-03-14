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

$expertise_term = null;
if ( $selected_expertise ) {
	$expertise_term = get_term( (int) $selected_expertise, 'expertise' );
}

// Resolve icon (term -> parent fallback). If none, hide label entirely.
$expertise_icon = $expertise_term ? fnesl_get_expertise_icon_for_term( $expertise_term, 'fnesl_term_icon_svg_id' ) : null;

// Label text should match the term we’re displaying (original selected term name),
// but if you prefer showing the parent name when parent icon is used, swap to $expertise_icon['term']->name.
$expertise_name = '';
if ( $expertise_term && ! is_wp_error( $expertise_term ) ) {
	$expertise_name = $expertise_term->name;
}



// blur class helper
switch ( $blur_level ) {
	case 'xs':
		$blur_class = 'fnesl-blur-xs';
		break;

	case 'sm':
		$blur_class = 'fnesl-blur-sm';
		break;

	default:
		$blur_class = 'fnesl-blur-none';
		break;
}
?>















<div class="relative mx-auto project-hero alignfull  ">


	<?php if ( $background_image ) :
		echo '<img decoding="async"
			src="' . esc_url( $background_image_thumb ) . '"
			alt=""
			class=" opacity-20 blur-xs -z-1 absolute inset-0 object-cover pointer-events-none object-center h-screen w-full saturate-0  mask-alpha mask-b-from-20% mask-b-to-60%"
			aria-hidden="true"
			decoding="async"
			>';
 endif; ?>


<div class="max-w-[1800px] mx-auto p-2">

	<div class="  bg-primary-500  text-white rounded-md h-[85dvh] max-h-[600px]  justify-between flex flex-col isolate mx-auto

	 relative overflow-hidden    " style="background-color: <?php echo esc_attr( $background_color ); ?>; color: <?php echo esc_attr( $text_color ); ?>;">


	 <?php get_template_part( 'parts/menu', null, null ); ?>



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





		<div class="project-hero__inner z-10

		container  px-6  items-end

		text-<?php echo esc_attr( $text_align ); ?>">

			<?php if ( $expertise_name ) : ?>



				<?php
$expertise_svg = '';
if ( $expertise_term && ! is_wp_error( $expertise_term ) ) {
	$expertise_svg = fnesl_inline_expertise_term_svg( $expertise_term, 'h-6 w-6 fill-current mr-3' );
}
?>

<?php if ( $expertise_svg && $expertise_name ) : ?>
	<div class="flex items-center expertise-label decoration-white col-start-1 row-start-1 isolate z-10 mt-2 mb-2">
		<?php echo $expertise_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="text-md pl-4 border-l"><?php echo esc_html( $expertise_name ); ?></span>
	</div>
<?php endif; ?>


			<?php endif; ?>

<h1 class="wp-block-heading has-text-align-<?php echo esc_attr( $text_align ); ?> has-<?php echo esc_attr( $font_size_class ); ?>-font-size text-balance " style="font-weight:500;line-height:1">
				<?php echo esc_html( $title ); ?>
			</h1>


		</div>




	</div>
</div>
</div>
