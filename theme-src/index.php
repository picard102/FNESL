<?php get_header(); ?>












<div class=" max-w-[1800px] mx-auto p-2 ">

<div class="  relative bg-primary-500  text-white rounded-md min-h-[85dvh]  justify-between flex flex-col isolate mx-auto ">





<div class=" border-b-current border-b mb-12 pt-3 pb-3 container flex items-center ">

	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center  group
	site-logo mx-auto flex-shrink-0 " aria-label="<?php bloginfo( 'name' ); ?>">
		<svg class=" aspect-[1.4/1] h-20 fill-current  " aria-hidden="true">
		<use xlink:href="#logo-compact"></use>
		</svg>
		<div class="font-serif text-xl leading-none border-l pl-3 ">First Nations</br> Engineering <br>Services <span class="text-xs font-thin ">LTD.</span> </div>
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




		<a href="<?= esc_url($item->url); ?>" class="line-clamp-1 p-3 border rounded-full"><?= esc_html($item->title); ?></a>



</div>



				<div class="container py-12 px-6 grid grid-cols-3 items-end ">

				<div class=" prose flex flex-col items-start col-span-2 ">

					<h1 class="text-5xl mb-6 text-pretty " >Engineering with Purpose.<br> Empowering Communities.</h1>

					<p class="mb-6 max-w-prose text-balance">We are a 100% Indigenous-owned civil engineering and community planning firm delivering infrastructure solutions that strengthen First Nations and municipalities across Canada. </p>


					<a href="#services" class="bg-white text-primary-500 px-6 py-3 rounded-full font-semibold shadow-md hover:shadow-lg transition ">Our Services</a>

				</div>

		<div class=" max-w-xs hidden md:block -mb-18 justify-self-end ">

					<div class="text-sm uppercase mb-4 font-semibold ">Featured Project</div>







				<a href="" class="group h-full overflow-hidden rounded-xs  transition hover:shadow-sm focus:outline-none ring-offset-3 hover:ring-2 focus:ring-2 ring-primary-400 grid grid-cols-1 grid-rows-2  bg-white  text-white !no-underline ring-offset-primary-200 aspect-[7/9] p-1 border border-primary-300
 " aria-label="Shoal Lake 40 First Nation Water Treatment &amp; Supply System">




							<div class="relative  col-start-1 row-start-1 row-end-3  overflow-hidden   border border-primary-300  rounded-xs">

								<img src="https://fnesl.local/wp-content/uploads/2025/09/water-treatment-GettyImages-505176828-1920x1080-1-1024x576.jpg" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]">
							</div>

						<div class="p-3 col-start-1 row-start-3 row-end-3 flex flex-col isolate  text-primary-600 items-start ">



<div class="flex items-center expertise-label decoration-white col-start-1 row-start-1 isolate z-10 mt-2 mb-2">
	<svg class="aspect-square h-4 fill-current mr-2" aria-hidden="true"><use xlink:href="#exp-energy"></use></svg>
	<span class="text-xs pl-2 border-l ">	Energy</span>
