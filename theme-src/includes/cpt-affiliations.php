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

// // Disable Gutenberg for the affiliation CPT
// add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
//   if ($post_type === 'affiliation') {
//     return false;
//   }
//   return $use_block_editor;
// }, 10, 2);


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


function tpe_logo_width_percent_from_ratio( float $r ): float {
	// Clamp crazy values
	$r = max( 0.2, min( 8.0, $r ) );

	// Example piecewise mapping (tune to taste):
	// Tall logos (small r) should get a smaller width.
	// Wide logos (large r) can take more width.
	if ( $r < 0.6 ) {          // very tall
		return 32;
	}
	if ( $r < 1.0 ) {          // tall-ish
		return 40;
	}
	if ( $r < 1.6 ) {          // near-square to mild-wide
		return 60;
	}
	if ( $r < 2.5 ) {          // wide
		return 69;
	}
	if ( $r < 4.0 ) {          // very wide
		return 75;
	}

	return 79;                 // ultra-wide
}




