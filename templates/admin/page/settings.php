<?php
/**
 * @var \AC\Renderable $section
 */
?>


<h1 class="screen-reader-text"><?= __( 'Settings', 'codepress-admin-columns' ); ?></h1>

<div class="cpac-admin-notices">
	<?php do_action( 'ac/settings/general/notice' ); ?>
</div>

<div class="ac-settings">
	<?php foreach ( $this->sections as $section ) : ?>
		<?= $section->render(); ?>
	<?php endforeach; ?>
</div>