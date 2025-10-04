<?php
// render.php for fnesl/project-expertise-header

$project_id = get_the_ID();
$terms = wp_get_post_terms( $project_id, 'expertise', [ 'parent' => 0 ] );

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    $term_id = $attributes['selectedExpertise'] ?? null;

    if ( ! $term_id && count( $terms ) === 1 ) {
        $term_id = $terms[0]->term_id;

    }

    if ( ! $term_id ) {
        $term_id = $terms[0]->term_id; // fallback
    }

    $term = get_term( $term_id );

    if ( $term && ! is_wp_error( $term ) ) {
        ?>
        <div class="flex gap-2 items-center">

					<svg class=" aspect-square h-5 fill-current " aria-hidden="true">
						<use xlink:href="#exp-<?php echo esc_attr( $term->slug ); ?>"></use>
					</svg>


            <span class="text-lg"><?php echo esc_html( $term->name ); ?></span>
        </div>
        <?php
    }
}