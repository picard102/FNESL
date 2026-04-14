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
$expiry_date        = trim( (string) get_post_meta( $job_id, 'expiry_date', true ) );
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
	<div class="rounded-[1.5rem] border border-primary-200 bg-primary-950 p-6 text-white shadow-[0_18px_44px_rgba(24,40,52,0.22)]">
		<p class="text-xs font-medium uppercase tracking-[0.2em] text-primary-300"><?php esc_html_e( 'Apply', 'fnesl' ); ?></p>
		<h2 class="mt-3 text-2xl font-medium leading-tight text-white"><?php esc_html_e( 'Interested in this role?', 'fnesl' ); ?></h2>
		<p class="mt-3 text-sm leading-6 text-primary-100"><?php esc_html_e( 'Send your application materials by email. Include the role title in your subject line so we can route it quickly.', 'fnesl' ); ?></p>

		<?php if ( $application_email ) : ?>
			<div class="mt-6">
				<a href="mailto:<?php echo esc_attr( antispambot( $application_email ) ); ?>" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white px-5 py-3 text-sm font-medium text-primary-950 no-underline hover:bg-primary-50">
					<?php esc_html_e( 'Apply by Email', 'fnesl' ); ?>
					<svg class="h-3 w-3 fill-current" aria-hidden="true">
						<use xlink:href="#icons_arrow_east"></use>
					</svg>
				</a>
				<p class="mt-3 break-all text-sm text-primary-100"><?php echo esc_html( antispambot( $application_email ) ); ?></p>
			</div>
		<?php else : ?>
			<p class="mt-6 text-sm leading-6 text-primary-100"><?php esc_html_e( 'Application instructions will be provided here when this posting is ready to receive submissions.', 'fnesl' ); ?></p>
		<?php endif; ?>

		<?php if ( $closing_date ) : ?>
			<div class="mt-6 rounded-[1.25rem] border border-white/10 bg-white/6 p-4">
				<p class="text-xs uppercase tracking-[0.18em] text-primary-300"><?php esc_html_e( 'Closing Date', 'fnesl' ); ?></p>
				<p class="mt-2 text-base font-medium text-white"><?php echo esc_html( fnesl_job_display_date( $closing_date ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="rounded-[1.5rem] border border-primary-100 bg-white p-6 shadow-[0_10px_30px_rgba(48,89,110,0.07)]">
		<div class="flex items-center justify-between gap-4">
			<h3 class="text-lg font-medium text-primary-950"><?php esc_html_e( 'Position Details', 'fnesl' ); ?></h3>
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

			<?php if ( $expiry_date ) : ?>
				<div class="grid gap-1">
					<dt class="text-primary-500"><?php esc_html_e( 'Posting Expires', 'fnesl' ); ?></dt>
					<dd class="text-primary-900"><?php echo esc_html( fnesl_job_display_date( $expiry_date ) ); ?></dd>
				</div>
			<?php endif; ?>
		</dl>
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
