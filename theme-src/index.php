<?php get_header(); ?>

<main class="wp-site-blocks">

// Render banner if it's active (and header isn't loaded)



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





<div class="relative isolate flex justify-center items-center gap-x-6 overflow-hidden bg-primary-900 px-6 py-2.5 pb-4 border-b border-white/10 sm:px-3.5 text-gray-100 -mb-4">

  <div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
    <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
  </div>
  <div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
    <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
  </div>

  <div class="flex flex-wrap items-center gap-x-2 gap-y-2">

    <p class="text-sm/6 text-gray-100">
		Passionate About Communities? We’re Hiring
    </p>

		<svg viewBox="0 0 2 2" aria-hidden="true" class="hidden md:inline mx-2 size-0.5 fill-current"><circle r="1" cx="1" cy="1" /></svg>

    <a href="#" class="flex items-center gap-2 text-sm font-book text-white no-underline hover:underline ">
			Start your career here
			<svg class=" aspect-square h-3 fill-current  " aria-hidden="true">
				<use xlink:href="#icons_arrow_east"></use>
			</svg>
		</a>

	</div>

</div>



<div class="mx-auto p-2">

<img decoding="async" src="https://fnesl.local/wp-content/uploads/2025/09/Shoal-Lake-treatment-plant-2-web.jpg" alt="" class="opacity-20 blur-xs -z-1 absolute inset-0 object-cover pointer-events-none object-center h-screen w-full saturate-0  mask-alpha mask-b-from-20% mask-b-to-60%" aria-hidden="true">


<div class="max-w-[1536px] mx-auto rounded-b-lg relative isolate flex justify-between flex-col overflow-hidden bg-primary-500 px-6 py-2.5  border border-white/10 sm:px-3.5 text-gray-100  min-h-150 h-[70vh]  ">


  <nav id="site-header" role="banner" class="container flex justify-center z-10 gap-12 items-center font-serif font-medium uppercase text-md  ">

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
        <li><a href="<?= esc_url($item->url); ?>"><?= esc_html($item->title); ?></a></li>
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
        <li><a href="<?= esc_url($item->url); ?>"><?= esc_html($item->title); ?></a></li>
      <?php endforeach; ?>
    </ul>

</nav>





	<div class="project-hero__media blur-xs">

	<img decoding="async" src="https://fnesl.local/wp-content/uploads/2025/09/Shoal-Lake-treatment-plant-2-web.jpg" alt="" class="opacity-100 opacity-0" data-fallback="true" aria-hidden="true">

	<video autoplay="" muted="" loop="" playsinline="" poster="https://fnesl.local/wp-content/uploads/2025/09/Shoal-Lake-treatment-plant-2-web.jpg" class="opacity-100" data-video="true">
		<source src="https://fnesl.local/wp-content/uploads/2025/09/web-home-banner-video_1.mp4" type="video/mp4"></video>

	<script>
		(function(){
			var video=document.currentScript.previousElementSibling;
			var img=video.previousElementSibling;
			if(video && img){
				video.addEventListener('canplay',function(){
					video.classList.remove('opacity-0');
					video.classList.add('opacity-100');
					if(img){img.classList.add('opacity-0');}
				});
			}
		})();
	</script>

</div>









<div class="project-hero__inner is-align-bottom container mb-12" data-wp-layout="constrained">

	<div class="flex gap-2 items-center">
		<svg class=" aspect-square h-4 fill-current " aria-hidden="true">
			<use xlink:href="#exp-energy"></use>
		</svg>
			<span class="text-md">Energy</span>
	</div>



<h1 class="wp-block-heading has-text-align-left has-4-xl-font-size text-balance max-w-2xl" style="font-style:normal;font-weight:500;line-height:1">Shoal Lake 40 First Nation Water Treatment &amp; Supply System</h1>
</div>





<div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
    <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
  </div>

  <div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
    <div style="clip-path: polygon(74.8% 41.9%, 97.2% 73.2%, 100% 34.9%, 92.5% 0.4%, 87.5% 0%, 75% 28.6%, 58.5% 54.6%, 50.1% 56.8%, 46.9% 44%, 48.3% 17.4%, 24.7% 53.9%, 0% 27.9%, 11.9% 74.2%, 24.9% 54.1%, 68.6% 100%, 74.8% 41.9%)" class="aspect-577/310 w-144.25 bg-linear-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
  </div>

</div>

</div>







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




<?php get_footer(); ?>