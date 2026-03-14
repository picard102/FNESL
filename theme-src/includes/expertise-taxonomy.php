<?php
/**
 * Expertise taxonomy + SVG icon term meta
 *
 * Notes:
 * - Uses FNESL's existing Vite enqueue system:
 *   - Manifest: get_stylesheet_directory() . '/.vite/manifest.json'
 *   - Assets:   get_stylesheet_directory_uri() . '/assets'
 * - Assumes your Vite input builds an entry that appears in the manifest under:
 *   'js/admin-tax-icons.entry.js'
 */

const FNESL_EXPERTISE_TAX  = 'expertise';
const FNESL_TERM_ICON_KEY  = 'fnesl_term_icon_svg_id';

/* -----------------------------------------------------------
 * Taxonomy registration
 * --------------------------------------------------------- */
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
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => ['slug' => 'expertise'],
		'show_in_rest'      => true,
	];

	register_taxonomy(FNESL_EXPERTISE_TAX, ['project'], $args);
});

/* -----------------------------------------------------------
 * Admin enqueue (uses existing FNESL Vite manifest system)
 * --------------------------------------------------------- */
add_action('admin_enqueue_scripts', function () {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen) return;

	// Runs on:
	// - /wp-admin/edit-tags.php (list + add)
	// - /wp-admin/term.php (edit)
	$is_terms_ui = in_array($screen->base, ['edit-tags', 'term'], true);

	if (!$is_terms_ui || $screen->taxonomy !== FNESL_EXPERTISE_TAX) {
		return;
	}

	// needed for wp.media
	wp_enqueue_media();

	$dist_uri = get_stylesheet_directory_uri() . '/assets';

	$entry = function_exists('fnesl_get_manifest_entry')
		? fnesl_get_manifest_entry('js/admin-tax-icons.entry.js')
		: null;

	if ($entry && !empty($entry['file'])) {
		wp_enqueue_script(
			'fnesl-admin-tax-icons',
			$dist_uri . '/' . basename($entry['file']),
			[],
			null,
			true
		);
	}
});

/* -----------------------------------------------------------
 * Term fields (add + edit)
 * --------------------------------------------------------- */
add_action(FNESL_EXPERTISE_TAX . '_add_form_fields', function () {
	?>
	<div class="form-field term-icon-wrap">
		<label for="fnesl-term-icon"><?php esc_html_e('Icon (SVG)', 'fnesl'); ?></label>
		<input type="hidden" id="fnesl-term-icon" name="<?php echo esc_attr(FNESL_TERM_ICON_KEY); ?>" value="" />

		<div style="margin:8px 0;">
			<img id="fnesl-term-icon-preview" src="" alt="" style="max-width:64px; max-height:64px; display:none;" />
		</div>

		<button type="button" class="button" id="fnesl-term-icon-upload"><?php esc_html_e('Choose SVG', 'fnesl'); ?></button>
		<button type="button" class="button" id="fnesl-term-icon-remove" style="display:none;"><?php esc_html_e('Remove', 'fnesl'); ?></button>

		<p class="description"><?php esc_html_e('Upload/select an SVG to use as this term icon.', 'fnesl'); ?></p>
		<?php wp_nonce_field('fnesl_term_icon_save', 'fnesl_term_icon_nonce'); ?>
	</div>
	<?php
});

