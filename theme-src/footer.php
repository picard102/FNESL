</main><!-- #site-content -->

<div class=" max-w-[1800px] mx-auto p-2 ">
<footer class="bg-primary-700 mt-12 relative overflow-hidden rounded-md isolate" id="site-footer" role="contentinfo">


<div class="container py-6  text-primary-300 flex gap-12 items-start grid grid-cols-12">


<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-4 items-center
	site-logo mx-auto flex-shrink-0 text-white  col-span-12" aria-label="<?php bloginfo( 'name' ); ?>">
		<svg class=" aspect-[1.4/1] h-32 fill-current  pr-6" aria-hidden="true">
		<use xlink:href="#logo-full"></use>
		</svg>
		<div class="font-serif text-xl leading-none border-l pl-3 hidden ">First Nations</br> Engineering <br>Services <span class="text-xs font-thin ">LTD.</span> </div>
	</a>




<?php
$affiliations = get_posts([
  'post_type'   => 'affiliation',
  'numberposts' => -1,
]);

// Randomize order
shuffle($affiliations);

if ( $affiliations ) : ?>

<div class="flex gap-6 flex-col col-span-12">

<div class="text-sm border-b border-primary-600 pb-3">Affiliations & Certifications</div>




<!--
  <ul class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
    <?php foreach ( $affiliations as $affiliation ) :

      $thumb_id = get_post_thumbnail_id( $affiliation->ID );

      $ratio = $thumb_id ? tpe_svg_aspect_ratio_from_attachment( $thumb_id ) : null;
      $w_pct = $ratio ? tpe_logo_width_percent_from_ratio( $ratio ) : 55.359769747362;

      // keep layout sane if something unexpected comes back
      $w_pct = max(10, min(100, (float) $w_pct));
      ?>

<li class="">
	<a href="" class="flex items-center justify-center col h-16 p-4 hover:text-white
	transition-colors duration-300 ease-in-out  ">
		<div class="flex items-center justify-center" style="width: <?php echo esc_attr( $w_pct ); ?>%;">
			<?php
				echo tpe_inline_featured_svg(
					$affiliation->ID,
					'w-full  fill-current'
				); // phpcs:ignore
			?>
		</div>
			</a>
