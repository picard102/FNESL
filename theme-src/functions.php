<?php
// Nothing yet, just here so WP loads itd

require_once __DIR__ . '/includes/cpt-projects.php';
require_once get_template_directory() . '/includes/assets.php';

add_action('after_setup_theme', function () {
	error_log("FNESL [functions.php] after_setup_theme – loading blocks…");
	require_once get_template_directory() . '/includes/blocks/project-hero/block.php';
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




/**
 * Ensures the global `window.wp.icons` object is available in both the block editor and frontend.
 *
 * This function injects a fallback initialization script for `window.wp.icons` after the relevant WordPress scripts
 * are loaded. It helps prevent errors in custom blocks or themes that expect `wp.icons` to exist, even if the
 * official icon scripts are not loaded.
 *
 * Actions Hooked:
 * - 'enqueue_block_editor_assets': Adds the fallback script after 'wp-block-editor' in the block editor.
 * - 'wp_enqueue_scripts': Adds the fallback script after 'wp-block-library' on the frontend.
 *
 * @since 1.0.0
 */

function fnesl_force_wp_icons_global() {
	// Inline a script after block-editor scripts
	add_action( 'enqueue_block_editor_assets', function () {
			wp_add_inline_script(
					'wp-block-editor', // safe: always loaded in editor
					'window.wp = window.wp || {}; window.wp.icons = window.wp.icons || {}; console.log("FNESL: wp.icons fallback initialized");',
					'after'
			);
	} );

	// Optional: frontend too, if your blocks render icons outside the editor
	add_action( 'wp_enqueue_scripts', function () {
			wp_add_inline_script(
					'wp-block-library',
					'window.wp = window.wp || {}; window.wp.icons = window.wp.icons || {}; console.log("FNESL: wp.icons frontend fallback initialized");',
					'after'
			);
	} );
}
add_action( 'init', 'fnesl_force_wp_icons_global' );