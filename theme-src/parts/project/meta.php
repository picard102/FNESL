<?php
/**
 * Project Sidebar Meta
 * Displays expertise, client, location, awards, and timeline.
 */

$post_id = isset( $args['project_id'] ) ? (int) $args['project_id'] : get_the_ID();

// ===============================
// Helper: Safe term getter
// ===============================
function fnesl_get_terms_safe( $post_id, $taxonomy ) {
	if ( ! $post_id ) {
		return [];
	}
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return [];
	}
	return $terms;
}

// ===============================
// Retrieve data safely
// ===============================
$expertise_terms = fnesl_get_terms_safe( $post_id, 'expertise' );
$client_terms    = fnesl_get_terms_safe( $post_id, 'client' );
$location_terms  = fnesl_get_terms_safe( $post_id, 'location' );
//$award_terms     = fnesl_get_terms_safe( $post_id, 'award' );


// $timeline = get_post_meta( $post_id, 'project_timeline', true );

// ===============================
// Early bail if everything is empty
// ===============================
if (
	empty( $expertise_terms ) &&
	empty( $client_terms ) &&
	empty( $location_terms ) &&
	empty( $award_terms ) &&
	empty( $timeline )
) {
	return;
}

// ===============================
// Output
// ===============================
?>
<aside class="project-meta space-y-8 has-sm-font-size">

	<?php if ( ! empty( $expertise_terms ) ) : ?>
		<div class="project-meta-expertise flex flex-col">
			<h3 class="font-serif text-sm mb-3">Expertise:</h3>
			<?php
			foreach ( $expertise_terms as $term ) :
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}

				// Get top-level parent for icon reference
				$parent = $term;
				while ( $parent->parent ) {
					$maybe_parent = get_term( $parent->parent, 'expertise' );
					if ( ! $maybe_parent || is_wp_error( $maybe_parent ) ) {
						break;
					}
					$parent = $maybe_parent;
				}

				$icon_id = sanitize_title( $parent->name ?? $term->name );
				?>
				<div class="flex items-center gap-2 mb-2">
					<svg class="aspect-square h-7 fill-primary-400" aria-hidden="true">
						<use xlink:href="#exp-<?php echo esc_attr( $icon_id ); ?>"></use>
					</svg>
					<span class="font-semi font-sans"><?php echo esc_html( $term->name ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $client_terms ) ) : ?>
		<div class="project-meta-client">
			<h3 class="font-serif text-sm mb-2">Client:</h3>
			<p class="font-sans">
				<?php
				$clients = wp_list_pluck( $client_terms, 'name' );
				echo esc_html( implode( ', ', $clients ) );
				?>
			</p>
		</div>
	<?php endif; ?>


	<?php if ( ! empty( $location_terms ) ) : ?>
	<div class="project-meta-location">
		<h3 class="font-serif text-sm mb-2">Location:</h3>

		<div class="font-sans space-y-1">
			<?php
			foreach ( $location_terms as $term ) {
				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}

				// Skip non-leaf nodes (terms that have children)
				$children = get_terms( [
					'taxonomy'   => 'location',
					'parent'     => $term->term_id,
					'hide_empty' => false,
					'fields'     => 'ids',
				] );

				if ( ! empty( $children ) ) {
					continue;
				}

				// Determine display chain
				$line = esc_html( $term->name );

				if ( $term->parent ) {
					$parent = get_term( $term->parent, 'location' );
					if ( $parent && ! is_wp_error( $parent ) ) {
						$line .= ', ' . esc_html( $parent->name );
					}
				}

				echo '<div>' . $line . '</div>';
			}
			?>
		</div>
	</div>
<?php endif; ?>



	<?php if ( ! empty( $timeline ) ) : ?>
		<div class="project-meta-timeline">
			<h3 class="font-serif text-sm mb-2">Timeline:</h3>
			<p class="font-sans"><?php echo esc_html( $timeline ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $award_terms ) ) : ?>
		<div class="project-meta-awards">
			<h3 class="font-serif text-sm mb-2">Awards:</h3>
			<ul class="list-disc list-inside font-sans">
				<?php
				foreach ( $award_terms as $award ) :
					if ( empty( $award->name ) ) {
						continue;
					}
					echo '<li>' . esc_html( $award->name ) . '</li>';
				endforeach;
				?>
			</ul>
		</div>
	<?php endif; ?>

</aside>