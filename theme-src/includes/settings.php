<?php
/**
 * Footer Settings (no plugin)
 * - Appearance > Footer Settings
 * - Contact rows (repeatable): label + textarea
 * - Social links (repeatable): network (select) + URL
 * - Stored in one option: theme_footer_settings
 *
 * Put this file in your theme (ex: /includes/footer-settings.php)
 * and require it from functions.php:
 * require_once get_template_directory() . '/includes/footer-settings.php';
 */

/**
 * Allowed social networks (single source of truth).
 * Keys become your icon mapping keys.
 */
function theme_footer_allowed_social_networks(): array {
	return array(
		'instagram' => 'Instagram',
		'facebook'  => 'Facebook',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
		'x'         => 'X',
		'threads'   => 'Threads',
		'github'    => 'GitHub',
	);
}

/**
 * Admin menu page
 */
add_action( 'admin_menu', 'theme_footer_settings_menu' );
function theme_footer_settings_menu() {
	add_theme_page(
		__( 'Footer Settings', 'your-textdomain' ),
		__( 'Footer Settings', 'your-textdomain' ),
		'manage_options',
		'theme-footer-settings',
		'theme_footer_settings_page'
	);
}

/**
 * Register option
 */
add_action( 'admin_init', 'theme_footer_settings_register' );
function theme_footer_settings_register() {
	register_setting(
		'theme_footer_settings_group',
		'theme_footer_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'theme_footer_settings_sanitize',
			'default'           => array(
				'contact_rows' => array(),
				'social_links' => array(),
			),
		)
	);
}

/**
 * Sanitize + normalize saved settings
 */
