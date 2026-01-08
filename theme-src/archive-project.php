<?php
get_header();
?>


	<?php
  // Optional top banner (block template part)
	block_template_part( 'banner' );
      get_template_part( 'parts/menu', null, null );
      ?>

<main class="wp-site-blocks ">
  <?php
  $intro_page = get_page_by_path('projects');
  if ( $intro_page ) {
    echo apply_filters('the_content', $intro_page->post_content);
  }
  ?>
</main>
<?php
get_footer();