</li>

    <?php endforeach; ?>
  </ul> -->




	<ul class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
	<?php foreach ( $affiliations as $affiliation ) :

		// Prefer single-colour meta, fallback to full-colour meta
		$logo_id = (int) get_post_meta( $affiliation->ID, 'affiliation_svg_logo_1c_id', true );
		if ( ! $logo_id ) {
			$logo_id = (int) get_post_meta( $affiliation->ID, 'affiliation_svg_logo_id', true );
		}

		// Skip entirely if we have no logo
		if ( ! $logo_id ) {
			continue;
		}

		$ratio = tpe_svg_aspect_ratio_from_attachment( $logo_id );
		$w_pct = $ratio ? tpe_logo_width_percent_from_ratio( $ratio ) : 55.359769747362;

		// keep layout sane if something unexpected comes back
		$w_pct = max( 10, min( 100, (float) $w_pct ) );

		$url = (string) get_post_meta( $affiliation->ID, 'affiliation_url', true );
		?>
		<li>
			<a
				href="<?php echo esc_url( $url ?: '#' ); ?>"
				<?php if ( $url ) : ?>
					target="_blank" rel="noopener noreferrer"
				<?php else : ?>
					aria-disabled="true" tabindex="-1"
				<?php endif; ?>
				class="flex items-center justify-center col h-16 p-4 hover:text-white transition-colors duration-300 ease-in-out"
			>
				<div class="flex items-center justify-center" style="width: <?php echo esc_attr( $w_pct ); ?>%;">
					<?php
					echo tpe_inline_featured_svg(
						$affiliation->ID,
						'w-full fill-current',
						$logo_id
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</a>
		</li>
	<?php endforeach; ?>
</ul>


</div>



<?php endif; ?>





<!--
<?php if ( $affiliations ) : ?>
  <ul
    class="flex flex-wrap gap-3 items-center justify-center mt-6
           [--per-row:3] md:[--per-row:4] lg:[--per-row:6]"
  >
    <?php foreach ( $affiliations as $affiliation ) :
      $thumb_id = get_post_thumbnail_id( $affiliation->ID );
      $ratio    = $thumb_id ? tpe_svg_aspect_ratio_from_attachment( $thumb_id ) : null;
      $w_pct    = $ratio ? tpe_logo_width_percent_from_ratio( $ratio ) : 55.359769747362;
      $w_pct    = max(10, min(100, (float) $w_pct));
    ?>
      <li class="overflow-hidden"
          style="flex: 0 0 calc(<?php echo esc_attr( $w_pct ); ?>% / var(--per-row));">
        <a href="" class="flex items-center justify-center h-16 p-4">
          <div class="flex items-center justify-center" style="width: <?php echo esc_attr( $w_pct ); ?>%;">
            <?php
              echo tpe_inline_featured_svg(
                $affiliation->ID,
                'w-full fill-current'
              );
            ?>
          </div>
				</a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
 -->






































<div class=" col-span-12 grid grid-cols-subgrid gap-12 items-start ">

<?php
$menu_name = 'footer';
$locations = get_nav_menu_locations();

if (is_array($locations) && !empty($locations[$menu_name])) {

$menu = wp_get_nav_menu_object($locations[$menu_name]);
$menu_items = wp_get_nav_menu_items($menu->term_id);
?>
<ul class="
	grid grid-cols-subgrid gap-12 gap-y-0 col-span-8

text-md text-white ">
      <?php
			if ( $menu_items ) {

			foreach ($menu_items as $item): ?>
        <li class="border-primary-600 border-b col-span-4
				[&:nth-child(-n+2)]:border-t
				">

				<a href="<?= esc_url($item->url); ?>" class="line-clamp-1   my-3 "><?= esc_html($item->title); ?></a></li>
      <?php endforeach;
			} ?>
    </ul>
<?php }?>




<div class="flex flex-col gap-4 text-xs  col-span-4 border-t py-6 border-primary-600

grid grid-cols-[auto_1fr] gap-x-6

">

		<span>Phone</span>
		<span itemprop="telephone" class="text-white">+1 (519) 445-2220</span>

		<span>Fax</span>
		<span itemprop="faxNumber" class="text-white">+1 (519) 445-2224</span>

		<span>Mailing Address</span>
	<address class="not-italic text-white" itemscope="" itemtype="https://schema.org/PostalAddress">
		<span itemprop="postOfficeBoxNumber">PO Box 280</span><br>
		<span itemprop="addressLocality">Ohsweken</span>,
		<span itemprop="addressRegion">Ontario</span><br>
		<span itemprop="postalCode">N0A 1M0</span>
	</address>

		<span>Delivery Address</span>
	<address class="not-italic text-white" itemscope="" itemtype="https://schema.org/PostalAddress">

		<span itemprop="streetAddress">1786 Chiefswood Rd</span><br>
		<span itemprop="addressLocality">Ohsweken</span>,
		<span itemprop="addressRegion">Ontario</span><br>
		<span itemprop="postalCode">N0A 1M0</span>
	</address>
</div>

</div>







	<div class="flex gap-4 ">
		<a href="" class="flex bg-primary-600 hover:bg-primary-800
	transition-colors duration-300 ease-in-out p-2 rounded ">
		<svg class=" aspect-square  h-6 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_linkedin"></use>
		</svg>
		</a>

		<a href="" class="flex bg-primary-600  p-2 rounded ">
		<svg class=" aspect-square  h-6 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_instagram"></use>
		</svg>
		</a>

		<a href="" class="flex bg-primary-600  p-2 rounded ">
		<svg class=" aspect-square  h-6 fill-current  inline " aria-hidden="true">
		<use xlink:href="#icons_youtube"></use>
		</svg>
		</a>


	</div>




</div>


<div aria-hidden="true" class="absolute   -top-12 -z-10   mix-blend-overlay opacity-10 pointer-events-none ">
<svg class=" fill-current w-[900%] aspect-[164/33] " aria-hidden="true">
<use xlink:href="#logo-bottom"></use>
</svg>
</div>




<div class="bg-primary-900 text-primary-200 py-12 mt-12 z-10 relative ">
	<div class="container text-sm ">
		&copy; <?php echo date('Y'); ?> First Nations Engineering Services Ltd. All rights reserved.
	</div>
</div>
</footer>
</div>









<?php wp_footer(); ?>
</body>
</html>