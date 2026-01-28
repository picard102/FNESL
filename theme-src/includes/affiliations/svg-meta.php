<?php
/**
 * Affiliation meta:
 * - URL
 * - Full Color SVG (attachment ID)
 * - Single Color SVG (attachment ID)
 *
 * Two UIs:
 * - Block editor: Gutenberg sidebar panel (JS via Vite) + REST meta registration
 * - Classic editor: PHP meta box + media modal (inline JS) + save handler
 *
 * The PHP meta box + its admin JS are disabled when the block editor is active for affiliation,
 * so you don't get duplicate UIs.
 */

/** Meta keys */
const AFFIL_SVG_FULL_META = 'affiliation_svg_logo_id';
const AFFIL_SVG_1C_META   = 'affiliation_svg_logo_1c_id';
const AFFIL_URL_META      = 'affiliation_url';

/**
 * Helpers
 */
function fnesl_affiliation_uses_block_editor() {
	// WP 5.0+ provides this function. If missing, assume classic editor.
	if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
		return false;
	}
	return (bool) use_block_editor_for_post_type( 'affiliation' );
}

/**
 * 1) Register meta for REST (needed for Gutenberg sidebar panel)
 * Safe to leave enabled always (doesn't hurt classic editor).
 */
add_action('init', function () {

	register_post_meta('affiliation', AFFIL_URL_META, [
		'type'              => 'string',
		'single'            => true,
		'sanitize_callback' => 'esc_url_raw',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can('edit_posts');
		},
	]);

	register_post_meta('affiliation', AFFIL_SVG_FULL_META, [
		'type'              => 'integer',
		'single'            => true,
		'sanitize_callback' => 'absint',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can('edit_posts');
		},
	]);

	register_post_meta('affiliation', AFFIL_SVG_1C_META, [
		'type'              => 'integer',
		'single'            => true,
		'sanitize_callback' => 'absint',
		'show_in_rest'      => true,
		'auth_callback'     => function () {
			return current_user_can('edit_posts');
		},
	]);
});

/**
 * 2) Gutenberg sidebar panel script (only if block editor is active)
 * Uses your existing manifest helper.
 */