add_action(FNESL_EXPERTISE_TAX . '_edit_form_fields', function ($term) {
	$attachment_id = (int) get_term_meta($term->term_id, FNESL_TERM_ICON_KEY, true);
	$url = $attachment_id ? wp_get_attachment_url($attachment_id) : '';
	?>
	<tr class="form-field term-icon-wrap">
		<th scope="row">
			<label for="fnesl-term-icon"><?php esc_html_e('Icon (SVG)', 'fnesl'); ?></label>
		</th>
		<td>
			<input type="hidden" id="fnesl-term-icon" name="<?php echo esc_attr(FNESL_TERM_ICON_KEY); ?>" value="<?php echo esc_attr($attachment_id); ?>" />

			<div style="margin:8px 0;">
				<img
					id="fnesl-term-icon-preview"
					src="<?php echo esc_url($url); ?>"
					alt=""
					style="max-width:64px; max-height:64px; <?php echo $url ? '' : 'display:none;'; ?>"
				/>
			</div>

			<button type="button" class="button" id="fnesl-term-icon-upload"><?php esc_html_e('Choose SVG', 'fnesl'); ?></button>
			<button type="button" class="button" id="fnesl-term-icon-remove" <?php echo $url ? '' : 'style="display:none;"'; ?>>
				<?php esc_html_e('Remove', 'fnesl'); ?>
			</button>

			<p class="description"><?php esc_html_e('Upload/select an SVG to use as this term icon.', 'fnesl'); ?></p>
			<?php wp_nonce_field('fnesl_term_icon_save', 'fnesl_term_icon_nonce'); ?>
		</td>
	</tr>
	<?php
}, 10, 1);

/* -----------------------------------------------------------
 * Save term meta
 * --------------------------------------------------------- */
add_action('created_' . FNESL_EXPERTISE_TAX, 'fnesl_save_expertise_term_icon');
add_action('edited_'  . FNESL_EXPERTISE_TAX, 'fnesl_save_expertise_term_icon');

function fnesl_save_expertise_term_icon($term_id) {
	if (!current_user_can('manage_categories')) return;

	if (
		empty($_POST['fnesl_term_icon_nonce']) ||
		!wp_verify_nonce($_POST['fnesl_term_icon_nonce'], 'fnesl_term_icon_save')
	) {
		return;
	}

	$attachment_id = isset($_POST[FNESL_TERM_ICON_KEY]) ? (int) $_POST[FNESL_TERM_ICON_KEY] : 0;

	if ($attachment_id) {
		$mime = get_post_mime_type($attachment_id);
		if ($mime !== 'image/svg+xml') {
			$attachment_id = 0;
		}
	}

	if ($attachment_id) {
		update_term_meta($term_id, FNESL_TERM_ICON_KEY, $attachment_id);
	} else {
		delete_term_meta($term_id, FNESL_TERM_ICON_KEY);
	}
}


/**
 * Find an SVG icon attachment for a term:
 * - First check the term itself.
 * - If missing and term has parent, check parent (walk up until found or root).
 * - Returns array [ 'term' => WP_Term, 'icon_id' => int, 'url' => string ] or null.
 */
function fnesl_get_expertise_icon_for_term( $term, $meta_key = 'fnesl_term_icon_svg_id' ) {
	if ( ! $term || is_wp_error( $term ) ) return null;

	$seen = [];

	while ( $term && ! is_wp_error( $term ) ) {
		// prevent loops
		if ( isset( $seen[ $term->term_id ] ) ) break;
		$seen[ $term->term_id ] = true;

		$icon_id = (int) get_term_meta( $term->term_id, $meta_key, true );
		if ( $icon_id ) {
			$url = wp_get_attachment_url( $icon_id );
			if ( $url ) {
				return [
					'term'    => $term,
					'icon_id' => $icon_id,
					'url'     => $url,
				];
			}
		}

		$parent_id = (int) $term->parent;
		if ( $parent_id > 0 ) {
			$term = get_term( $parent_id, 'expertise' );
			continue;
		}

		break;
	}

	return null;
}



/**
 * Inline an expertise term SVG icon with parent fallback.
 *
 * - Looks for term meta 'fnesl_term_icon_svg_id' on the term.
 * - If missing and term has parent(s), checks parent chain.
 * - Returns '' if no SVG icon found.
 *
 * Usage:
 * echo fnesl_inline_expertise_term_svg( $term, 'h-6 w-6 fill-current' );
 */
