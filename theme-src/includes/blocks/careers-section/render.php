<?php
/**
 * Render callback for Careers Section block.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count = max( 1, (int) ( $attributes['count'] ?? 6 ) );
$today = wp_date( 'Y-m-d' );

$jobs = get_posts( [
	'post_type'      => 'job',
	'post_status'    => 'publish',
	'posts_per_page' => $count,
	'orderby'        => [
		'menu_order' => 'ASC',
		'date'       => 'DESC',
	],
	'meta_query'     => [
		'relation' => 'OR',
		[
			'key'     => 'expiry_date',
			'compare' => 'NOT EXISTS',
		],
		[
			'key'     => 'expiry_date',
			'value'   => '',
			'compare' => '=',
		],
		[
			'key'     => 'expiry_date',
			'value'   => $today,
			'compare' => '>=',
			'type'    => 'DATE',
		],
	],
] );

$jobs_data = array_map(
	static function ( $job ) {
		return [
			'id'          => (int) $job->ID,
			'title'       => get_the_title( $job->ID ),
			'link'        => get_permalink( $job->ID ),
			'jobType'     => (string) get_post_meta( $job->ID, 'job_type', true ),
			'location'    => (string) get_post_meta( $job->ID, 'job_location', true ),
			'salaryText'  => (string) get_post_meta( $job->ID, 'salary_text', true ),
			'closingDate' => (string) get_post_meta( $job->ID, 'closing_date', true ),
		];
	},
	$jobs
);

$config = [
	'jobs' => $jobs_data,
];
?>
<div
	<?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	data-careers-section
	data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
></div>
