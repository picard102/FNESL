<?php
// Nothing yet, just here so WP loads itd

require_once get_template_directory() . '/includes/assets.php';

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