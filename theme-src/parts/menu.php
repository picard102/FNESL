
  <nav id="site-header" role="banner" class="container flex justify-center z-10 gap-12 items-center font-serif font-medium uppercase text-md text-shadow-2xs site-nav ">

	<?php
$menu_name = 'primary';
$locations = get_nav_menu_locations();
$left_items = array();
$right_items = array();
if (isset($locations[$menu_name])) {
    $menu = wp_get_nav_menu_object($locations[$menu_name]);
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    $total = count($menu_items);
    $half = ceil($total / 2);
    $left_items = array_slice($menu_items, 0, $half);
    $right_items = array_slice($menu_items, $half);
}
?>

<ul class="flex gap-12 nav-left flex-1 flex justify-end mb-2">
      <?php foreach ($left_items as $item): ?>
        <li><a href="<?= esc_url($item->url); ?>" class="line-clamp-1"><?= esc_html($item->title); ?></a></li>
      <?php endforeach; ?>
    </ul>



<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center  group
site-logo mx-auto flex-shrink-0 " aria-label="<?php bloginfo( 'name' ); ?>">
	<svg class=" aspect-[1.4/1]  h-24 fill-current  " aria-hidden="true">
	<use xlink:href="#logo-full"></use>
	</svg>

</a>


<ul class="flex gap-12 nav-right flex-1 flex justify-start mb-2">
      <?php foreach ($right_items as $item): ?>
        <li><a href="<?= esc_url($item->url); ?>" class="line-clamp-1"><?= esc_html($item->title); ?></a></li>
      <?php endforeach; ?>
    </ul>

</nav>