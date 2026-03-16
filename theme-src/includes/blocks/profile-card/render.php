<?php
/**
 * Render callback for Profile Card block
 */

$profile_id = $attributes['profileId'] ?? 0;
if (!$profile_id) {
    return '';
}

$post = get_post($profile_id);
if (!$post) {
    return '';
}

$image = get_the_post_thumbnail($profile_id, 'large', [
    'class' => 'profile-card-image',
]);

$title = esc_html(get_the_title($profile_id));

$roles = get_the_terms($profile_id, 'Roles');
$role_label = $roles && !is_wp_error($roles)
    ? esc_html($roles[0]->name)
    : '';


$credentials = get_the_terms($profile_id, 'credentials');
$credentials_label = $credentials && !is_wp_error($credentials)
    ? esc_html(implode(', ', wp_list_pluck($credentials, 'name')))
    : '';

$modal_id = 'profile-modal-' . $profile_id;
$has_content = !empty(trim($post->post_content));
?>

<!-- Card -->
<?php if ($has_content) : ?>
<button
    type="button"
    data-profile-modal="<?php echo esc_attr($modal_id); ?>"
    class="profile-card w-full   text-left text-gray-900 cursor-pointer
		flex flex-col gap-3"
>
<?php else : ?>
<div class="profile-card w-full   text-left text-gray-900
		flex flex-col gap-3">
<?php endif; ?>

    <div class="w-full aspect-[4/3] rounded-sm overflow-hidden border border-primary-100  outline-1 -outline-offset-1 outline-black/5 bg-primary-100 ">
        <?php
        echo str_replace(
            '<img',
            '<img class="w-full h-full object-cover object-top"',
            $image
        );
        ?>
    </div>

		<div class="flex flex-col  ">
			<h3 class="text-base font-medium text-pretty text-primary-900 line-clamp-2 leading-tight mb-1">
					<?php echo $title; ?>
			</h3>


            <?php if ($credentials_label) : ?>
            <p class="text-sm text-primary-600">
                <?php echo $credentials_label; ?>
            </p>
            <?php endif; ?>

            <?php if ($role_label) : ?>
            <p class="text-sm text-primary-800">
                <?php echo $role_label; ?>
            </p>
            <?php endif; ?>

		</div>

<?php if ($has_content) : ?>
</button>
<?php else : ?>
</div>
<?php endif; ?>



<?php if ($has_content) : ?>
<!-- Modal -->
<dialog id="<?php echo esc_attr($modal_id); ?>" class="profile-modal rounded-xl p-0 max-w-xl w-[90vw]">

    <form method="dialog" class="fixed inset-0 bg-black/50"></form>

    <div class="bg-white p-6 rounded-xl relative z-10">

        <div class="w-full aspect-[4/5] rounded-xl overflow-hidden mb-4">
            <?php
            echo str_replace(
                '<img',
                '<img class="w-full h-full object-cover"',
                $image
            );
            ?>
        </div>

        <h2 class="text-2xl font-semibold mb-2"><?php echo $title; ?></h2>
        <p class="text-gray-500 mb-4"><?php echo $role_label; ?></p>

        <div class="prose max-w-none mb-6">
            <?php echo wpautop($post->post_content); ?>
        </div>

        <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg" value="close">
            Close
        </button>
    </div>
</dialog>
<?php endif; ?>