</div>




							<h3 class="text-balance text-xl font-medium leading-tight 	">
								Shoal Lake 40 First Nation Water Treatment &amp; Supply System</h3>

							<p class="mt-6 text-sm text-primary-500 flex gap-3	">
								<span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center ">
									<svg class="aspect-square h-3 fill-current" aria-hidden="true"><use xlink:href="#icons_arrow_east"></use></svg>
								</span>
								View Project
							 </p>

						</div>
					</a>







		</div>





			</div>



			<div class="  absolute inset-0 -z-10 overflow-hidden rounded-md  " aria-hidden="true">

					<img decoding="async" src="http://fnesl.ca/temp/wordpress/wp-content/uploads/2025/10/sl40wtp-hero.mp4" alt="" class="opacity-0 absolute inset-0 object-cover h-full w-full blur-xs" aria-hidden="true" data-fallback="true">

					<video autoplay="" muted="" loop="" playsinline="" poster="http://fnesl.ca/temp/wordpress/wp-content/uploads/2025/10/sl40wtp-hero.mp4" class="opacity-30 absolute inset-0 object-cover h-full w-full blur-xs" data-video="true">
				<source src="http://fnesl.ca/temp/wordpress/wp-content/uploads/2025/10/sl40wtp-hero.mp4" type="video/mp4">
			</video>

			<script>
				(function(){
					var video=document.currentScript.previousElementSibling;
					var img=video.previousElementSibling;
					if(video && img){
						video.addEventListener('canplay',function(){
							video.classList.remove('opacity-0');
							video.classList.add('opacity-30');
							img.classList.add('opacity-0');
						});
					}
				})();
			</script>


			<div aria-hidden="true" class="absolute top-1/2 left-[max(-7rem,calc(50%-52rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
	<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[46rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
</div>


<div aria-hidden="true" class="absolute  bottom-0   mix-blend-overlay opacity-10 ">
<svg class=" fill-current w-[900%] aspect-[164/33] " aria-hidden="true">
<use xlink:href="#logo-top"></use>
</svg>
</div>



<div aria-hidden="true" class="absolute top-1/2 left-[max(45rem,calc(50%+8rem))] -z-10 -translate-y-1/2 transform-gpu blur-2xl">
	<div style="clip-path: polygon(74.8% 41.9%,97.2% 73.2%,100% 34.9%,92.5% 0.4%,87.5% 0%,75% 28.6%,58.5% 54.6%,50.1% 56.8%,46.9% 44%,48.3% 17.4%,24.7% 53.9%,0% 27.9%,11.9% 74.2%,24.9% 54.1%,68.6% 100%,74.8% 41.9%)" class="aspect-[577/310] w-[36rem] bg-gradient-to-r from-[#ff80b5] to-[#9089fc] opacity-40"></div>
</div>




			</div>

			</div>




</div>





























	<?php
  // Optional top banner (block template part)
	// block_template_part( 'banner' );
     // get_template_part( 'parts/menu', null, null );
      ?>





	<main class="wp-site-blocks">



	<?php


	if ( have_posts() ) {
		while ( have_posts() ) {

			the_post();
			echo '<!-- TEMPLATE: about to output the_content() -->';

			the_content();
			echo '<!-- TEMPLATE: finished the_content() -->';
		}
	}
	?>

</main>














<?php

$affiliations = get_posts([
  'post_type'   => 'affiliation',
  'numberposts' => 2,
	'orderby'     => 'menu_order',
]);

if ( $affiliations ) {

	echo '<div class="container flex flex-col items-center border-t border-primary-100 pt-12 pb-12 mt-12 mb-12 ">

	<h3 class="text-4xl text-primary-500 mb-6 ">Our Partners</h3>
	<p class="text-sm mb-12" >We are proud to be affiliated with the following organizations:</p>';



	echo '<ul class="grid grid-cols-2 gap-6  ">';

	foreach ( $affiliations as $affiliation ) {

	// New meta keys
	$logo_id = (int) get_post_meta( $affiliation->ID, 'affiliation_svg_logo_id', true ); // full colour
	$url     = (string) get_post_meta( $affiliation->ID, 'affiliation_url', true );

	if ( ! $logo_id  ) {
		continue;
	}
	?>


	<li class="bg-white rounded-sm p-6 grid grid-cols-[1fr_2fr] gap-6">


	<div class="flex items-center justify-center">
		<?php
		$logo_html = '';

		if ( $logo_id ) {
			$logo_html = wp_get_attachment_image(
				$logo_id,
				'full',
				false,
				[
					'class' => 'w-full max-h-[100px] h-auto object-contain',
					'alt'   => esc_attr( get_the_title( $affiliation->ID ) ) . ' logo',
				]
			);
		}
		if ( $logo_html ) {
			// If URL exists, wrap logo in link
			if ( $url ) {
				echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="block w-full">';
				echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</a>';
			} else {
				echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>
	</div>

	<div class="flex flex-col justify-center items-start self-start">
		<h3 class="text-xl mb-2"><?php echo esc_html( get_the_title( $affiliation->ID ) ); ?></h3>

		<div class="text-sm text-primary-900">
			<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $affiliation->ID ) ) ); ?>
		</div>

		<?php if ( $url ) : ?>
			<a
				href="<?php echo esc_url( $url ); ?>"
				class="mt-6 text-sm text-primary-500 flex gap-3"
				target="_blank"
				rel="noopener noreferrer"
			>
				<span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center">
					<svg class="aspect-square h-3 fill-current" aria-hidden="true">
						<use xlink:href="#icons_arrow_east"></use>
					</svg>
				</span>
				Visit Website
			</a>
		<?php endif; ?>
	</div>

</li>


		<?php } ?>
		</ul></div>
<?php } ?>





