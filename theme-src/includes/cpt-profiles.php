<?php
/**
 * Register "Profiles" Custom Post Type
 */

/**
 * Registers the 'project' custom post type for Profiles.
 *
 * - Sets up labels for admin UI and REST API.
 * - Enables support for title, editor, thumbnail, excerpt, and custom fields.
 * - Makes the post type publicly queryable, with archive and REST API support.
 * - Uses 'Profiles' as the URL slug and displays in the admin menu with a portfolio icon.
 *
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 */
add_action('init', function () {
    $labels = [
        'name'               => __('Profiles', 'fnesl'),
        'singular_name'      => __('Profile', 'fnesl'),
        'menu_name'          => __('Profiles', 'fnesl'),
        'name_admin_bar'     => __('Profile', 'fnesl'),
        'add_new'            => __('Add New', 'fnesl'),
        'add_new_item'       => __('Add New Profile', 'fnesl'),
        'new_item'           => __('New Profile', 'fnesl'),
        'edit_item'          => __('Edit Profile', 'fnesl'),
        'view_item'          => __('View Profile', 'fnesl'),
        'all_items'          => __('All Profiles', 'fnesl'),
        'search_items'       => __('Search Profiles', 'fnesl'),
        'parent_item_colon'  => __('Parent Profiles:', 'fnesl'),
        'not_found'          => __('No Profiles found.', 'fnesl'),
        'not_found_in_trash' => __('No Profiles found in Trash.', 'fnesl'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'profiles'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-id-alt',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'show_in_rest'       => true, // ✅ enables Gutenberg + REST API
				'show_in_nav_menus'  => true,
    ];

    register_post_type('profile', $args);
});



// Register Roles Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Roles', 'fnesl'),
        'singular_name'     => __('Role', 'fnesl'),
        'search_items'      => __('Search Roles', 'fnesl'),
        'all_items'         => __('All Roles', 'fnesl'),
        'parent_item'       => __('Parent Roles', 'fnesl'),
        'parent_item_colon' => __('Parent Roles:', 'fnesl'),
        'edit_item'         => __('Edit Role', 'fnesl'),
        'update_item'       => __('Update Role', 'fnesl'),
        'add_new_item'      => __('Add New Role', 'fnesl'),
        'new_item_name'     => __('New Role Name', 'fnesl'),
        'menu_name'         => __('Roles', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => true, // acts like categories (true) vs tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'Roles'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('Roles', ['profile'], $args);
});



// Register FN Affiliations Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('FN Affiliations', 'fnesl'),
        'singular_name'     => __('FN Affiliation', 'fnesl'),
        'search_items'      => __('Search FN Affiliations', 'fnesl'),
        'all_items'         => __('All FN Affiliations', 'fnesl'),
        'parent_item'       => __('Parent FN Affiliations', 'fnesl'),
        'parent_item_colon' => __('Parent FN Affiliations:', 'fnesl'),
        'edit_item'         => __('Edit FN Affiliation', 'fnesl'),
        'update_item'       => __('Update FN Affiliation', 'fnesl'),
        'add_new_item'      => __('Add New FN Affiliation', 'fnesl'),
        'new_item_name'     => __('New FN Affiliation Name', 'fnesl'),
        'menu_name'         => __('FN Affiliations', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => false, // acts like categories (true) vs tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'fn-affiliations'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('fn-affiliations', ['profile'], $args);
});




// Register Credentials Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Credentials', 'fnesl'),
        'singular_name'     => __('Credential', 'fnesl'),
        'search_items'      => __('Search Credentials', 'fnesl'),
        'all_items'         => __('All Credentials', 'fnesl'),
        'parent_item'       => __('Parent Credentials', 'fnesl'),
        'parent_item_colon' => __('Parent Credentials:', 'fnesl'),
        'edit_item'         => __('Edit Credential', 'fnesl'),
        'update_item'       => __('Update Credential', 'fnesl'),
        'add_new_item'      => __('Add New Credential', 'fnesl'),
        'new_item_name'     => __('New Credential Name', 'fnesl'),
        'menu_name'         => __('Credentials', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => true, // acts like categories (true) vs tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'credentials'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('credentials', ['profile'], $args);
});




// Register Education Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Education', 'fnesl'),
        'singular_name'     => __('Education', 'fnesl'),
        'search_items'      => __('Search Education', 'fnesl'),
        'all_items'         => __('All Education', 'fnesl'),
        'parent_item'       => __('Parent Education', 'fnesl'),
        'parent_item_colon' => __('Parent Education:', 'fnesl'),
        'edit_item'         => __('Edit Education', 'fnesl'),
        'update_item'       => __('Update Education', 'fnesl'),
        'add_new_item'      => __('Add New Education', 'fnesl'),
        'new_item_name'     => __('New EducationName', 'fnesl'),
        'menu_name'         => __('Education', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => true, // acts like categories (true) vs tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'education'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('education', ['profile'], $args);
});






