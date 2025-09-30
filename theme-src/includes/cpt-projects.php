<?php
/**
 * Register "Projects" Custom Post Type
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
    ];

    register_post_type('project', $args);
});