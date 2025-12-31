<?php get_header(); ?>





	<?php
  // Optional top banner (block template part)
	block_template_part( 'banner' );
      get_template_part( 'parts/menu', null, null );
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
</div>
</main>



<?php get_footer(); ?>