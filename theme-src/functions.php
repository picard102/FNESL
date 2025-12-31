<?php
// Nothing yet, just here so WP loads itd

require_once __DIR__ . '/includes/cpt-projects.php';
require_once __DIR__ . '/includes/cpt-profiles.php';
require_once get_template_directory() . '/includes/assets.php';

add_action('init', function () {
	//error_log("FNESL [functions.php] after_setup_theme – loading blocks…");
	register_block_type( get_stylesheet_directory() . '/includes/blocks/project-hero-v2' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/profile-card' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/project-cards' );
});


/**
 * Hooks into 'after_setup_theme' to enable theme features and register navigation menus.
 *
 * - Adds support for automatic document titles.
 * - Enables post thumbnails (featured images).
 * - Enables custom editor styles for the block editor.
 * - Enables default block styles for the block editor.
 * - Registers a 'primary' navigation menu.
 *
 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
 * @see https://developer.wordpress.org/reference/functions/register_nav_menus/
 */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
		register_nav_menus([
			'primary' => __('Primary Menu', 'fnesl'),
	]);

});


/**
 * Hooks into 'enqueue_block_editor_assets' to add an inline script that locks post autosaving in the block editor.
 *
 * This prevents the editor from automatically saving posts by dispatching the 'lockPostAutosaving' action
 * via the 'core/editor' data store when the block editor assets are enqueued.
 *
 * @see https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/
 * @see https://developer.wordpress.org/block-editor/reference-guides/data/data-core-editor/
 */
add_action( 'enqueue_block_editor_assets', function() {
    wp_add_inline_script(
        'wp-editor',
        'wp.data.dispatch("core/editor").lockPostAutosaving();'
    );
});


function fneslsprite() {
	$sprite_file = plugin_dir_path( __FILE__ ) . 'assets/sprite.svg';

	if ( file_exists( $sprite_file ) ) {
			echo '<div class="hidden" aria-hidden="true">';
			// Output as plain text, not parsed as PHP
			echo file_get_contents( $sprite_file );
			echo '</div>';
	}
}
add_action( 'wp_body_open', 'fneslsprite' );

function fnesl_sprite_in_editor() {
	$sprite_file = get_template_directory() . '/assets/sprite.svg'; // adjust path

	if ( file_exists( $sprite_file ) ) {
			echo '<div class="hidden" aria-hidden="true">';
			echo file_get_contents( $sprite_file );
			echo '</div>';
	}
}
add_action( 'admin_footer', 'fnesl_sprite_in_editor' ); // runs inside block editor iframe




add_action( 'after_setup_theme', function() {
	add_theme_support( 'editor-styles' );

	// Get the built banner file from manifest dynamically
	$banner_entry = fnesl_get_manifest_entry( 'css/banner.entry.css' );
	if ( $banner_entry && ! empty( $banner_entry['file'] ) ) {
		// Important: relative path from theme root, not full URI
		add_editor_style( 'assets/' . basename( $banner_entry['file'] ) );
	}
});


/**
 * Register a custom "FNESL" block category.
 */
add_filter( 'block_categories_all', function( $categories, $post ) {
    $fnesl_category = [
        'slug'  => 'fnesl',
        'title' => __( 'FNESL Blocks', 'fnesl' ),
        'icon'  => 'admin-site-alt3', // optional Dashicon
    ];

    // Add our category only if it doesn't already exist
    $slugs = wp_list_pluck( $categories, 'slug' );
    if ( ! in_array( 'fnesl', $slugs, true ) ) {
        $categories[] = $fnesl_category;
    }

    return $categories;
}, 10, 2 );

add_action('enqueue_block_editor_assets', function () {
  // Ensure apiFetch exists for any block that imports @wordpress/api-fetch
  wp_enqueue_script('wp-api-fetch');
}, 0);