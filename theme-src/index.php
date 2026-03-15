<?php
get_header();
?>


<?php if ( ! has_block( 'fnesl/home-hero' ) ) {

								get_template_part(
						'parts/menu',
							null,
							[
								'variant'      => 'transparent',
							]
						);
		}
      ?>


























	<main class="wp-site-blocks">



	<?php


	if ( have_posts() ) {
		while ( have_posts() ) {

			the_post();
			echo '<!-- TEMPLATE: about to output the_content() -->';

			the_content();
			echo '<!-- TEMPLATE: finished the_content() -->';
		}
	}
	?>

</main>


















<div class="container bg-primary-900 text-white rounded-sm mt-12 mb-12 grid grid-cols-3 gap-6 p-6 divide-x divide-primary-600 ">

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin  ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin  ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>


</div>





<?php

$newsposts = get_posts([
  'post_type'   => 'post',
  'numberposts' => 2,
	'orderby'     => 'menu_order',
]);

if ( $newsposts ) {

	echo '<div class="container flex flex-col items-center border-t border-primary-100 pt-12 pb-12 mt-12 mb-12 ">

	<h3 class="text-4xl text-primary-500 mb-6 ">Latest News & Events</h3>
	<p class="text-sm mb-12" >Lorum Ipsum dolor sit amet, consectetur adipiscing elit.</p>';



	echo '<ul class="grid grid-cols-3 gap-6  ">';

	foreach ( $newsposts as $newspost ) {
		?>
			<li class="bg-white rounded-sm p-3 flex flex-col gap-6">

				<div class=" flex items-center justify-center fill-current aspect-video bg-primary-900 grid grid-cols-1 grid-rows-1 overflow-hidden rounded-sm  relative  w-full  ">

					<div class="text-white text-xs col-start-1 row-start-1 self-start flex items-center gap-2 z-10 m-3">
						<div class="rounded-full bg-current w-2 h-2"></div>
						News <div class="rounded-sm bg-primary-500 w-0.5 h-3 mx-2"></div> <?php echo esc_html( get_the_date( 'M d, Y', $newspost->ID ) ); ?>
					</div>



					<?php echo wp_get_attachment_image( get_post_thumbnail_id( $newspost->ID ), 'medium', false, [ 'class' => 'w-full h-full  object-cover col-start-1 row-start-1 opacity-50' ] ); ?>
				</div>

				<div class=" flex flex-col justify-center  items-start self-start">
					<h3 class="text-lg text-primary-600 mb-2 "><?php echo esc_html( get_the_title( $newspost->ID ) ); ?></h3>
					<div class="text-sm text-primary-900 ">
						<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $newspost->ID ) ) ); ?>
					</div>

					<a href="<?php echo esc_url( get_permalink( $newspost->ID ) ); ?>" class="mt-6 text-sm text-primary-500 flex gap-3 " rel="noopener noreferrer"><span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center ">
									<svg class="aspect-square h-3 fill-current" aria-hidden="true"><use xlink:href="#icons_arrow_east"></use></svg>
								</span> Read More </a>
				</div>


	</li>

		<?php } ?>
		</ul></div>
<?php } ?>










<?php get_footer(); ?>