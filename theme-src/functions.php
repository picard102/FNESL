<?php
// Nothing yet, just here so WP loads itd

require_once __DIR__ . '/includes/cpt-projects.php';
require_once __DIR__ . '/includes/cpt-profiles.php';
require_once __DIR__ . '/includes/cpt-affiliations.php';
require_once __DIR__ . '/includes/cpt-jobs.php';
require_once __DIR__ . '/includes/settings.php';
require_once get_template_directory() . '/includes/assets.php';

add_action('init', function () {
	//error_log("FNESL [functions.php] after_setup_theme – loading blocks…");
	register_block_type( get_stylesheet_directory() . '/includes/blocks/project-hero-v2' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/profile-card' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/project-cards' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/project-archive' );

	register_block_type( get_stylesheet_directory() . '/includes/blocks/home-hero' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/affiliations' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/news-cards' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/careers-section' );
	register_block_type( get_stylesheet_directory() . '/includes/blocks/responsive-grid' );
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
			'footer' => __('Footer Menu', 'fnesl'),
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


/**
 * REST endpoint: /wp-json/fnesl/v1/profile/{id}
 * Returns formatted profile data for the shared modal.
 */
add_action('rest_api_init', function () {
    register_rest_route('fnesl/v1', '/profile/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => function (WP_REST_Request $request) {
            $id   = (int) $request['id'];
            $post = get_post($id);

            if (!$post || $post->post_type !== 'profile') {
                return new WP_Error('not_found', 'Profile not found', ['status' => 404]);
            }

            $roles       = get_the_terms($id, 'Roles');
            $credentials = get_the_terms($id, 'credentials');

            return [
                'title'       => get_the_title($id),
                'role'        => ($roles && !is_wp_error($roles)) ? $roles[0]->name : '',
                'credentials' => ($credentials && !is_wp_error($credentials))
                    ? implode(', ', wp_list_pluck($credentials, 'name'))
                    : '',
                'image'       => get_the_post_thumbnail($id, 'large'),
                'content'     => wpautop($post->post_content),
            ];
        },
        'permission_callback' => '__return_true',
        'args'                => [
            'id' => ['validate_callback' => fn($v) => is_numeric($v)],
        ],
    ]);
});


/**
 * Inject single shared profile modal into the footer.
 */
add_action('wp_footer', function () {
    ?>

    <dialog id="profile-modal-shared" class="profile-modal rounded-xl p-0 max-w-xl w-[90vw] m-auto">
        <form method="dialog" class="fixed inset-0 bg-black/50"></form>


        <div class="bg-white p-6 rounded-lg relative z-10 flex flex-col gap-4">
            <div id="profile-modal-image" class="w-full aspect-[4/3] rounded-sm overflow-hidden border border-primary-100  outline-1 -outline-offset-1 outline-black/5 bg-primary-100"></div>

						<div>

						<div class="flex flex-wrap px-1 items-baseline gap-2 ">
            <h2 id="profile-modal-title" class="text-3xl font-medium text-pretty text-primary-900 line-clamp-2 leading-tight mb-1"></h2>

            <p id="profile-modal-credentials" class="text-primary-500 "></p>

</div>

            <p id="profile-modal-role" class="text-gray-500 "></p>

            <div id="profile-modal-content" class="prose mt-6 mb-6"></div>
						</div>

            <form method="dialog">
                <button class="absolute top-2 right-2 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Close</button>
            </form>
        </div>


    </dialog>


    <?php
});


/**
 * Pass REST base URL to frontend JS.
 */
add_action('wp_enqueue_scripts', function () {
    wp_localize_script('fnesl-theme-script', 'fneslData', [
        'restUrl' => esc_url_raw(rest_url('fnesl/v1/profile/')),
    ]);
}, 20);
