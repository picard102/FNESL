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
$affiliations = get_posts( array(
	'post_type'      => 'affiliation', // <-- your CPT slug
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
	'tax_query'      => array(
		array(
			'taxonomy' => 'placement', // <-- your taxonomy slug
			'field'    => 'slug',
			'terms'    => array( 'footer' ), // <-- term slug
		),
	),
) );

// Randomize order
shuffle($affiliations);

if ( $affiliations ) : ?>

<div class="flex gap-6 flex-col col-span-12">

<div class="text-sm border-b border-primary-600 pb-3">Affiliations & Certifications</div>





	<ul class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-7">
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



<?php

$opt = get_option(
		'theme_footer_settings',
		array(
			'contact_rows' => array(),
			'social_links' => array(),
		)
	);

	if ( ! is_array( $opt ) ) {
		$opt = array();
	}

	$opt['contact_rows'] = ( isset( $opt['contact_rows'] ) && is_array( $opt['contact_rows'] ) ) ? $opt['contact_rows'] : array();
	$opt['social_links'] = ( isset( $opt['social_links'] ) && is_array( $opt['social_links'] ) ) ? $opt['social_links'] : array();







?>


<div class="flex flex-col gap-4 text-sm  col-span-4 border-t py-6 border-primary-600 grid grid-cols-[auto_1fr] gap-x-6">


<?php

	$rows = $opt['contact_rows'];

	$has_any = false;
	foreach ( $rows as $row ) {
		$label = isset( $row['label'] ) ? trim( (string) $row['label'] ) : '';
		$value = isset( $row['value'] ) ? trim( (string) $row['value'] ) : '';
		if ( $label !== '' || $value !== '' ) {
			$has_any = true;
			break;
		}
	}

	if ( ! $has_any ) {
		return;
	}


	foreach ( $rows as $row ) {
		$label = isset( $row['label'] ) ? trim( (string) $row['label'] ) : '';
		$value = isset( $row['value'] ) ? trim( (string) $row['value'] ) : '';

		if ( $label === '' && $value === '' ) {
			continue;
		}

		if ( $label !== '' ) {
			echo '<span>' . esc_html( $label ) . '</span>';
		}

		if ( $value !== '' ) {
			echo '<span itemprop="telephone" class="text-white">' . nl2br( esc_html( $value ) ) . '</span>';
		}

	}


	?>


</div>

</div>







	<div class="flex gap-4 ">

<?php


$links = $opt['social_links'];



	foreach ( $links as $row ) {

		$network = isset( $row['network'] ) ? sanitize_key( (string) $row['network'] ) : '';
		$url     = isset( $row['url'] ) ? trim( (string) $row['url'] ) : '';

		if ( $url === '' ) {
			continue;
		}

		$label = $network;

		echo '<a class="flex bg-primary-600 hover:bg-primary-800
		transition-colors duration-300 ease-in-out p-2 rounded" href="' . esc_url( $url ) . '" target="_blank" rel="noopener" aria-label="' . esc_attr( $label ) . '">';


			echo '		<svg class=" aspect-square  h-6 fill-current  inline " aria-hidden="true">
			<use xlink:href="#icons_' . esc_html( $label ) . '"></use>
			</svg>';
			echo '<span class="screen-reader-text">' . esc_html( $label ) . '</span>';


		echo '</a>';

	}



?>


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