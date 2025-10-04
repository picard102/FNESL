<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>


	<a href=""  class="bg-primary-500 text-primary-50 block ">

		<div class="flex justify-between gap-2 p-4  text-sm   text-primary-50 font-medium  ">
		Passionate About Communities? We’re Hiring

		<span class="flex gap-1 items-center ">
		Start your career here 	<svg class=" aspect-square  h-3 fill-current  " aria-hidden="true">
		<use xlink:href="#icons_arrow_east"></use>
		</svg>
		</span>
	</div>

</a>

  <header id="site-header" role="banner" class="flex flex-col p-4  text-primary-500">

	<div class="flex gap-4 opacity-70 text-sm self-end">
		<span>Phone: (647) 955-9006</span>
		<span>Fax: (647) 955-9006</span>
	</div>

	<div class="flex gap-4 items-center justify-between ">

<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center !no-underline group
 " aria-label="<?php bloginfo( 'name' ); ?>">
	<svg class=" aspect-[1.4/1]  h-24 fill-current hidden " aria-hidden="true">
	<use xlink:href="#logo-full"></use>
	</svg>

	<svg class=" aspect-[1.69/1] h-20 fill-current " aria-hidden="true">
	<use xlink:href="#logo-compact"></use>
	</svg>

	<h1 class="group-hover:border-b  text-lg flex-col leading-5 uppercase font-bold mt-2.5 hidden md:flex"> <span>First Nations</span> <span>Engineering</span> Services LTD.</h1>
</a>


    <nav id="site-navigation" role="navigation" class="text-lg font-serif font-medium text-primary-400">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
      ]);
      ?>
    </nav>


		</div>
  </header>

  <main id="site-content" role="main">







