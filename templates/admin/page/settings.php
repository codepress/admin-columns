<?php
/**
 * @var \AC\Renderable $section
 */
?>
<table class="form-table ac-form-table settings">
	<tbody>

	<?php foreach ( $this->sections as $section ) : ?>
		<?= $section->render(); ?>
	<?php endforeach; ?>

	</tbody>
</table>