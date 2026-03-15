<?php
/**
 * Register "Affiliations" Custom Post Type
 */

/**
 * Registers the 'Affiliation' custom post type for Affiliations.
 *
 * - Sets up labels for admin UI and REST API.
 * - Enables support for title, editor, thumbnail, excerpt, and custom fields.
 * - Makes the post type publicly queryable, with archive and REST API support.
 * - Uses 'Affiliations' as the URL slug and displays in the admin menu with a portfolio icon.
 *
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 */
add_action('init', function () {
    $labels = [
        'name'               => __('Affiliations', 'fnesl'),
        'singular_name'      => __('Affiliation', 'fnesl'),
        'menu_name'          => __('Affiliations', 'fnesl'),
        'name_admin_bar'     => __('Affiliation', 'fnesl'),
        'add_new'            => __('Add New', 'fnesl'),
        'add_new_item'       => __('Add New Affiliation', 'fnesl'),
        'new_item'           => __('New Affiliation', 'fnesl'),
        'edit_item'          => __('Edit Affiliation', 'fnesl'),
        'view_item'          => __('View Affiliation', 'fnesl'),
        'all_items'          => __('All Affiliations', 'fnesl'),
        'search_items'       => __('Search Affiliations', 'fnesl'),
        'parent_item_colon'  => __('Parent Affiliations:', 'fnesl'),
        'not_found'          => __('No Affiliations found.', 'fnesl'),
        'not_found_in_trash' => __('No Affiliations found in Trash.', 'fnesl'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'Affiliations'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
				'supports' => ['title', 'editor', 'excerpt', 'custom-fields', 'page-attributes'],
				'show_in_rest'       => true, // ✅ enables Gutenberg + REST API
				'show_in_nav_menus'  => true,
    ];

    register_post_type('affiliation', $args);
});

require_once __DIR__ . '/affiliations/svg-meta.php';

// Disable Gutenberg for the affiliation CPT
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
  if ($post_type === 'affiliation') {
    return false;
  }
  return $use_block_editor;
}, 10, 2);


/**
 * Return inline SVG markup for a post's featured image (if it's an SVG attachment).
 * Adds classes to the <svg> element so Tailwind can style it.
 *
 * Usage:
 * echo tpe_inline_featured_svg( $post_id, 'w-full h-auto fill-current text-white' );
 */
/**
 * Inline an SVG for an affiliation.
 * Prefers the "single colour" logo meta, falls back to "full colour" logo meta,
 * and finally falls back to the featured image (optional).
 */
function tpe_inline_featured_svg( int $post_id, string $svg_class = '' ): string {

	$thumb_id = 0;

	// ✅ Prefer single-colour meta, then full-colour meta
	$one_id  = (int) get_post_meta( $post_id, 'affiliation_svg_logo_1c_id', true );
	$full_id = (int) get_post_meta( $post_id, 'affiliation_svg_logo_id', true );

	if ( $one_id ) {
		$thumb_id = $one_id;
	} elseif ( $full_id ) {
		$thumb_id = $full_id;
	} else {
		// Optional fallback to featured image
		$thumb_id = (int) get_post_thumbnail_id( $post_id );
	}

	if ( ! $thumb_id ) {
		return '';
	}

	$mime = get_post_mime_type( $thumb_id );
	if ( $mime !== 'image/svg+xml' ) {
		return '';
	}

	$path = get_attached_file( $thumb_id );
	if ( ! $path || ! file_exists( $path ) ) {
		return '';
	}

	$svg = file_get_contents( $path );
	if ( ! $svg ) {
		return '';
	}

	// Remove <style> blocks
	$svg = preg_replace( '#<style\b[^>]*>(.*?)</style>#is', '', $svg );

	// Remove inline style attributes
	$svg = preg_replace( '/\sstyle=("|\')(.*?)\1/i', '', $svg );

	// Remove solid fill attributes (keep none, currentColor, gradients)
	$svg = preg_replace( '/\sfill=("|\')(?!none|currentColor|url\()(.*?)\1/i', '', $svg );

	// Remove solid stroke attributes (same logic)
	$svg = preg_replace( '/\sstroke=("|\')(?!none|currentColor|url\()(.*?)\1/i', '', $svg );

	// Strip absolute width / height attributes from the <svg> root so that CSS
	// can control the rendered size without fighting inline HTML attributes.
	// viewBox is preserved so the SVG still knows its own aspect ratio.
	$svg = preg_replace_callback(
		'/(<svg\b[^>]*)\s(?:width|height)=([\'"])[\d.%a-zA-Z]+\2/i',
		function ( $m ) { return $m[1]; },
		$svg
	);
	// Run twice to catch both width and height when they appear in either order.
	$svg = preg_replace_callback(
		'/(<svg\b[^>]*)\s(?:width|height)=([\'"])[\d.%a-zA-Z]+\2/i',
		function ( $m ) { return $m[1]; },
		$svg
	);

	// Basic hardening: strip scripts and on* handlers.
	$svg = preg_replace( '#<script\b[^>]*>(.*?)</script>#is', '', $svg );
	$svg = preg_replace( '/\son\w+="[^"]*"/i', '', $svg );
	$svg = preg_replace( "/\son\w+='[^']*'/i", '', $svg );

	// Inject/merge class into the <svg> tag.
	if ( $svg_class ) {
		if ( preg_match( '/<svg\b[^>]*\bclass=([\'"])(.*?)\1/i', $svg ) ) {
			$svg = preg_replace_callback(
				'/(<svg\b[^>]*\bclass=)([\'"])(.*?)(\2)/i',
				function ( $m ) use ( $svg_class ) {
					$existing = trim( $m[3] );
					$merged   = trim( $existing . ' ' . $svg_class );
					return $m[1] . $m[2] . esc_attr( $merged ) . $m[4];
				},
				$svg,
				1
			);
		} else {
			$svg = preg_replace(
				'/<svg\b/i',
				'<svg class="' . esc_attr( $svg_class ) . '"',
				$svg,
				1
			);
		}
	}

	return $svg;
}





