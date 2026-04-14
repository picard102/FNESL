<?php
/**
 * Job meta sidebar/details.
 */

$job_id = isset( $args['job_id'] ) ? (int) $args['job_id'] : 0;

if ( ! $job_id ) {
	return;
}

$job_type           = trim( (string) get_post_meta( $job_id, 'job_type', true ) );
$job_location       = trim( (string) get_post_meta( $job_id, 'job_location', true ) );
$application_email  = trim( (string) get_post_meta( $job_id, 'application_email', true ) );
$closing_date       = trim( (string) get_post_meta( $job_id, 'closing_date', true ) );
$salary_text        = trim( (string) get_post_meta( $job_id, 'salary_text', true ) );
$is_vacancy         = trim( (string) get_post_meta( $job_id, 'is_vacancy', true ) );
$uses_ai_screening  = trim( (string) get_post_meta( $job_id, 'uses_ai_screening', true ) );
$accommodation_note = trim( (string) get_post_meta( $job_id, 'accommodation_note', true ) );

if ( ! function_exists( 'fnesl_job_display_date' ) ) {
	function fnesl_job_display_date( $value ) {
		if ( empty( $value ) ) {
			return '';
		}

		$timestamp = strtotime( $value );
		return $timestamp ? wp_date( 'F j, Y', $timestamp ) : $value;
	}
}
?>

<div class="grid gap-5">
	<div class="rounded-[1.5rem] border border-primary-100 bg-white p-6 shadow-[0_10px_30px_rgba(48,89,110,0.07)]">
		<div class="flex items-center justify-between gap-4">
			<h2 class="text-lg font-medium text-primary-950"><?php esc_html_e( 'Position Details', 'fnesl' ); ?></h2>
			<?php if ( $job_type ) : ?>
				<span class="rounded-full border border-accent-200 bg-[color:var(--wp--preset--color--accent-200)]/35 px-3 py-1 text-xs font-medium text-accent-700"><?php echo esc_html( $job_type ); ?></span>
			<?php endif; ?>
		</div>

		<dl class="mt-5 grid gap-4 text-sm">
			<?php if ( $job_location ) : ?>
				<div class="grid gap-1">
					<dt class="text-primary-500"><?php esc_html_e( 'Location', 'fnesl' ); ?></dt>
					<dd class="text-primary-900"><?php echo esc_html( $job_location ); ?></dd>
				</div>
			<?php endif; ?>

			<?php if ( $salary_text ) : ?>
				<div class="grid gap-1">
					<dt class="text-primary-500"><?php esc_html_e( 'Compensation', 'fnesl' ); ?></dt>
					<dd class="text-primary-900"><?php echo esc_html( $salary_text ); ?></dd>
				</div>
			<?php endif; ?>

			<?php if ( $closing_date ) : ?>
				<div class="grid gap-1">
					<dt class="text-primary-500"><?php esc_html_e( 'Closing Date', 'fnesl' ); ?></dt>
					<dd class="text-primary-900"><?php echo esc_html( fnesl_job_display_date( $closing_date ) ); ?></dd>
				</div>
			<?php endif; ?>
		</dl>

		<?php if ( $application_email ) : ?>
			<div class="mt-6">
				<a href="mailto:<?php echo esc_attr( antispambot( $application_email ) ); ?>" class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-5 py-3 text-sm font-medium text-primary-900 no-underline hover:bg-primary-100">
					<?php esc_html_e( 'Apply by Email', 'fnesl' ); ?>
					<svg class="h-3 w-3 fill-current" aria-hidden="true">
						<use xlink:href="#icons_arrow_east"></use>
					</svg>
				</a>
				<p class="mt-3 text-sm text-primary-600"><?php echo esc_html( antispambot( $application_email ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="rounded-[1.5rem] border border-primary-100 bg-background p-6">
		<h3 class="text-base font-medium text-primary-950"><?php esc_html_e( 'Hiring Notes', 'fnesl' ); ?></h3>
		<div class="mt-4 grid gap-3 text-sm leading-6 text-primary-700">
			<?php if ( 'yes' === $uses_ai_screening ) : ?>
				<p><?php esc_html_e( 'AI is used during applicant screening for this role.', 'fnesl' ); ?></p>
			<?php elseif ( 'no' === $uses_ai_screening ) : ?>
				<p><?php esc_html_e( 'AI is not used during applicant screening for this role.', 'fnesl' ); ?></p>
			<?php endif; ?>

			<?php if ( 'yes' === $is_vacancy ) : ?>
				<p><?php esc_html_e( 'This posting is tied to an active vacancy.', 'fnesl' ); ?></p>
			<?php elseif ( 'no' === $is_vacancy ) : ?>
				<p><?php esc_html_e( 'This posting is not tied to an active vacancy.', 'fnesl' ); ?></p>
			<?php endif; ?>

			<?php if ( $accommodation_note ) : ?>
				<p><?php echo esc_html( $accommodation_note ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</div>
