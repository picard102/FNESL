<?php
/**
 * Register "Projects" Custom Post Type + Taxonomies
 * + Project Archive REST endpoint (with expertise term icon support + parent fallback)
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
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
		'show_in_rest'       => true,
		'show_in_nav_menus'  => true,

		'template' => [
			[
				'fnesl/project-hero-v2',
				[
					'showOverlay' => true,
					'fontSize'    => '3xl',
				],
			],
			[
				'core/paragraph',
				[
					'content'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
					'textColor'   => 'primary',
					'fontSize'    => 'lg',
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

require_once __DIR__ . '/expertise-taxonomy.php';

// -----------------------------
// Client taxonomy
// -----------------------------
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
		'show_in_rest'      => true,
	];

	register_taxonomy('client', ['project'], $args);
});

// -----------------------------
// Location taxonomy
// -----------------------------
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
		'show_in_rest'      => true,
	];

	register_taxonomy('location', ['project'], $args);
});

// -----------------------------
// Location term meta – lat/lng for map dot placement
// -----------------------------
add_action( 'init', function () {
	foreach ( [ 'location_lat', 'location_lng' ] as $key ) {
		register_term_meta( 'location', $key, [
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => function ( $v ) {
				$f = filter_var( $v, FILTER_VALIDATE_FLOAT );
				return ( $f !== false ) ? (string) $f : '';
			},
			'show_in_rest'      => true,
		] );
	}
} );

// Admin form – Add term screen
add_action( 'location_add_form_fields', function () { ?>
	<div class="form-field">
		<label for="location-lat"><?php esc_html_e( 'Latitude', 'fnesl' ); ?></label>
		<input type="text" id="location-lat" name="location_lat" placeholder="e.g. 49.895" />
		<p><?php esc_html_e( 'Optional. Used to position a dot on the location map.', 'fnesl' ); ?></p>
	</div>
	<div class="form-field">
		<label for="location-lng"><?php esc_html_e( 'Longitude', 'fnesl' ); ?></label>
		<input type="text" id="location-lng" name="location_lng" placeholder="e.g. -97.138" />
	</div>
<?php } );

// Admin form – Edit term screen
add_action( 'location_edit_form_fields', function ( WP_Term $term ) {
	$lat = get_term_meta( $term->term_id, 'location_lat', true );
	$lng = get_term_meta( $term->term_id, 'location_lng', true ); ?>
	<tr class="form-field">
		<th scope="row"><label for="location-lat"><?php esc_html_e( 'Latitude', 'fnesl' ); ?></label></th>
		<td>
			<input type="text" id="location-lat" name="location_lat" value="<?php echo esc_attr( $lat ); ?>" placeholder="e.g. 49.895" />
			<p class="description"><?php esc_html_e( 'Optional. Used to position a dot on the location map.', 'fnesl' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="location-lng"><?php esc_html_e( 'Longitude', 'fnesl' ); ?></label></th>
		<td>
			<input type="text" id="location-lng" name="location_lng" value="<?php echo esc_attr( $lng ); ?>" />
		</td>
	</tr>
<?php } );

// Save both on add and edit
function _fnesl_save_location_meta( int $term_id ): void {
	foreach ( [ 'location_lat', 'location_lng' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$val = filter_var( wp_unslash( $_POST[ $key ] ), FILTER_VALIDATE_FLOAT ); // phpcs:ignore
			if ( $val !== false ) {
				update_term_meta( $term_id, $key, (string) $val );
			} else {
				delete_term_meta( $term_id, $key );
			}
		}
	}
}
add_action( 'created_location', '_fnesl_save_location_meta' );
add_action( 'edited_location',  '_fnesl_save_location_meta' );

// -----------------------------
// Partners taxonomy
// -----------------------------
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
		'show_in_rest'      => true,
	];

	register_taxonomy('partners', ['project'], $args);
});

// -----------------------------
// Award taxonomy (NOTE: singular "award")
// -----------------------------
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
		'show_in_rest'      => true,
	];

	register_taxonomy('award', ['project'], $args);
});

/**
 * Resolve a term icon (SVG attachment) with parent fallback.
 * Returns null or: [ 'attachmentId' => int, 'url' => string, 'termId' => int ]
 */
function fnesl_resolve_term_icon( $term, $taxonomy = 'expertise', $meta_key = 'fnesl_term_icon_svg_id' ) {
	if ( is_numeric( $term ) ) {
		$term = get_term( (int) $term, $taxonomy );
	}
	if ( ! $term || is_wp_error( $term ) ) return null;

	$seen = [];

	while ( $term && ! is_wp_error( $term ) ) {
		if ( isset( $seen[ $term->term_id ] ) ) break;
		$seen[ $term->term_id ] = true;

		$icon_id = (int) get_term_meta( $term->term_id, $meta_key, true );

		if ( $icon_id ) {
			$url  = wp_get_attachment_url( $icon_id );
			$mime = get_post_mime_type( $icon_id );

			if ( $url && $mime === 'image/svg+xml' ) {
				return [
					'attachmentId' => $icon_id,
					'url'          => (string) $url,
					'termId'       => (int) $term->term_id,
				];
			}
		}

		$parent_id = (int) $term->parent;
		if ( $parent_id > 0 ) {
			$term = get_term( $parent_id, $taxonomy );
			continue;
		}

		break;
	}

	return null;
}

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
 *   "show": ["expertise","partners","location","client","awards"],    // optional; used to limit filters returned
 *   "includeFilters": true | false                                   // optional; default true
 * }
 */
