<?php
/**
 * Register "Jobs" Custom Post Type and job meta.
 */

function fnesl_sanitize_job_date( $value ) {
	$value = sanitize_text_field( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	$date = DateTime::createFromFormat( 'Y-m-d', $value );
	return $date && $date->format( 'Y-m-d' ) === $value ? $value : '';
}

function fnesl_sanitize_job_flag( $value ) {
	$value = sanitize_text_field( (string) $value );
	return in_array( $value, [ 'yes', 'no' ], true ) ? $value : '';
}

add_action('init', function () {
	$labels = [
		'name'               => __('Jobs', 'fnesl'),
		'singular_name'      => __('Job', 'fnesl'),
		'menu_name'          => __('Jobs', 'fnesl'),
		'name_admin_bar'     => __('Job', 'fnesl'),
		'add_new'            => __('Add New', 'fnesl'),
		'add_new_item'       => __('Add New Job', 'fnesl'),
		'new_item'           => __('New Job', 'fnesl'),
		'edit_item'          => __('Edit Job', 'fnesl'),
		'view_item'          => __('View Job', 'fnesl'),
		'all_items'          => __('All Jobs', 'fnesl'),
		'search_items'       => __('Search Jobs', 'fnesl'),
		'parent_item_colon'  => __('Parent Jobs:', 'fnesl'),
		'not_found'          => __('No jobs found.', 'fnesl'),
		'not_found_in_trash' => __('No jobs found in Trash.', 'fnesl'),
	];

	$args = [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => 'careers'],
		'capability_type'    => 'post',
		'has_archive'        => 'careers',
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-businessperson',
		'supports'           => ['title', 'editor', 'excerpt', 'page-attributes'],
		'show_in_rest'       => true,
		'show_in_nav_menus'  => true,
		'template'           => [
			[
				'core/paragraph',
				[
					'content' => 'Add a short overview of the role, responsibilities, and what the candidate will help deliver.',
				],
			],
		],
	];

	register_post_type('job', $args);
});

add_action('init', function () {
	$meta_fields = [
		'job_type' => [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		],
		'job_location' => [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		],
		'application_email' => [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_email',
		],
		'closing_date' => [
			'type'              => 'string',
			'sanitize_callback' => 'fnesl_sanitize_job_date',
		],
		'expiry_date' => [
			'type'              => 'string',
			'sanitize_callback' => 'fnesl_sanitize_job_date',
		],
		'salary_text' => [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		],
		'is_vacancy' => [
			'type'              => 'string',
			'sanitize_callback' => 'fnesl_sanitize_job_flag',
		],
		'uses_ai_screening' => [
			'type'              => 'string',
			'sanitize_callback' => 'fnesl_sanitize_job_flag',
		],
		'accommodation_note' => [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		],
	];

	foreach ( $meta_fields as $key => $config ) {
		register_post_meta('job', $key, [
			'type'              => $config['type'],
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => $config['sanitize_callback'],
			'auth_callback'     => function () {
				return current_user_can('edit_posts');
			},
		]);
	}
});

