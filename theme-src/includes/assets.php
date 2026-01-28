<?php
/**
 * Enqueue theme assets from Vite manifests
 */

/**
 * Retrieves the value of a specified entry from the manifest.json file.
 *
 * Loads and caches the manifest.json file from the theme's stylesheet directory
 * on the first call, then returns the value associated with the given entry key.
 * If the manifest file does not exist or the entry is not found, returns null.
 *
 * @param string $entry The key of the manifest entry to retrieve.
 * @return mixed|null The value of the manifest entry, or null if not found.
 */
function fnesl_get_manifest_entry( $entry ) {
	static $manifest = null;

	if ( $manifest === null ) {
			$manifest_path = get_stylesheet_directory() . '/.vite/manifest.json';


			if ( file_exists( $manifest_path ) ) {
					$json = file_get_contents( $manifest_path );
					$manifest = json_decode( $json, true );

					if ( json_last_error() !== JSON_ERROR_NONE ) {
							//error_log( "[FNESL] Failed to parse manifest.json: " . json_last_error_msg() );
							$manifest = [];
					} else {
							//error_log( "[FNESL] Loaded manifest with " . count( $manifest ) . " entries from {$manifest_path}" );
					}
			} else {
					// error_log( "[FNESL] Manifest not found at {$manifest_path}" );
					$manifest = [];
			}
	}

	if ( ! isset( $manifest[ $entry ] ) ) {
			// error_log( "[FNESL] Manifest entry '{$entry}' not found" );
	} else {
			// error_log( "[FNESL] Found manifest entry '{$entry}': " . print_r( $manifest[ $entry ], true ) );
	}

	return $manifest[ $entry ] ?? null;
}



/**
 * Enqueues theme assets (CSS and JS) for the front-end.
 *
 * Retrieves the theme's asset manifest entry for 'js/theme.entry.js' and enqueues
 * associated CSS files and the main JavaScript file. Uses the stylesheet directory URI
 * as the base path for assets. Hooks into 'wp_enqueue_scripts'.
 *
 * @see fnesl_get_manifest_entry() Retrieves asset manifest entry.
 * @see wp_enqueue_style() Enqueues CSS files.
 * @see wp_enqueue_script() Enqueues JS files.
 */
function fnesl_theme_assets() {
	$dist_uri = get_stylesheet_directory_uri() . '/assets';

	$theme_entry = fnesl_get_manifest_entry( 'js/theme.entry.js' );
	if ( $theme_entry ) {
			// Enqueue CSS
			if ( ! empty( $theme_entry['css'] ) ) {
					foreach ( $theme_entry['css'] as $css_file ) {
							// error_log( "[FNESL] Enqueuing theme CSS: {$css_file}" );
							wp_enqueue_style(
									'fnesl-theme-style',
									$dist_uri . '/' . basename( $css_file ),
									[],
									null
							);
					}
			} else {
					// error_log( "[FNESL] No CSS found for js/theme.entry.js" );
			}

			// Enqueue JS
			if ( ! empty( $theme_entry['file'] ) ) {
					// error_log( "[FNESL] Enqueuing theme JS: {$theme_entry['file']}" );
					wp_enqueue_script(
							'fnesl-theme-script',
							$dist_uri . '/' . basename( $theme_entry['file'] ),
							[],
							null,
							true
					);
			} else {
					// error_log( "[FNESL] No JS file found for js/theme.entry.js" );
			}
	} else {
			// error_log( "[FNESL] Theme entry js/theme.entry.js not enqueued because manifest entry was missing" );
	}
}
add_action( 'wp_enqueue_scripts', 'fnesl_theme_assets' );

/**
 * Enqueues custom editor assets (CSS and JS) for the block editor.
 *
 * Retrieves the asset manifest entry for the editor, and enqueues associated CSS and JS files
 * for use in the WordPress block editor. Uses the theme's stylesheet directory URI as the base path.
 *
 * Hooks into 'enqueue_block_editor_assets'.
 *
 * @see fnesl_get_manifest_entry()
 */
function fnesl_editor_assets() {
    $dist_uri = get_stylesheet_directory_uri();

    // Editor assets
    $editor_entry = fnesl_get_manifest_entry('js/editor.entry.js');
    if ($editor_entry) {
        if (!empty($editor_entry['css'])) {
            foreach ($editor_entry['css'] as $css_file) {
                wp_enqueue_style('editor-style', $dist_uri . '/' . $css_file, [], null);
            }
        }
        wp_enqueue_script('editor-script', $dist_uri . '/' . $editor_entry['file'], [], null, true);
    }
}
add_action('enqueue_block_editor_assets', 'fnesl_editor_assets');


/**
 * Enqueues the BrowserSync client script for live reloading during development.
 *
 * This function checks if WordPress debugging is enabled and if the site is running
 * on the local development domain ('fnesl.ddev.site'). If both conditions are met,
 * it enqueues the BrowserSync client script to facilitate live reloading.
 *
 * The script is enqueued for both frontend and admin pages.
 *
 * @see https://browsersync.io/
 */
function fnesl_browsersync() {
	if ( defined('WP_DEBUG') && WP_DEBUG && str_contains($_SERVER['SERVER_NAME'], 'fnesl.ddev.site') ) {
			wp_enqueue_script(
					'browsersync',
					'https://fnesl.ddev.site:2519/browser-sync/browser-sync-client.js',
					[],
					null,
					true
			);
	}
}
add_action('wp_enqueue_scripts', 'fnesl_browsersync');
add_action( 'admin_enqueue_scripts', 'fnesl_browsersync' );



function fnesl_enqueue_manifest_css( $entry, $handle ) {
	$dist_uri = get_stylesheet_directory_uri() . '/assets';
	$manifest_entry = fnesl_get_manifest_entry( $entry );

	if ( $manifest_entry && ! empty( $manifest_entry['file'] ) ) {
		wp_enqueue_style(
			$handle,
			$dist_uri . '/' . basename( $manifest_entry['file'] ),
			[],
			null
		);
	}
}


add_action( 'enqueue_block_editor_assets', function() {
	fnesl_enqueue_manifest_css( 'css/banner.entry.css', 'fnesl-banner-editor' );
});


add_action('after_setup_theme', function() {
  add_theme_support('editor-styles');
  $banner_entry = fnesl_get_manifest_entry('css/banner.entry.css');
  if ($banner_entry) {
    add_editor_style('assets/' . basename($banner_entry['file']));
  }
});