function theme_footer_settings_sanitize( $input ) {
	$output = array(
		'contact_rows' => array(),
		'social_links' => array(),
	);

	// Contact rows: label + textarea
	if ( isset( $input['contact_rows'] ) && is_array( $input['contact_rows'] ) ) {
		foreach ( $input['contact_rows'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$label = isset( $row['label'] ) ? sanitize_text_field( (string) $row['label'] ) : '';
			$value = isset( $row['value'] ) ? sanitize_textarea_field( (string) $row['value'] ) : '';

			if ( $label === '' && $value === '' ) {
				continue;
			}

			$output['contact_rows'][] = array(
				'label' => $label,
				'value' => $value,
			);
		}
	}

	// Social links: network (select) + url
	$allowed_networks = array_keys( theme_footer_allowed_social_networks() );

	if ( isset( $input['social_links'] ) && is_array( $input['social_links'] ) ) {
		foreach ( $input['social_links'] as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$network = isset( $row['network'] ) ? sanitize_key( (string) $row['network'] ) : '';
			$url     = isset( $row['url'] ) ? esc_url_raw( (string) $row['url'] ) : '';

			if ( $url === '' ) {
				continue;
			}

			if ( ! in_array( $network, $allowed_networks, true ) ) {
				$network = '';
			}

			$output['social_links'][] = array(
				'network' => $network,
				'url'     => $url,
			);
		}
	}

	return $output;
}

/**
 * Admin page HTML + minimal JS for repeaters
 */
function theme_footer_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$opt = get_option(
		'theme_footer_settings',
		array(
			'contact_rows' => array(),
			'social_links' => array(),
		)
	);

	$contact_rows = isset( $opt['contact_rows'] ) && is_array( $opt['contact_rows'] ) ? $opt['contact_rows'] : array();
	$social_links = isset( $opt['social_links'] ) && is_array( $opt['social_links'] ) ? $opt['social_links'] : array();

	// Keep one empty row visible
	if ( empty( $contact_rows ) ) {
		$contact_rows = array( array( 'label' => '', 'value' => '' ) );
	}
	if ( empty( $social_links ) ) {
		$social_links = array( array( 'network' => '', 'url' => '' ) );
	}

	$networks = theme_footer_allowed_social_networks();
	?>
	<div class="wrap">
		<h1><?php echo esc_html__( 'Footer Settings', 'your-textdomain' ); ?></h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'theme_footer_settings_group' ); ?>

			<hr>

			<h2><?php echo esc_html__( 'Contact Rows', 'your-textdomain' ); ?></h2>
			<p class="description">
				<?php echo esc_html__( 'Repeatable label/value pairs for the footer (phone, address, hours, etc.).', 'your-textdomain' ); ?>
			</p>

			<table class="widefat striped" style="max-width: 980px;">
				<thead>
					<tr>
						<th style="width: 240px;"><?php echo esc_html__( 'Label', 'your-textdomain' ); ?></th>
						<th><?php echo esc_html__( 'Value', 'your-textdomain' ); ?></th>
						<th style="width: 80px;"><?php echo esc_html__( 'Remove', 'your-textdomain' ); ?></th>
					</tr>
				</thead>
				<tbody id="theme-footer-contact-rows">
					<?php foreach ( $contact_rows as $i => $row ) : ?>
						<tr class="theme-footer-contact-row">
							<td>
								<input
									type="text"
									class="regular-text"
									name="theme_footer_settings[contact_rows][<?php echo esc_attr( $i ); ?>][label]"
									value="<?php echo esc_attr( $row['label'] ?? '' ); ?>"
									placeholder="<?php echo esc_attr__( 'Phone', 'your-textdomain' ); ?>"
								/>
							</td>
							<td>
								<textarea
									class="large-text"
									rows="2"
									name="theme_footer_settings[contact_rows][<?php echo esc_attr( $i ); ?>][value]"
									placeholder="<?php echo esc_attr__( '416-555-1234 or 123 Main St, Toronto…', 'your-textdomain' ); ?>"
								><?php echo esc_textarea( $row['value'] ?? '' ); ?></textarea>
							</td>
							<td>
								<button type="button" class="button theme-footer-remove-contact">
									<?php echo esc_html__( 'Remove', 'your-textdomain' ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p style="margin-top: 12px;">
				<button type="button" class="button" id="theme-footer-add-contact">
					<?php echo esc_html__( 'Add Contact Row', 'your-textdomain' ); ?>
				</button>
			</p>

			<hr>

			<h2><?php echo esc_html__( 'Social Links', 'your-textdomain' ); ?></h2>
			<p class="description">
				<?php echo esc_html__( 'Repeatable social links with a controlled list of networks.', 'your-textdomain' ); ?>
			</p>

			<table class="widefat striped" style="max-width: 980px;">
				<thead>
					<tr>
						<th style="width: 260px;"><?php echo esc_html__( 'Network', 'your-textdomain' ); ?></th>
						<th><?php echo esc_html__( 'URL', 'your-textdomain' ); ?></th>
						<th style="width: 80px;"><?php echo esc_html__( 'Remove', 'your-textdomain' ); ?></th>
					</tr>
				</thead>
				<tbody id="theme-footer-social-rows">
					<?php foreach ( $social_links as $i => $row ) :
						$current_network = isset( $row['network'] ) ? (string) $row['network'] : '';
						$current_url     = isset( $row['url'] ) ? (string) $row['url'] : '';
						?>
						<tr class="theme-footer-social-row">
							<td>
								<select name="theme_footer_settings[social_links][<?php echo esc_attr( $i ); ?>][network]">
									<option value=""><?php echo esc_html__( 'Select…', 'your-textdomain' ); ?></option>
									<?php foreach ( $networks as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current_network, $key ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
							<td>
								<input
									type="url"
									class="large-text"
									name="theme_footer_settings[social_links][<?php echo esc_attr( $i ); ?>][url]"
									value="<?php echo esc_attr( $current_url ); ?>"
									placeholder="https://"
								/>
							</td>
							<td>
								<button type="button" class="button theme-footer-remove-social">
									<?php echo esc_html__( 'Remove', 'your-textdomain' ); ?>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p style="margin-top: 12px;">
				<button type="button" class="button" id="theme-footer-add-social">
					<?php echo esc_html__( 'Add Social Link', 'your-textdomain' ); ?>
				</button>
			</p>

			<?php submit_button(); ?>
		</form>
	</div>

	<script>
	(function() {
		const contactTbody = document.getElementById('theme-footer-contact-rows');
		const socialTbody  = document.getElementById('theme-footer-social-rows');
		const addContactBtn = document.getElementById('theme-footer-add-contact');
		const addSocialBtn  = document.getElementById('theme-footer-add-social');

		const networkOptionsHtml = <?php echo wp_json_encode(
			'<option value="">Select…</option>' .
			implode(
				'',
				array_map(
					function( $key, $label ) {
						return '<option value="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</option>';
					},
					array_keys( $networks ),
					array_values( $networks )
				)
			)
		); ?>;

		function countRows(tbody, className) {
			return tbody.querySelectorAll('.' + className).length;
		}

		function contactRowTemplate(i) {
			return `
				<tr class="theme-footer-contact-row">
					<td>
						<input type="text" class="regular-text"
							name="theme_footer_settings[contact_rows][${i}][label]"
							placeholder="Phone">
					</td>
					<td>
						<textarea class="large-text" rows="2"
							name="theme_footer_settings[contact_rows][${i}][value]"
							placeholder="416-555-1234 or 123 Main St, Toronto…"></textarea>
					</td>
					<td>
						<button type="button" class="button theme-footer-remove-contact">Remove</button>
					</td>
				</tr>
			`;
		}

		function socialRowTemplate(i) {
			return `
				<tr class="theme-footer-social-row">
					<td>
						<select name="theme_footer_settings[social_links][${i}][network]">
							${networkOptionsHtml}
						</select>
					</td>
					<td>
						<input type="url" class="large-text"
							name="theme_footer_settings[social_links][${i}][url]"
							placeholder="https://">
					</td>
					<td>
						<button type="button" class="button theme-footer-remove-social">Remove</button>
					</td>
				</tr>
			`;
		}

		addContactBtn.addEventListener('click', function() {
			const i = countRows(contactTbody, 'theme-footer-contact-row');
			contactTbody.insertAdjacentHTML('beforeend', contactRowTemplate(i));
		});

		addSocialBtn.addEventListener('click', function() {
			const i = countRows(socialTbody, 'theme-footer-social-row');
			socialTbody.insertAdjacentHTML('beforeend', socialRowTemplate(i));
		});

		contactTbody.addEventListener('click', function(e) {
			const btn = e.target.closest('.theme-footer-remove-contact');
			if (!btn) return;

			const row = btn.closest('.theme-footer-contact-row');
			if (!row) return;

			const rows = contactTbody.querySelectorAll('.theme-footer-contact-row');
			if (rows.length <= 1) {
				row.querySelector('input').value = '';
				row.querySelector('textarea').value = '';
				return;
			}
			row.remove();
		});

		socialTbody.addEventListener('click', function(e) {
			const btn = e.target.closest('.theme-footer-remove-social');
			if (!btn) return;

			const row = btn.closest('.theme-footer-social-row');
			if (!row) return;

			const rows = socialTbody.querySelectorAll('.theme-footer-social-row');
			if (rows.length <= 1) {
				const sel = row.querySelector('select');
				const url = row.querySelector('input[type="url"]');
				if (sel) sel.value = '';
				if (url) url.value = '';
				return;
			}
			row.remove();
		});
	})();
	</script>
	<?php
}


