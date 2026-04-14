<?php
/**
 * Template for displaying single Jobs.
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();

		$job_id            = get_the_ID();
		$job_type          = trim( (string) get_post_meta( $job_id, 'job_type', true ) );
		$job_location      = trim( (string) get_post_meta( $job_id, 'job_location', true ) );
		$closing_date      = trim( (string) get_post_meta( $job_id, 'closing_date', true ) );
		$salary_text       = trim( (string) get_post_meta( $job_id, 'salary_text', true ) );
		$application_email = trim( (string) get_post_meta( $job_id, 'application_email', true ) );
		$summary           = has_excerpt() ? get_the_excerpt() : '';

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

		<?php
		get_template_part(
			'parts/menu',
			null,
			[
				'variant' => 'transparent',
			]
		);
		?>

		<main class="wp-site-blocks">
			<section class="alignwide px-6">
				<div class="rounded-[2rem] bg-background px-6 py-8 md:px-10 md:py-12">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'job' ) ); ?>" class="inline-flex items-center gap-2 text-sm text-primary-700 no-underline hover:underline">
						<svg class="h-3 w-3 rotate-180 fill-current" aria-hidden="true">
							<use xlink:href="#icons_arrow_east"></use>
						</svg>
						<?php esc_html_e( 'Back to Careers', 'fnesl' ); ?>
					</a>

					<div class="mt-6 grid gap-8 lg:grid-cols-[minmax(0,1.5fr)_minmax(18rem,0.9fr)] lg:items-start">
						<div class="min-w-0">
							<div class="flex flex-wrap items-center gap-3">
								<p class="text-sm uppercase tracking-[0.22em] text-primary-500"><?php esc_html_e( 'Career Opportunity', 'fnesl' ); ?></p>
								<?php if ( $job_type ) : ?>
									<span class="rounded-full border border-accent-200 bg-[color:var(--wp--preset--color--accent-200)]/35 px-3 py-1 text-xs font-medium text-accent-700"><?php echo esc_html( $job_type ); ?></span>
								<?php endif; ?>
							</div>

							<h1 class="mt-4 font-serif text-4xl leading-tight text-primary-950 md:text-6xl"><?php the_title(); ?></h1>

							<div class="mt-6 flex flex-wrap items-center gap-x-3 gap-y-2 text-base text-primary-700">
								<?php if ( $job_location ) : ?>
									<span><?php echo esc_html( $job_location ); ?></span>
								<?php endif; ?>

								<?php if ( $job_location && $salary_text ) : ?>
									<span class="text-primary-400">•</span>
								<?php endif; ?>

								<?php if ( $salary_text ) : ?>
									<span><?php echo esc_html( $salary_text ); ?></span>
								<?php endif; ?>

								<?php if ( $closing_date ) : ?>
									<?php if ( $job_location || $salary_text ) : ?>
										<span class="text-primary-400">•</span>
									<?php endif; ?>
									<span><?php echo esc_html__( 'Closes', 'fnesl' ); ?> <?php echo esc_html( fnesl_job_display_date( $closing_date ) ); ?></span>
								<?php endif; ?>
							</div>

							<?php if ( $summary ) : ?>
								<div class="mt-8 max-w-3xl text-lg leading-8 text-primary-800">
									<?php echo wp_kses_post( wpautop( $summary ) ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $application_email ) : ?>
								<div class="mt-8">
									<a href="mailto:<?php echo esc_attr( antispambot( $application_email ) ); ?>" class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white px-5 py-3 text-sm font-medium text-primary-900 no-underline shadow-[0_2px_10px_rgba(48,89,110,0.06)] hover:bg-primary-50">
										<?php esc_html_e( 'Apply by Email', 'fnesl' ); ?>
										<svg class="h-3 w-3 fill-current" aria-hidden="true">
											<use xlink:href="#icons_arrow_east"></use>
										</svg>
									</a>
								</div>
							<?php endif; ?>
						</div>

						<aside class="lg:sticky lg:top-8">
							<?php get_template_part( 'parts/job/meta', null, [ 'job_id' => $job_id ] ); ?>
						</aside>
					</div>
				</div>
			</section>

			<section class="alignwide px-6">
				<div class="grid gap-12 lg:grid-cols-[minmax(0,1.5fr)_minmax(18rem,0.9fr)]">
					<article class="prose max-w-none text-primary-900">
						<?php the_content(); ?>
					</article>

					<div class="hidden lg:block"></div>
				</div>
			</section>
		</main>
		<?php
	endwhile;
endif;

get_footer();
