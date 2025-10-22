<?php get_header(); ?>





	<?php
  // Optional top banner (block template part)
	block_template_part( 'banner' );
      get_template_part( 'parts/menu', null, null );
      ?>





	<main class="wp-site-blocks">




<div class="wp-block-group alignwide">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	}
	?>
</div>
</main>



<?php get_footer(); ?>