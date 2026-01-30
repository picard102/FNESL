<div class="container">
<div class="border-b-current border-b mb-12 pt-3 pb-3  flex items-center ">

<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center  group
	site-logo mx-auto flex-shrink-0 " aria-label="<?php bloginfo( 'name' ); ?>">
		<svg class=" aspect-[1.6] h-20 fill-current  " aria-hidden="true">
		<use xlink:href="#logo-compact-v2"></use>
		</svg>
		<div class="font-serif text-xl leading-none border-l pl-3 ">First Nations</br> Engineering <br>Services <span class="text-xs font-thin ">LTD.</span> </div>
	</a>

	<?php
	$menu_name = 'primary';
	$locations = get_nav_menu_locations();
	if ( isset( $locations[ $menu_name ] ) ) {
		$menu       = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items( $menu->term_id );
		// Pull off the last menu item
		$cta_item = array_pop( $menu_items );
	}
	?>

	<ul class="flex gap-6 flex-1 justify-center items-center text-base">
		<?php if ( ! empty( $menu_items ) ) : ?>
			<?php foreach ( $menu_items as $item ) : ?>
				<li>
					<a
						href="<?php echo esc_url( $item->url ); ?>"
						class="line-clamp-1 p-3"
					>
						<?php echo esc_html( $item->title ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

	<?php if ( ! empty( $cta_item ) ) : ?>
		<a
			href="<?php echo esc_url( $cta_item->url ); ?>"
			class="line-clamp-1 p-3 border rounded-full whitespace-nowrap"
		>
			<?php echo esc_html( $cta_item->title ); ?>
		</a>
	<?php endif; ?>


</div>
</div>