<div class="container bg-primary-900 text-white rounded-sm mt-12 mb-12 grid grid-cols-3 gap-6 p-6 divide-x divide-primary-600 ">

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin  ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>

	<div class="flex flex-col px-3">
		<h3 class="text-6xl text-white mb-3 font-thin  ">100%</h3>
		<p class="text-sm  max-w-prose text-primary-300">Indigenous Owned. <br>Firm led and operated by Indigenous professionals.</p>
	</div>


</div>





<?php

$newsposts = get_posts([
  'post_type'   => 'post',
  'numberposts' => 2,
	'orderby'     => 'menu_order',
]);

if ( $newsposts ) {

	echo '<div class="container flex flex-col items-center border-t border-primary-100 pt-12 pb-12 mt-12 mb-12 ">

	<h3 class="text-4xl text-primary-500 mb-6 ">Latest News & Events</h3>
	<p class="text-sm mb-12" >Lorum Ipsum dolor sit amet, consectetur adipiscing elit.</p>';



	echo '<ul class="grid grid-cols-3 gap-6  ">';

	foreach ( $newsposts as $newspost ) {
		?>
			<li class="bg-white rounded-sm p-3 flex flex-col gap-6">

				<div class=" flex items-center justify-center fill-current aspect-video bg-primary-900 grid grid-cols-1 grid-rows-1 overflow-hidden rounded-sm  relative   ">

					<div class="text-white text-xs col-start-1 row-start-1 self-start flex items-center gap-2 z-10 m-3">
						<div class="rounded-full bg-current w-2 h-2"></div>
						News <div class="rounded-sm bg-primary-500 w-0.5 h-3 mx-2"></div> <?php echo esc_html( get_the_date( 'M d, Y', $newspost->ID ) ); ?>
					</div>

					<?php echo wp_get_attachment_image( get_post_thumbnail_id( $newspost->ID ), 'medium', false, [ 'class' => 'w-full h-full cover col-start-1 row-start-1 opacity-50' ] ); ?>
				</div>

				<div class=" flex flex-col justify-center  items-start self-start">
					<h3 class="text-lg text-primary-600 mb-2 "><?php echo esc_html( get_the_title( $newspost->ID ) ); ?></h3>
					<div class="text-sm text-primary-900 ">
						<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', $newspost->ID ) ) ); ?>
					</div>

					<a href="<?php echo esc_url( get_post_meta( $newspost->ID, 'affiliation_website', true ) ); ?>" class="mt-6 text-sm text-primary-500 flex gap-3 " target="_blank" rel="noopener noreferrer"><span class="flex-1 bg-primary-500 text-white px-1 py-1 rounded-full inline-flex items-center ">
									<svg class="aspect-square h-3 fill-current" aria-hidden="true"><use xlink:href="#icons_arrow_east"></use></svg>
								</span> Read More <?php echo esc_html( get_post_meta( $newspost->ID, 'affiliation_website', true ) ); ?></a>
				</div>


	</li>

		<?php } ?>
		</ul></div>
<?php } ?>










<?php get_footer(); ?>