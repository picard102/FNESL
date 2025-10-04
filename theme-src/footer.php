</main><!-- #site-content -->

<footer id="site-footer" role="contentinfo" class="flex flex-col bg-primary-500 gap-4 text-white  ">



<div class="grid grid-cols-1 grid-rows-1 items-start justify-items-center  text-primary-900 relative border-t-24 border-ghost  ">

<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class=" flex gap-4 items-center no-underline -mt-6 z-20  col-start-1 col-end-1 row-start-1 row-end-1
 " aria-label="<?php bloginfo( 'name' ); ?>">
	<svg class=" aspect-[1.4/1]  h-32 fill-current   " aria-hidden="true">
	<use xlink:href="#logo-full"></use>
	</svg>
</a>

<svg class=" aspect-[5/1]  w-full container fill-ghost  col-start-1 col-end-1 row-start-1 row-end-1 -mt-1" aria-hidden="true">
	<use xlink:href="#footer"></use>
	</svg>
</div>




<div class="flex gap-4 items-center justify-between">

<nav id="site-navigation" role="navigation" class="text-lg font-serif font-medium text-primary-400">
	<?php
	wp_nav_menu([
		'theme_location' => 'primary',
		'menu_id'        => 'primary-menu',
	]);
	?>
</nav>


<div class="flex gap-4 text-sm self-end">
	<address class="not-italic" itemscope itemtype="https://schema.org/PostalAddress">
		<span class="text-md font-serif">Mailing Address</span><br>
		<span itemprop="postOfficeBoxNumber">PO Box 280</span><br>
		<span itemprop="addressLocality">Ohsweken</span>,
		<span itemprop="addressRegion">Ontario</span><br>
		<span itemprop="postalCode">N0A 1M0</span>
	</address>

	<address class="not-italic" itemscope itemtype="https://schema.org/PostalAddress">
		<span class="text-md font-serif">Deliveries</span><br>
		<span itemprop="streetAddress">1786 Chiefswood Rd</span><br>
		<span itemprop="addressLocality">Ohsweken</span>,
		<span itemprop="addressRegion">Ontario</span><br>
		<span itemprop="postalCode">N0A 1M0</span>
	</address>
</div>

</div>



<div class="flex gap-4 border-y my-12 py-12 justify-center">
	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/fnes-logo.png" alt=" Logo" class="h-12">
	<img src="<?php echo get_template_directory_uri(); ?>/assets/images/indigenous-owned.png" alt="Logo " class="h-12">
</div>



<div class="flex gap-4 pb-12 justify-between text-xs">

	<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>

	<div class="flex gap-4 ">
		<a href="" class="flex bg-primary-500 p-1 rounded border">
		<svg class=" aspect-square  h-4 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_linkedin"></use>
		</svg>
		</a>

		<a href="" class="flex bg-primary-500 p-1 rounded border">
		<svg class=" aspect-square  h-4 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_instagram"></use>
		</svg>
		</a>

		<a href="" class="flex bg-primary-500 p-1 rounded border">
		<svg class=" aspect-square  h-4 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_youtube"></use>
		</svg>
		</a>


	</div>


</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>