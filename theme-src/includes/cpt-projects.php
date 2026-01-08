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
				'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
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



// Register Partners Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Partners', 'fnesl'),
        'singular_name'     => __('Partners', 'fnesl'),
        'search_items'      => __('Search Partners', 'fnesl'),
        'all_items'         => __('All Partners', 'fnesl'),
        'parent_item'       => __('Parent Partners', 'fnesl'),
        'parent_item_colon' => __('Parent Partners:', 'fnesl'),
        'edit_item'         => __('Edit Partners', 'fnesl'),
        'update_item'       => __('Update Partners', 'fnesl'),
        'add_new_item'      => __('Add New Partners', 'fnesl'),
        'new_item_name'     => __('New Partners Name', 'fnesl'),
        'menu_name'         => __('Partners', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'partners'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('partners', ['project'], $args);
});


// Register Award Taxonomy
add_action('init', function () {
    $labels = [
        'name'              => __('Award', 'fnesl'),
        'singular_name'     => __('Award', 'fnesl'),
        'search_items'      => __('Search Awards', 'fnesl'),
        'all_items'         => __('All Awards', 'fnesl'),
        'parent_item'       => __('Parent Award', 'fnesl'),
        'parent_item_colon' => __('Parent Award:', 'fnesl'),
        'edit_item'         => __('Edit Awards', 'fnesl'),
        'update_item'       => __('Update Award', 'fnesl'),
        'add_new_item'      => __('Add New Award', 'fnesl'),
        'new_item_name'     => __('New Award Name', 'fnesl'),
        'menu_name'         => __('Award', 'fnesl'),
    ];

    $args = [
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => false,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'award'],
        'show_in_rest'      => true, // ✅ makes taxonomy Gutenberg + REST friendly
    ];

    register_taxonomy('award', ['project'], $args);
});




/**
 * Single JSON endpoint for Project Archive (filters + projects + pagination)
 *
 * POST /wp-json/fnesl/v1/project-archive
 * Body:
 * {
 *   "page": 1,
 *   "perPage": 12,
 *   "mode": "and" | "or",
 *   "terms": { "expertise":[1,2], "partners":[...], "location":[...], "client":[...], "awards":[...] },
 *   "show": ["expertise","partners","location","client","awards"],   // optional; used to limit filters returned
 *   "includeFilters": true | false                                  // optional; default true
 * }
 */
add_action( 'rest_api_init', function () {

	register_rest_route( 'fnesl/v1', '/project-archive', [
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {

			$allowed_tax = [ 'expertise', 'partners', 'location', 'client', 'awards' ];

			// --- Inputs ---
			$page    = max( 1, (int) $req->get_param( 'page' ) );
			$perPage = (int) $req->get_param( 'perPage' );
			$perPage = max( 1, min( 100, $perPage > 0 ? $perPage : 12 ) );

			$mode_in = (string) $req->get_param( 'mode' );
			$mode    = ( $mode_in === 'or' ) ? 'OR' : 'AND';

			$include_filters = $req->get_param( 'includeFilters' );
			$include_filters = ( $include_filters === null ) ? true : (bool) $include_filters;

			$terms_by_tax = (array) $req->get_param( 'terms' );

			$show = $req->get_param( 'show' );
			if ( is_string( $show ) ) {
				$show = array_filter( array_map( 'trim', explode( ',', $show ) ) );
			}
			$show = is_array( $show ) ? $show : $allowed_tax;
			$show = array_values( array_intersect( $allowed_tax, array_map( 'sanitize_key', $show ) ) );

			// --- Build tax_query ---
			$tax_query = [ 'relation' => $mode ];

			foreach ( $allowed_tax as $tax ) {
				$ids = $terms_by_tax[ $tax ] ?? [];
				if ( ! is_array( $ids ) || empty( $ids ) ) continue;

				$ids = array_values( array_filter( array_map( 'intval', $ids ) ) );
				if ( empty( $ids ) ) continue;

				$tax_query[] = [
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $ids,
					'operator' => 'IN',
				];
			}

			$args = [
				'post_type'           => 'project',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				'posts_per_page'      => $perPage,
				'paged'               => $page,

				// ✅ Featured image required: accurate totals/pagination
				'meta_query'          => [
					[
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					],
				],
			];

			// Only apply tax_query if something is selected
			if ( count( $tax_query ) > 1 ) {
				$args['tax_query'] = $tax_query;
			}

			$q = new WP_Query( $args );

			$projects = [];
			if ( $q->have_posts() ) {
				foreach ( $q->posts as $p ) {
					$projects[] = [
						'id'    => (int) $p->ID,
						'title' => (string) get_the_title( $p->ID ),
						'link'  => (string) get_permalink( $p->ID ),
						'image' => (string) get_the_post_thumbnail_url( $p->ID, 'large' ),
					];
				}
			}

			$total       = (int) $q->found_posts;
			$total_pages = max( 1, (int) $q->max_num_pages );

			wp_reset_postdata();

			// --- Filters payload (optional) ---
			$filters = null;

			if ( $include_filters ) {
				$filters = [];

				foreach ( $show as $tax ) {
					if ( ! taxonomy_exists( $tax ) ) continue;

					$terms = get_terms([
						'taxonomy'   => $tax,
						'hide_empty' => true,
						'orderby'    => 'name',
						'order'      => 'ASC',
					]);

					if ( is_wp_error( $terms ) || empty( $terms ) ) continue;

					$filters[ $tax ] = array_map( function ( $t ) {
						return [
							'id'   => (int) $t->term_id,
							'name' => (string) $t->name,
							'slug' => (string) $t->slug,
							'count'=> (int) $t->count,
							'parent'=> (int) $t->parent,
							'description' => (string) $t->description,
						];
					}, $terms );
				}
			}

			$response = [
				'mode'       => strtolower( $mode ),
				'page'       => $page,
				'perPage'    => $perPage,
				'projects'   => $projects,
				'total'      => $total,
				'totalPages' => $total_pages,
			];

			if ( $include_filters ) {
				$response['filters'] = $filters;
			}

			return $response;
		},
	] );
} );