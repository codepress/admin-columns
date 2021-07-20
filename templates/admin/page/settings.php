<?php
/**
 * @var \AC\Renderable $section
 */
?>
<h1 class="screen-reader-text"><?= __('Settings', 'codepress-admin-columns'); ?></h1>

<div class="ac-settings">
	<?php foreach ( $this->sections as $section ) : ?>
		<?= $section->render(); ?>
	<?php endforeach; ?>
</div>