function tpe_svg_aspect_ratio_from_attachment( int $attachment_id ): ?float {
	if ( get_post_mime_type( $attachment_id ) !== 'image/svg+xml' ) {
		return null;
	}

	$path = get_attached_file( $attachment_id );
	if ( ! $path || ! file_exists( $path ) ) {
		return null;
	}

	$svg = file_get_contents( $path );
	if ( ! $svg ) {
		return null;
	}

	// Prefer viewBox: "minX minY width height"
	if ( preg_match( '/viewBox\s*=\s*["\']\s*[-\d.]+\s+[-\d.]+\s+([\d.]+)\s+([\d.]+)\s*["\']/i', $svg, $m ) ) {
		$w = (float) $m[1];
		$h = (float) $m[2];
		return ( $h > 0 ) ? ( $w / $h ) : null;
	}

	// Fallback: width/height attributes (less reliable, but better than nothing)
	if (
		preg_match( '/\bwidth\s*=\s*["\']\s*([\d.]+)\s*["\']/i', $svg, $mw ) &&
		preg_match( '/\bheight\s*=\s*["\']\s*([\d.]+)\s*["\']/i', $svg, $mh )
	) {
		$w = (float) $mw[1];
		$h = (float) $mh[1];
		return ( $h > 0 ) ? ( $w / $h ) : null;
	}

	return null;
}


/**
 * Convert an SVG aspect ratio (width ÷ height) to a container width percentage
 * that gives each logo an approximately equal perceived visual weight.
 *
 * FORMULA — equal visual-area scaling
 * ------------------------------------
 * When a logo is rendered in a fixed-height cell its displayed area equals:
 *   area = width × (width / ratio) = width² / ratio
 *
 * For all logos to have the same area, width² / ratio = constant, therefore:
 *   width = base × √ratio
 *
 * The constant `BASE` is calibrated so that a perfectly square logo (ratio = 1)
 * occupies 55 % of the cell — matching the pre-existing fallback default.
 *
 * This produces a smooth, continuous curve instead of the previous step
 * function, which caused visible jumps between adjacent aspect ratios.
 */
function tpe_logo_width_percent_from_ratio( float $r ): float {
	$base  = 55.36; // % width for a 1:1 square logo — tune this one knob

	$r     = max( 0.2, min( 8.0, $r ) );                // clamp extreme ratios
	$w_pct = $base * sqrt( $r );

	return max( 20.0, min( 95.0, $w_pct ) );            // hard floor / ceiling
}










// Register Award Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Placement', 'fnesl'),
        'singular_name'     => __('Placement', 'fnesl'),
        'search_items'      => __('Search Placements', 'fnesl'),
        'all_items'         => __('All Placements', 'fnesl'),
        'parent_item'       => __('Parent Placement', 'fnesl'),
        'parent_item_colon' => __('Parent Placement:', 'fnesl'),
        'edit_item'         => __('Edit Placements', 'fnesl'),
        'update_item'       => __('Update Placement', 'fnesl'),
        'add_new_item'      => __('Add New Placement', 'fnesl'),
        'new_item_name'     => __('New Placement Name', 'fnesl'),
        'menu_name'         => __('Placement', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'placement'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('placement', ['affiliation'], $args);
});
