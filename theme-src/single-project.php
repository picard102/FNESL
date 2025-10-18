<?php
/**
 * Template for displaying single Projects (two-column layout, full-width hero)
 */

get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();

    $project_id = get_the_ID();
    $content = get_the_content();
    $has_hero = has_block( 'fnesl/project-hero-v2', $content );



?>

<main class="wp-site-blocks ">
  <?php
  // Optional top banner (block template part)
	block_template_part( 'banner' );

  // --- FULL WIDTH HERO ---
  if ( $has_hero ) {
    // Render only the hero block, not the rest of the content
    $hero_blocks = parse_blocks( $content );
    foreach ( $hero_blocks as $block ) {
      if ( $block['blockName'] === 'fnesl/project-hero-v2' ) {
        echo render_block( $block );
      }
    }
  } else {
    ?>
    <section class="project-hero project-hero--default alignfull">
      <div class="alignwide">
        <h1 class="wp-block-post-title"><?php the_title(); ?></h1>
      </div>
    </section>
    <?php
  }
  ?>

  <!-- Two-column layout below hero -->
  <div class="project-layout alignwide grid grid-cols-1 lg:grid-cols-[4fr_1fr] gap-12 mt-12 px-6">

    <article class="project-content prose wp-block-group ">
      <?php
      // Render all blocks except the hero
      if ( $has_hero ) {
        foreach ( $hero_blocks as $block ) {
          if ( $block['blockName'] !== 'fnesl/project-hero-v2' ) {
            echo render_block( $block );
          }
        }
      } else {
        echo do_blocks( $content );
      }
      ?>
    </article>

    <aside class="project-sidebar">
      <?php
      get_template_part( 'parts/project/meta', null, [ 'project_id' => $project_id ] );
      ?>
    </aside>
  </div>


</main>

<?php
  endwhile;
endif;

get_footer();