add_action('enqueue_block_editor_assets', function () {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;

	if ( ! $screen || $screen->post_type !== 'affiliation' ) {
		return;
	}

	// If affiliation is NOT using the block editor, don't load the panel.
	if ( ! fnesl_affiliation_uses_block_editor() ) {
		return;
	}

	wp_enqueue_media();

	$dist_uri = get_stylesheet_directory_uri();

	$entry = fnesl_get_manifest_entry( 'js/affiliation.entry.js' );
	if ( ! $entry || empty( $entry['file'] ) ) {
		return;
	}

	if ( ! empty( $entry['css'] ) ) {
		foreach ( $entry['css'] as $css_file ) {
			wp_enqueue_style(
				'affiliation-editor-panel-style',
				$dist_uri . '/' . ltrim( $css_file, '/' ),
				[],
				null
			);
		}
	}

	wp_enqueue_script(
		'affiliation-editor-panel',
		$dist_uri . '/' . ltrim( $entry['file'], '/' ),
		[ 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data', 'wp-api-fetch' ],
		null,
		true
	);
});

/**
 * 3) Classic editor PHP meta box UI (only if NOT using block editor)
 */
add_action('add_meta_boxes', function () {
	if ( fnesl_affiliation_uses_block_editor() ) {
		return;
	}

	add_meta_box(
		'affiliation_svg_logo_metabox',
		'Affiliation Details',
		'affiliation_render_details_metabox',
		'affiliation',
		'side',
		'default'
	);
});

/**
 * Meta box UI
 */
function affiliation_render_details_metabox($post) {
	wp_nonce_field('affiliation_details_save', 'affiliation_details_nonce');

	static $css_printed = false;
	if (!$css_printed) {
		$css_printed = true;
		echo '<style>
			.affil-field { margin: 0 0 14px; }
			.affil-label { font-weight: 600; margin: 0 0 6px; }
			.affil-help  { color:#666; margin: 6px 0 0; font-size: 12px; line-height: 1.4; }

			.affil-url-input { width: 100%; box-sizing: border-box; }

			.affil-svg-preview {
				position: relative;
				height: 100px;
				padding: 5px;
				border: 1px solid #ccd0d4;
				background-color: #fefefe;
				background-image:
					linear-gradient(45deg, #e5e5e5 25%, transparent 25%),
					linear-gradient(-45deg, #e5e5e5 25%, transparent 25%),
					linear-gradient(45deg, transparent 75%, #e5e5e5 75%),
					linear-gradient(-45deg, transparent 75%, #e5e5e5 75%);
				background-size: 16px 16px;
				background-position: 0 0, 0 8px, 8px -8px, -8px 0;
				display: flex;
				align-items: center;
				justify-content: center;
				overflow: hidden;
			}

			.affil-svg-preview img {
				max-width: 100%;
				max-height: 100%;
				width: auto;
				height: auto;
				display: block;
			}

			.affil-url { margin: 6px 0 0; word-break: break-all; font-size: 11px; color: #555; }

			.affil-buttons {
				margin: 8px 0 0;
				display: flex;
				gap: 6px;
				flex-wrap: wrap;
			}
		</style>';
	}

	$full_id   = (int) get_post_meta($post->ID, AFFIL_SVG_FULL_META, true);
	$full_url  = $full_id ? wp_get_attachment_url($full_id) : '';

	$one_id    = (int) get_post_meta($post->ID, AFFIL_SVG_1C_META, true);
	$one_url   = $one_id ? wp_get_attachment_url($one_id) : '';

	$affil_url = (string) get_post_meta($post->ID, AFFIL_URL_META, true);
	?>

	<div class="affil-field">
		<p class="affil-label">Affiliation URL</p>
		<input
			type="url"
			name="affiliation_url"
			class="affil-url-input"
			value="<?php echo esc_attr($affil_url); ?>"
			placeholder="https://example.com"
		/>
		<p class="affil-help">Optional. Used to link the logo/name on the front end.</p>
	</div>

	<hr style="margin: 12px 0;" />

	<div class="affil-field">
		<p class="affil-label">Full Color SVG</p>

		<input type="hidden" id="affil_svg_full_id" name="affil_svg_full_id" value="<?php echo esc_attr($full_id); ?>" />

		<div id="affil_svg_full_preview" style="margin: 10px 0;">
			<?php if ($full_url) : ?>
				<div class="affil-svg-preview">
					<img src="<?php echo esc_url($full_url); ?>" alt="" />
				</div>
				<p class="affil-url"><?php echo esc_html($full_url); ?></p>
			<?php else : ?>
				<p style="color:#666; margin:0;">No SVG selected.</p>
			<?php endif; ?>
		</div>

		<div class="affil-buttons">
			<button type="button" class="button" id="affil_svg_full_select"><?php echo $full_url ? 'Change SVG' : 'Select SVG'; ?></button>
			<button type="button" class="button" id="affil_svg_full_remove" <?php disabled(!$full_id); ?>>Remove</button>
		</div>

		<p class="affil-help">Primary logo SVG.</p>
	</div>

	<hr style="margin: 12px 0;" />

	<div class="affil-field">
		<p class="affil-label">Single Color SVG (optional)</p>

		<input type="hidden" id="affil_svg_1c_id" name="affil_svg_1c_id" value="<?php echo esc_attr($one_id); ?>" />

		<div id="affil_svg_1c_preview" style="margin: 10px 0;">
			<?php if ($one_url) : ?>
				<div class="affil-svg-preview">
					<img src="<?php echo esc_url($one_url); ?>" alt="" />
				</div>
				<p class="affil-url"><?php echo esc_html($one_url); ?></p>
			<?php else : ?>
				<p style="color:#666; margin:0;">No SVG selected.</p>
			<?php endif; ?>
		</div>

		<div class="affil-buttons">
			<button type="button" class="button" id="affil_svg_1c_select"><?php echo $one_url ? 'Change SVG' : 'Select SVG'; ?></button>
			<button type="button" class="button" id="affil_svg_1c_remove" <?php disabled(!$one_id); ?>>Remove</button>
		</div>

		<p class="affil-help">If available, upload a version designed to be used in one color.</p>
	</div>

	<?php
}

/**
 * Save handler (classic editor form post)
 * This will effectively be a no-op in Gutenberg because those fields aren't posted.
 * Keeping it active is fine and avoids edge cases.
 */
add_action('save_post_affiliation', function ($post_id) {
	if (
		!isset($_POST['affiliation_details_nonce']) ||
		!wp_verify_nonce($_POST['affiliation_details_nonce'], 'affiliation_details_save')
	) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	$full_id = isset($_POST['affil_svg_full_id']) ? (int) $_POST['affil_svg_full_id'] : 0;
	$one_id  = isset($_POST['affil_svg_1c_id']) ? (int) $_POST['affil_svg_1c_id'] : 0;

	if ($full_id > 0) update_post_meta($post_id, AFFIL_SVG_FULL_META, $full_id);
	else delete_post_meta($post_id, AFFIL_SVG_FULL_META);

	if ($one_id > 0) update_post_meta($post_id, AFFIL_SVG_1C_META, $one_id);
	else delete_post_meta($post_id, AFFIL_SVG_1C_META);

	if (isset($_POST['affiliation_url']) && trim((string) $_POST['affiliation_url']) !== '') {
		$url = esc_url_raw((string) $_POST['affiliation_url']);
		update_post_meta($post_id, AFFIL_URL_META, $url);
	} else {
		delete_post_meta($post_id, AFFIL_URL_META);
	}
});

/**
 * Classic editor: media modal pickers (inline JS)
 * Disabled when block editor is active to avoid duplicate UI/handlers.
 */
add_action('admin_enqueue_scripts', function ($hook) {
	if ( fnesl_affiliation_uses_block_editor() ) {
		return;
	}

	if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;

	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || $screen->post_type !== 'affiliation') return;

	wp_enqueue_media();
	wp_enqueue_script('jquery');

	wp_add_inline_script('jquery', affiliation_admin_media_js());
});

function affiliation_admin_media_js() {
	return <<<JS
jQuery(function($) {
	function bindSvgPicker(opts) {
		var frame;

		function setPreview(attachment) {
			var url = attachment.url || '';
			$(opts.input).val(attachment.id || '');
			$(opts.preview).html(
				'<div class="affil-svg-preview">' +
					'<img src="' + url + '" alt="" />' +
				'</div>' +
				'<p class="affil-url">' + url + '</p>'
			);
			$(opts.remove).prop('disabled', false);
			$(opts.select).text('Change SVG');
		}

		function clearPreview() {
			$(opts.input).val('');
			$(opts.preview).html('<p style="color:#666; margin:0;">No SVG selected.</p>');
			$(opts.remove).prop('disabled', true);
			$(opts.select).text('Select SVG');
		}

		$(opts.select).on('click', function(e) {
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: opts.title || 'Select an SVG',
				button: { text: opts.buttonText || 'Use this SVG' },
				multiple: false,
				library: { type: 'image/svg+xml' }
			});

			frame.on('select', function() {
				var attachment = frame.state().get('selection').first().toJSON();
				setPreview(attachment);
			});

			frame.open();
		});

		$(opts.remove).on('click', function(e) {
			e.preventDefault();
			clearPreview();
		});
	}

	bindSvgPicker({
		input:  '#affil_svg_full_id',
		preview:'#affil_svg_full_preview',
		select: '#affil_svg_full_select',
		remove: '#affil_svg_full_remove',
		title:  'Select Full Color SVG',
		buttonText: 'Use this SVG'
	});

	bindSvgPicker({
		input:  '#affil_svg_1c_id',
		preview:'#affil_svg_1c_preview',
		select: '#affil_svg_1c_select',
		remove: '#affil_svg_1c_remove',
		title:  'Select Single Color SVG',
		buttonText: 'Use this SVG'
	});
});
JS;
}