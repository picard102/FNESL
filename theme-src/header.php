<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <header id="site-header" role="banner">
    <div class="site-branding">
      <?php if ( has_custom_logo() ) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <h1><?php bloginfo( 'name' ); ?></h1>
        </a>
        <p><?php bloginfo( 'description' ); ?></p>
      <?php endif; ?>
    </div>

    <nav id="site-navigation" role="navigation">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
      ]);
      ?>
    </nav>
  </header>

  <main id="site-content" role="main">