add_action( 'rest_api_init', function () {

		register_rest_route( 'fnesl/v1', '/project-archive', [
			'methods'             => 'POST',
			'permission_callback' => '__return_true',
			'callback'            => function ( WP_REST_Request $req ) {

				$taxonomy_aliases = [
					'awards' => 'award',
				];
				$allowed_tax = [ 'expertise', 'partners', 'location', 'client', 'award' ];

				// --- Inputs ---
				$page    = max( 1, (int) $req->get_param( 'page' ) );

			$perPage = (int) $req->get_param( 'perPage' );
			$perPage = max( 1, min( 100, $perPage > 0 ? $perPage : 12 ) );

			$mode_in = (string) $req->get_param( 'mode' );
			$mode    = ( $mode_in === 'or' ) ? 'OR' : 'AND';

			$include_filters = $req->get_param( 'includeFilters' );
			$include_filters = ( $include_filters === null ) ? true : (bool) $include_filters;

				$terms_by_tax = (array) $req->get_param( 'terms' );
				foreach ( $taxonomy_aliases as $public_tax => $internal_tax ) {
					if ( isset( $terms_by_tax[ $public_tax ] ) && ! isset( $terms_by_tax[ $internal_tax ] ) ) {
						$terms_by_tax[ $internal_tax ] = $terms_by_tax[ $public_tax ];
					}
				}

				$show = $req->get_param( 'show' );
				if ( is_string( $show ) ) {
					$show = array_filter( array_map( 'trim', explode( ',', $show ) ) );
				}
				$show = is_array( $show ) ? $show : array_merge( $allowed_tax, array_keys( $taxonomy_aliases ) );
				$show = array_map( 'sanitize_key', $show );
				$show = array_map(
					static function ( $tax ) use ( $taxonomy_aliases ) {
						return $taxonomy_aliases[ $tax ] ?? $tax;
					},
					$show
				);
				$show = array_values( array_unique( array_intersect( $allowed_tax, $show ) ) );

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

				// Featured image required: accurate totals/pagination
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
					$post_id = (int) $p->ID;

					// Defaults (stable payload)
					$selected_expertise = 0;
					$expertise_name     = '';
					$expertise_slug     = '';
					$expertise_icon     = null;

					// ---------------------------------------
					// Expertise: selected term OR first assigned
					// ---------------------------------------
					$selected_expertise = (int) get_post_meta( $post_id, 'selected_expertise', true );
					if ( ! $selected_expertise ) $selected_expertise = 0;

					if ( ! $selected_expertise ) {
						$expertise_terms = get_the_terms( $post_id, 'expertise' );
						if ( $expertise_terms && ! is_wp_error( $expertise_terms ) ) {
							$selected_expertise = (int) ( $expertise_terms[0]->term_id ?? 0 );
						}
					}

					if ( $selected_expertise ) {
						$expertise_term = get_term( $selected_expertise, 'expertise' );

						if ( $expertise_term && ! is_wp_error( $expertise_term ) ) {
							$expertise_name = html_entity_decode(
								(string) $expertise_term->name,
								ENT_QUOTES | ENT_HTML5,
								'UTF-8'
							);
							$expertise_slug = (string) $expertise_term->slug;

							$expertise_icon = fnesl_resolve_term_icon(
								$expertise_term,
								'expertise',
								'fnesl_term_icon_svg_id'
							);
						}
					}

					$projects[] = [
						'id'            => $post_id,
						'title'         => html_entity_decode(
							get_the_title( $post_id ),
							ENT_QUOTES | ENT_HTML5,
							'UTF-8'
						),
						'link'          => (string) get_permalink( $post_id ),
						'image'         => (string) get_the_post_thumbnail_url( $post_id, 'large' ),
						'expertiseId'   => $selected_expertise ? (int) $selected_expertise : null,
						'expertiseName' => (string) $expertise_name,
						'expertiseSlug' => (string) $expertise_slug,
						'expertiseIcon' => $expertise_icon, // null or { attachmentId, url, termId }
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

					$mapped = array_map( function ( $t ) use ( $tax ) {
						$icon = null;

						if ( $tax === 'expertise' ) {
							$icon = fnesl_resolve_term_icon( $t, 'expertise', 'fnesl_term_icon_svg_id' );
						}

						return [
							'id'          => (int) $t->term_id,
							'name'        => (string) $t->name,
							'slug'        => (string) $t->slug,
							'count'       => (int) $t->count,
							'parent'      => (int) $t->parent,
							'description' => (string) $t->description,
							'icon'        => $icon, // null or { attachmentId, url, termId }
						];
					}, $terms );

						$public_tax = array_search( $tax, $taxonomy_aliases, true );
						$filters[ $public_tax ?: $tax ] = $mapped;
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
