<?php
// Nothing yet, just here so WP loads itd

require_once __DIR__ . '/includes/cpt-projects.php';
require_once get_template_directory() . '/includes/assets.php';
require_once __DIR__ . '/includes/blocks/project-hero/block.php';

// Enable theme supports
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
		register_nav_menus([
			'primary' => __('Primary Menu', 'fnesl'),
	]);

});


add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
	if ( str_starts_with( $handle, 'fnesl-project-hero' ) ) {
			return '<script type="module" src="' . esc_url( $src ) . '" id="' . esc_attr( $handle ) . '"></script>';
	}
	return $tag;
}, 10, 3 );