function fnesl_inline_expertise_term_svg( $term, string $svg_class = '' ): string {
	if ( is_numeric( $term ) ) {
		$term = get_term( (int) $term, 'expertise' );
	}

	if ( ! $term || is_wp_error( $term ) ) return '';

	$seen = [];

	while ( $term && ! is_wp_error( $term ) ) {
		if ( isset( $seen[ $term->term_id ] ) ) break;
		$seen[ $term->term_id ] = true;

		$icon_id = (int) get_term_meta( $term->term_id, 'fnesl_term_icon_svg_id', true );

		if ( $icon_id ) {
			$mime = get_post_mime_type( $icon_id );
			if ( $mime !== 'image/svg+xml' ) return '';

			$path = get_attached_file( $icon_id );
			if ( ! $path || ! file_exists( $path ) ) return '';

			$svg = file_get_contents( $path );
			if ( ! $svg ) return '';

			// Remove <style> blocks
			$svg = preg_replace( '#<style\b[^>]*>(.*?)</style>#is', '', $svg );

			// Remove inline style attributes
			$svg = preg_replace( '/\sstyle=("|\')(.*?)\1/i', '', $svg );

			// Remove solid fill attributes (keep none, currentColor, gradients)
			$svg = preg_replace( '/\sfill=("|\')(?!none|currentColor|url\()(.*?)\1/i', '', $svg );

			// Remove solid stroke attributes (same logic)
			$svg = preg_replace( '/\sstroke=("|\')(?!none|currentColor|url\()(.*?)\1/i', '', $svg );

			// Basic hardening
			$svg = preg_replace( '#<script\b[^>]*>(.*?)</script>#is', '', $svg );
			$svg = preg_replace( '/\son\w+="[^"]*"/i', '', $svg );
			$svg = preg_replace( "/\son\w+='[^']*'/i", '', $svg );

			// Inject/merge class into the <svg> tag.
			if ( $svg_class ) {
				if ( preg_match( '/<svg\b[^>]*\bclass=([\'"])(.*?)\1/i', $svg ) ) {
					$svg = preg_replace_callback(
						'/(<svg\b[^>]*\bclass=)([\'"])(.*?)(\2)/i',
						function ( $m ) use ( $svg_class ) {
							$existing = trim( $m[3] );
							$merged   = trim( $existing . ' ' . $svg_class );
							return $m[1] . $m[2] . esc_attr( $merged ) . $m[4];
						},
						$svg,
						1
					);
				} else {
					$svg = preg_replace(
						'/<svg\b/i',
						'<svg class="' . esc_attr( $svg_class ) . '"',
						$svg,
						1
					);
				}
			}

			// Accessibility: make it decorative (you can remove if you want a title)
			$svg = preg_replace('/<svg\b/i', '<svg aria-hidden="true" focusable="false"', $svg, 1);

			return $svg;
		}

		$parent_id = (int) $term->parent;
		if ( $parent_id > 0 ) {
			$term = get_term( $parent_id, 'expertise' );
			continue;
		}

		break;
	}

	return '';
}



add_filter('manage_edit-expertise_columns', function ($columns) {
	// Insert icon column after the checkbox, before Name.
	$new = [];

	if (isset($columns['cb'])) {
		$new['cb'] = $columns['cb'];
	}

	$new['fnesl_icon'] = __('Icon', 'fnesl');

	// Keep the rest (including 'name')
	foreach ($columns as $key => $label) {
		if ($key === 'cb') continue;
		$new[$key] = $label;
	}

	return $new;
});


add_filter('manage_expertise_custom_column', function ($out, $column_name, $term_id) {
	if ($column_name !== 'fnesl_icon') return $out;

	$term = get_term((int) $term_id, 'expertise');
	if (!$term || is_wp_error($term)) return '';

	// Inline SVG with parent fallback. Returns '' if none found.
	$svg = fnesl_inline_expertise_term_svg($term, 'h-5 w-5 fill-current text-gray-700');

	if (!$svg) {
		// No icon anywhere in chain: show nothing (or a dash)
		return '';
	}

	// Wrap in a span to ensure layout is stable in admin table
	return '<span class="fnesl-term-icon" style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;">'
		. $svg .
	'</span>';
}, 10, 3);


add_action('admin_head-edit-tags.php', function () {
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || $screen->taxonomy !== 'expertise') return;
	?>
	<style>
		.column-fnesl_icon { width: 48px; }
		.column-fnesl_icon svg { width: 20px; height: 20px; display:block; }
	</style>
	<?php
});