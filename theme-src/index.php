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






























<?php get_footer(); ?>