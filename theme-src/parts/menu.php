
<div class=" container ">
<div class=" border-b-current border-b mb-12 pt-3 pb-3  flex items-center ">

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center  group
	site-logo mx-auto flex-shrink-0 " aria-label="<?php bloginfo( 'name' ); ?>">
		<svg class=" aspect-[1.4/1] h-20 fill-current  " aria-hidden="true">
		<use xlink:href="#logo-compact"></use>
		</svg>
		<div class="font-serif border-l pl-3 text-md leading-none ">First <br>Nations</br> Engineering<br> Services </div>
	</a>



<?php
$menu_name = 'primary';
$locations = get_nav_menu_locations();
$menu = wp_get_nav_menu_object($locations[$menu_name]);
$menu_items = wp_get_nav_menu_items($menu->term_id);
?>
<ul class="flex gap-6 flex-1 justify-center items-center text-base ">
      <?php foreach ($menu_items as $item): ?>
        <li><a href="<?= esc_url($item->url); ?>" class="line-clamp-1 p-3"><?= esc_html($item->title); ?></a></li>
      <?php endforeach; ?>
    </ul>




		<a href="<?= esc_url($item->url); ?>" class="line-clamp-1 p-3 border rounded-md"><?= esc_html($item->title); ?></a>



</div>

</div>

