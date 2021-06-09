<?php
/**
 * @var \AC\Renderable $section
 */
?>
<h1 class="screen-reader-text"><?= __('Settings', 'codepress-admin-columns'); ?></h1>
<table class="form-table ac-form-table settings">
	<tbody>

	<?php foreach ( $this->sections as $section ) : ?>
		<?= $section->render(); ?>
	<?php endforeach; ?>

	</tbody>
</table>