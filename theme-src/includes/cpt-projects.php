<?php
/**
 * Register "Projects" Custom Post Type
 */

/**
 * Registers the 'project' custom post type for Projects.
 *
 * - Sets up labels for admin UI and REST API.
 * - Enables support for title, editor, thumbnail, excerpt, and custom fields.
 * - Makes the post type publicly queryable, with archive and REST API support.
 * - Uses 'projects' as the URL slug and displays in the admin menu with a portfolio icon.
 *
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 */
add_action('init', function () {
    $labels = [
        'name'               => __('Projects', 'fnesl'),
        'singular_name'      => __('Project', 'fnesl'),
        'menu_name'          => __('Projects', 'fnesl'),
        'name_admin_bar'     => __('Project', 'fnesl'),
        'add_new'            => __('Add New', 'fnesl'),
        'add_new_item'       => __('Add New Project', 'fnesl'),
        'new_item'           => __('New Project', 'fnesl'),
        'edit_item'          => __('Edit Project', 'fnesl'),
        'view_item'          => __('View Project', 'fnesl'),
        'all_items'          => __('All Projects', 'fnesl'),
        'search_items'       => __('Search Projects', 'fnesl'),
        'parent_item_colon'  => __('Parent Projects:', 'fnesl'),
        'not_found'          => __('No projects found.', 'fnesl'),
        'not_found_in_trash' => __('No projects found in Trash.', 'fnesl'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'projects'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest'       => true, // ✅ enables Gutenberg + REST API
				'show_in_nav_menus'  => true,

				'template' => [
					[
					'fnesl/project-hero-v2',
					[
						'showOverlay'    => true,
						'fontSize'       => '3xl',
					],
					],
					[
						'core/paragraph',
						[
							'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
							'textColor' => 'primary',
							'fontSize'  => 'lg',
							'textColor'   => 'primary',
							'fontFamily'  => 'ibm-plex-serif',
						],
					],
					[
						'core/paragraph',
						[
							'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi.',
						],
					],




				],

    ];

    register_post_type('project', $args);
});


// Register Expertise Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Expertise', 'fnesl'),
        'singular_name'     => __('Expertise', 'fnesl'),
        'search_items'      => __('Search Expertise', 'fnesl'),
        'all_items'         => __('All Expertise', 'fnesl'),
        'parent_item'       => __('Parent Expertise', 'fnesl'),
        'parent_item_colon' => __('Parent Expertise:', 'fnesl'),
        'edit_item'         => __('Edit Expertise', 'fnesl'),
        'update_item'       => __('Update Expertise', 'fnesl'),
        'add_new_item'      => __('Add New Expertise', 'fnesl'),
        'new_item_name'     => __('New Expertise Name', 'fnesl'),
        'menu_name'         => __('Expertise', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => true, // acts like categories (true) vs tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'expertise'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('expertise', ['project'], $args);
});




// Register Client Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Client', 'fnesl'),
        'singular_name'     => __('Client', 'fnesl'),
        'search_items'      => __('Search Clients', 'fnesl'),
        'all_items'         => __('All Clients', 'fnesl'),
        'parent_item'       => __('Parent Client', 'fnesl'),
        'parent_item_colon' => __('Parent Client:', 'fnesl'),
        'edit_item'         => __('Edit Clients', 'fnesl'),
        'update_item'       => __('Update Client', 'fnesl'),
        'add_new_item'      => __('Add New Client', 'fnesl'),
        'new_item_name'     => __('New Client Name', 'fnesl'),
        'menu_name'         => __('Client', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'client'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('client', ['project'], $args);
});



// Register Location Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Location', 'fnesl'),
        'singular_name'     => __('Location', 'fnesl'),
        'search_items'      => __('Search Locations', 'fnesl'),
        'all_items'         => __('All Locations', 'fnesl'),
        'parent_item'       => __('Parent Location', 'fnesl'),
        'parent_item_colon' => __('Parent Location:', 'fnesl'),
        'edit_item'         => __('Edit Locations', 'fnesl'),
        'update_item'       => __('Update Location', 'fnesl'),
        'add_new_item'      => __('Add New Location', 'fnesl'),
        'new_item_name'     => __('New Location Name', 'fnesl'),
        'menu_name'         => __('Location', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'location'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('location', ['project'], $args);
});