add_action('add_meta_boxes', function () {
	add_meta_box(
		'fnesl-job-details',
		__('Job Details', 'fnesl'),
		function ( WP_Post $post ) {
			wp_nonce_field('fnesl_save_job_details', 'fnesl_job_details_nonce');

			$job_type           = get_post_meta($post->ID, 'job_type', true);
			$job_location       = get_post_meta($post->ID, 'job_location', true);
			$application_email  = get_post_meta($post->ID, 'application_email', true);
			$closing_date       = get_post_meta($post->ID, 'closing_date', true);
			$expiry_date        = get_post_meta($post->ID, 'expiry_date', true);
			$salary_text        = get_post_meta($post->ID, 'salary_text', true);
			$is_vacancy         = get_post_meta($post->ID, 'is_vacancy', true);
			$uses_ai_screening  = get_post_meta($post->ID, 'uses_ai_screening', true);
			$accommodation_note = get_post_meta($post->ID, 'accommodation_note', true);
			?>
			<p>
				<label for="fnesl-job-type"><strong><?php esc_html_e('Job Type', 'fnesl'); ?></strong></label><br />
				<input type="text" id="fnesl-job-type" name="job_type" value="<?php echo esc_attr($job_type); ?>" class="widefat" placeholder="<?php echo esc_attr__('Full-time', 'fnesl'); ?>" />
			</p>
			<p>
				<label for="fnesl-job-location"><strong><?php esc_html_e('Location', 'fnesl'); ?></strong></label><br />
				<input type="text" id="fnesl-job-location" name="job_location" value="<?php echo esc_attr($job_location); ?>" class="widefat" placeholder="<?php echo esc_attr__('Toronto, ON or Remote', 'fnesl'); ?>" />
			</p>
			<p>
				<label for="fnesl-job-application-email"><strong><?php esc_html_e('Application Email', 'fnesl'); ?></strong></label><br />
				<input type="email" id="fnesl-job-application-email" name="application_email" value="<?php echo esc_attr($application_email); ?>" class="widefat" placeholder="careers@example.com" />
			</p>
			<p>
				<label for="fnesl-job-closing-date"><strong><?php esc_html_e('Closing Date', 'fnesl'); ?></strong></label><br />
				<input type="date" id="fnesl-job-closing-date" name="closing_date" value="<?php echo esc_attr($closing_date); ?>" class="widefat" />
			</p>
			<p>
				<label for="fnesl-job-expiry-date"><strong><?php esc_html_e('Expiry Date', 'fnesl'); ?></strong></label><br />
				<input type="date" id="fnesl-job-expiry-date" name="expiry_date" value="<?php echo esc_attr($expiry_date); ?>" class="widefat" />
				<small><?php esc_html_e('Posting is hidden automatically after this date. Leave blank to keep it visible until manually removed.', 'fnesl'); ?></small>
			</p>
			<p>
				<label for="fnesl-job-salary-text"><strong><?php esc_html_e('Compensation', 'fnesl'); ?></strong></label><br />
				<input type="text" id="fnesl-job-salary-text" name="salary_text" value="<?php echo esc_attr($salary_text); ?>" class="widefat" placeholder="<?php echo esc_attr__('$70,000 - $85,000 annually', 'fnesl'); ?>" />
			</p>
			<p>
				<label for="fnesl-job-is-vacancy"><strong><?php esc_html_e('Vacancy Status', 'fnesl'); ?></strong></label><br />
				<select id="fnesl-job-is-vacancy" name="is_vacancy" class="widefat">
					<option value=""><?php esc_html_e('Select status', 'fnesl'); ?></option>
					<option value="yes" <?php selected( $is_vacancy, 'yes' ); ?>><?php esc_html_e('Active vacancy', 'fnesl'); ?></option>
					<option value="no" <?php selected( $is_vacancy, 'no' ); ?>><?php esc_html_e('Not an active vacancy', 'fnesl'); ?></option>
				</select>
			</p>
			<p>
				<label for="fnesl-job-uses-ai-screening"><strong><?php esc_html_e('AI Screening Disclosure', 'fnesl'); ?></strong></label><br />
				<select id="fnesl-job-uses-ai-screening" name="uses_ai_screening" class="widefat">
					<option value=""><?php esc_html_e('Select status', 'fnesl'); ?></option>
					<option value="yes" <?php selected( $uses_ai_screening, 'yes' ); ?>><?php esc_html_e('AI is used in screening', 'fnesl'); ?></option>
					<option value="no" <?php selected( $uses_ai_screening, 'no' ); ?>><?php esc_html_e('AI is not used in screening', 'fnesl'); ?></option>
				</select>
			</p>
			<p>
				<label for="fnesl-job-accommodation-note"><strong><?php esc_html_e('Accommodation Note', 'fnesl'); ?></strong></label><br />
				<textarea id="fnesl-job-accommodation-note" name="accommodation_note" class="widefat" rows="4" placeholder="<?php echo esc_attr__('Accommodation is available on request throughout the recruitment process.', 'fnesl'); ?>"><?php echo esc_textarea($accommodation_note); ?></textarea>
			</p>
			<?php
		},
		'job',
		'normal',
		'default'
	);
});

add_action('save_post_job', function ( $post_id ) {
	if ( ! isset($_POST['fnesl_job_details_nonce']) || ! wp_verify_nonce( sanitize_text_field( wp_unslash($_POST['fnesl_job_details_nonce']) ), 'fnesl_save_job_details' ) ) {
		return;
	}

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can('edit_post', $post_id) ) {
		return;
	}

	$fields = [
		'job_type'           => 'sanitize_text_field',
		'job_location'       => 'sanitize_text_field',
		'application_email'  => 'sanitize_email',
		'closing_date'       => 'fnesl_sanitize_job_date',
		'expiry_date'        => 'fnesl_sanitize_job_date',
		'salary_text'        => 'sanitize_text_field',
		'is_vacancy'         => 'fnesl_sanitize_job_flag',
		'uses_ai_screening'  => 'fnesl_sanitize_job_flag',
		'accommodation_note' => 'sanitize_textarea_field',
	];

	foreach ( $fields as $key => $sanitize ) {
		$value = isset($_POST[ $key ]) ? call_user_func( $sanitize, wp_unslash($_POST[ $key ]) ) : '';

		if ( '' === $value || false === $value ) {
			delete_post_meta($post_id, $key);
			continue;
		}

		update_post_meta($post_id, $key, $value);
	}

	$closing_date = get_post_meta( $post_id, 'closing_date', true );
	$expiry_date  = get_post_meta( $post_id, 'expiry_date', true );

	if ( $closing_date && ! $expiry_date ) {
		update_post_meta( $post_id, 'expiry_date', $closing_date );
	}
});

add_filter('use_block_editor_for_post_type', function ( $use_block_editor, $post_type ) {
	if ( 'job' === $post_type ) {
		return false;
	}

	return $use_block_editor;
}, 10, 2);
